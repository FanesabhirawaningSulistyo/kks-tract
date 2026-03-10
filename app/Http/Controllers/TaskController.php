<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Models\ProjekTim;
use App\Models\Tugas;
use App\Models\TugasFoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    private const WEIGHT_MAP = [
        'mudah'  => 1,
        'medium' => 2,
        'susah'  => 3,
    ];

    // ══════════════════════════════════════════════
    // PAGES
    // ══════════════════════════════════════════════

    public function index(Request $request, $id_projek)
    {
        /** @var User $user */
        $user   = Auth::user();
        $projek = Projek::with([
            'perusahaan',
            'kategoriProjek',
            'pembuat',
            'tim.user',
        ])->findOrFail($id_projek);

        // ─────────────────────────────────────────────
        // ROLE-BASED ACCESS CHECK
        // ─────────────────────────────────────────────
        if ($user->isKaryawan()) {
            // Karyawan hanya boleh akses project yang dia tergabung di tim
            $tergabung = ProjekTim::where('id_projek', $id_projek)
                ->where('id_user', $user->id_user)
                ->exists();
            if (!$tergabung) {
                abort(403, 'Anda tidak tergabung dalam project ini.');
            }
        } elseif ($user->isKlien()) {
            // Klien hanya boleh akses project dari perusahaannya
            $perusahaan = $user->perusahaan;
            if (!$perusahaan || $projek->id_perusahaan !== $perusahaan->id_perusahaan) {
                abort(403, 'Anda tidak memiliki akses ke project ini.');
            }
        } elseif ($user->isPM()) {
            // PM hanya boleh akses project yang dia buat
            if ($projek->dibuat_oleh !== $user->id_user) {
                abort(403, 'Anda tidak memiliki akses ke project ini.');
            }
        }
        // Admin: boleh akses semua project, tidak ada pengecekan tambahan

        $timProject = ProjekTim::with('user.jobRole')
            ->where('id_projek', $id_projek)
            ->get();

        $userTersedia = User::with('jobRole')
            ->whereNotIn('id_user', $timProject->pluck('id_user'))
            ->where('role', 'karyawan')
            ->orderBy('nama')
            ->get();

        $tasks = $projek->tugas()
            ->with(['tim.user', 'foto'])
            ->orderBy('dibuat_pada', 'asc')
            ->get();

        $stats = [
            'total'    => $tasks->count(),
            'done'     => $tasks->where('status_progress', 'done')->count(),
            'progress' => $tasks->whereIn('status_progress', ['In Progress'])->count(),
            'todo'     => $tasks->whereIn('status_progress', ['draft', 'To Do'])->count(),
            'approved' => $tasks->where('status_akhir', 'approved')->count(),
        ];

        $projek->setRelation('tugas', $tasks);

        return view('dashboard.kelolatask', compact('projek', 'timProject', 'userTersedia', 'stats'));
    }

    public function index2(Request $request)
    {
        $user    = Auth::user();
        $timList = ProjekTim::with(['projek', 'tugas.foto'])->where('id_user', $user->id_user)->get();
        return view('dashboard.task-karyawan', compact('timList', 'user'));
    }

    public function kelolaproject(Request $request)
    {
        return redirect()->route('dashboard.master-data-projek');
    }

    // ══════════════════════════════════════════════
    // TIM
    // ══════════════════════════════════════════════

    public function inviteTim(Request $request, $id_projek)
    {
        $request->validate([
            'id_user'   => 'required|array|min:1',
            'id_user.*' => 'exists:users,id_user',
        ]);

        $sudahAda = ProjekTim::where('id_projek', $id_projek)
            ->whereIn('id_user', $request->id_user)->pluck('id_user')->toArray();
        $userBaru = array_diff($request->id_user, $sudahAda);

        foreach ($userBaru as $id_user) {
            ProjekTim::create(['id_projek' => $id_projek, 'id_user' => $id_user]);
        }

        $jumlah = count($userBaru);
        if ($jumlah === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Semua user yang dipilih sudah menjadi anggota tim.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => "$jumlah anggota berhasil ditambahkan ke tim.",
            'added'   => $jumlah,
        ]);
    }

    public function removeTim(Request $request, $id_projek, $id_tim)
    {
        $tim = ProjekTim::where('id_tim', $id_tim)->where('id_projek', $id_projek)->firstOrFail();

        $tugasAktif = Tugas::where('id_tim', $id_tim)->whereNotIn('status_progress', ['done'])->count();
        if ($tugasAktif > 0) {
            return response()->json([
                'success' => false,
                'message' => "Tidak dapat menghapus anggota karena masih memiliki $tugasAktif tugas yang belum selesai.",
            ], 422);
        }

        $tim->delete();
        return response()->json(['success' => true, 'message' => 'Anggota berhasil dikeluarkan dari tim.']);
    }

    // ══════════════════════════════════════════════
    // TASK CRUD
    // ══════════════════════════════════════════════

    public function getTasks($id_projek)
    {
        $tasks = Tugas::with(['tim.user', 'foto'])
            ->where('id_projek', $id_projek)
            ->orderBy('dibuat_pada', 'asc')
            ->get()
            ->map(fn($task) => $this->formatTask($task));

        return response()->json(['success' => true, 'data' => $tasks]);
    }

    /**
     * GET /projek/{id}/task/tim-data
     * Digunakan oleh modal Kelola Tim untuk refresh tanpa reload halaman.
     */
    public function getTimData($id_projek)
    {
        $timProject = ProjekTim::with('user.jobRole')
            ->where('id_projek', $id_projek)
            ->get();

        $userTersedia = User::with('jobRole')
            ->whereNotIn('id_user', $timProject->pluck('id_user'))
            ->where('role', 'karyawan')
            ->orderBy('nama')
            ->get();

        $tim = $timProject->map(fn($t) => [
            'id_tim'  => $t->id_tim,
            'id_user' => $t->id_user,
            'nama'    => optional($t->user)->nama    ?? '—',
            'email'   => optional($t->user)->email   ?? '',
            'jabatan' => optional(optional($t->user)->jobRole)->nama_job_role ?? null,
        ])->values()->all();

        $users = $userTersedia->map(fn($u) => [
            'id_user' => $u->id_user,
            'nama'    => $u->nama    ?? '—',
            'email'   => $u->email   ?? '',
            'jabatan' => optional($u->jobRole)->nama_job_role ?? null,
        ])->values()->all();

        return response()->json(['success' => true, 'tim' => $tim, 'users' => $users]);
    }


    public function storeTask(Request $request, $id_projek)
    {
        $validator = Validator::make($request->all(), [
            'judul_tugas'     => 'required|string|max:255',
            'deskripsi_tugas' => 'nullable|string',
            'id_tim'          => 'required|exists:projek_tim,id_tim',
            'level'           => 'required|in:mudah,medium,susah',
            'status_progress' => 'required|in:draft,To Do,In Progress,done',
            'status_akhir'    => 'nullable|in:review,revisi,approved',
            'tenggat_waktu'   => 'nullable|date',
            'tanggal_mulai'   => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        ProjekTim::where('id_tim', $request->id_tim)->where('id_projek', $id_projek)->firstOrFail();

        DB::beginTransaction();
        try {
            $weight         = self::WEIGHT_MAP[$request->level] ?? 1;
            $tanggalSelesai = ($request->status_progress === 'done') ? now()->toDateString() : null;

            $task = Tugas::create([
                'id_projek'       => $id_projek,
                'id_tim'          => $request->id_tim,
                'judul_tugas'     => $request->judul_tugas,
                'deskripsi_tugas' => $request->deskripsi_tugas ?? '',
                'level'           => $request->level,
                'weight'          => $weight,
                'status_progress' => $request->status_progress ?? 'To Do',
                'status_akhir'    => $request->status_akhir ?: null,
                'tenggat_waktu'   => $request->tenggat_waktu,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $tanggalSelesai,
            ]);

            DB::commit();
            $task->load(['tim.user', 'foto']);

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil ditambahkan.',
                'data'    => $this->formatTask($task),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan task: ' . $e->getMessage()], 500);
        }
    }

    public function updateTask(Request $request, $id_projek, $id_tugas)
    {
        $task = Tugas::where('id_tugas', $id_tugas)->where('id_projek', $id_projek)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'judul_tugas'     => 'sometimes|required|string|max:255',
            'deskripsi_tugas' => 'nullable|string',
            'id_tim'          => 'sometimes|exists:projek_tim,id_tim',
            'level'           => 'sometimes|in:mudah,medium,susah',
            'weight'          => 'sometimes|integer|min:1|max:3',
            'status_progress' => 'sometimes|in:draft,To Do,In Progress,done',
            'status_akhir'    => 'nullable|in:review,revisi,approved',
            'tenggat_waktu'   => 'nullable|date',
            'tanggal_mulai'   => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'judul_tugas',
            'deskripsi_tugas',
            'id_tim',
            'level',
            'status_progress',
            'status_akhir',
            'tenggat_waktu',
            'tanggal_mulai',
        ]);

        if (array_key_exists('status_akhir', $data)) {
            $data['status_akhir'] = $data['status_akhir'] ?: null;
        }

        // Task Approved TIDAK boleh mundur status
        if (
            isset($data['status_progress']) &&
            $task->status_akhir === 'approved' &&
            $task->status_progress === 'done' &&
            $data['status_progress'] !== 'done'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Task yang sudah Approved oleh PM tidak dapat diubah statusnya kembali.',
            ], 422);
        }

        // AUTO-SET tanggal_selesai
        if (isset($data['status_progress'])) {
            if ($task->status_progress !== 'done' && $data['status_progress'] === 'done') {
                $data['tanggal_selesai'] = now()->toDateString();
            } elseif ($task->status_progress === 'done' && $data['status_progress'] !== 'done') {
                $data['tanggal_selesai'] = null;
            }
        }

        if (isset($data['level'])) {
            $data['weight'] = self::WEIGHT_MAP[$data['level']] ?? 1;
        }

        $task->update($data);
        $task->load(['tim.user', 'foto']);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diperbarui.',
            'data'    => $this->formatTask($task),
        ]);
    }

    public function updateStatusAkhir(Request $request, $id_projek, $id_tugas)
    {
        $request->validate([
            'status_akhir' => 'nullable|in:review,revisi,approved',
        ]);

        $task  = Tugas::where('id_tugas', $id_tugas)->where('id_projek', $id_projek)->firstOrFail();
        $newSA = $request->status_akhir ?: null;

        if ($newSA === 'approved' && $task->status_progress !== 'done') {
            return response()->json([
                'success' => false,
                'message' => 'Task harus berstatus Done sebelum dapat di-Approved.',
            ], 422);
        }

        $task->update(['status_akhir' => $newSA]);

        return response()->json([
            'success' => true,
            'message' => 'Status akhir task berhasil diperbarui.',
            'data'    => ['status_akhir' => $task->status_akhir],
        ]);
    }

    public function destroyTask($id_projek, $id_tugas)
    {
        $task = Tugas::where('id_tugas', $id_tugas)->where('id_projek', $id_projek)->firstOrFail();

        if ($task->status_akhir === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Task yang sudah Approved tidak dapat dihapus.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($task->foto as $foto) {
                Storage::disk('public')->delete($foto->nama_file);
                $foto->delete();
            }
            $task->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Task berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus task: ' . $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════════
    // FOTO / LAPORAN UPLOAD
    // ══════════════════════════════════════════════

    public function uploadFoto(Request $request, $id_projek, $id_tugas)
    {
        $request->validate([
            'foto'   => 'required|array|min:1',
            'foto.*' => [
                'required',
                'file',
                'max:10240',
                function ($attribute, $value, $fail) {
                    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
                    if (!in_array(strtolower($value->getClientOriginalExtension()), $allowed)) {
                        $fail("Tipe file tidak diizinkan.");
                    }
                },
            ],
            'tipe' => 'required|in:brief,hasil',
        ]);

        $task     = Tugas::where('id_tugas', $id_tugas)->where('id_projek', $id_projek)->firstOrFail();
        $uploaded = [];

        foreach ($request->file('foto') as $file) {
            $path = $file->store('tugas-foto', 'public');
            $foto = TugasFoto::create([
                'id_tugas'  => $id_tugas,
                'nama_file' => $path,
                'tipe'      => $request->tipe,
            ]);
            $uploaded[] = [
                'id_tugas_foto' => $foto->id_tugas_foto,
                'url'           => Storage::url($path),
                'nama_file'     => $file->getClientOriginalName(),
                'tipe'          => $foto->tipe,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploaded) . ' file berhasil diupload.',
            'data'    => $uploaded,
        ]);
    }

    public function destroyFoto($id_projek, $id_tugas, $id_foto)
    {
        $foto = TugasFoto::where('id_tugas_foto', $id_foto)->where('id_tugas', $id_tugas)->firstOrFail();
        Storage::disk('public')->delete($foto->nama_file);
        $foto->delete();
        return response()->json(['success' => true, 'message' => 'File berhasil dihapus.']);
    }


    /**
     * GET /projek/{id}/tim-json
     * Untuk refresh list tim & user tersedia via AJAX tanpa reload halaman
     */
    public function getTimJson($id_projek)
    {
        $tim = ProjekTim::with('user.jobRole')
            ->where('id_projek', $id_projek)
            ->get()
            ->map(fn($t) => [
                'id_tim'  => $t->id_tim,
                'id_user' => $t->id_user,
                'nama'    => optional($t->user)->nama ?? '—',
                'jabatan' => optional(optional($t->user)->jobRole)->nama_job_role ?? 'Karyawan',
            ]);

        $userTersedia = User::with('jobRole')
            ->whereNotIn('id_user', $tim->pluck('id_user'))
            ->where('role', 'karyawan')
            ->orderBy('nama')
            ->get()
            ->map(fn($u) => [
                'id_user' => $u->id_user,
                'nama'    => $u->nama,
                'jabatan' => optional($u->jobRole)->nama_job_role ?? 'Karyawan',
            ]);

        return response()->json([
            'success'       => true,
            'tim'           => $tim,
            'user_tersedia' => $userTersedia,
        ]);
    }

    // ══════════════════════════════════════════════
    // HELPER
    // ══════════════════════════════════════════════

    private function formatTask(Tugas $task): array
    {
        return [
            'id_tugas'        => $task->id_tugas,
            'judul_tugas'     => $task->judul_tugas,
            'deskripsi_tugas' => $task->deskripsi_tugas,
            'id_tim'          => $task->id_tim,
            'nama_assignee'   => optional(optional($task->tim)->user)->nama ?? '—',
            'avatar'          => strtoupper(substr(optional(optional($task->tim)->user)->nama ?? 'XX', 0, 2)),
            'level'           => $task->level,
            'weight'          => $task->weight ?? (self::WEIGHT_MAP[$task->level] ?? 1),
            'status_progress' => $task->status_progress,
            'status_akhir'    => $task->status_akhir,
            'tenggat_waktu'   => $task->tenggat_waktu ? $task->tenggat_waktu->format('Y-m-d') : null,
            'tanggal_mulai'   => $task->tanggal_mulai ? $task->tanggal_mulai->format('Y-m-d') : null,
            'tanggal_selesai' => $task->tanggal_selesai ? $task->tanggal_selesai->format('Y-m-d') : null,
            'dibuat_pada'     => $task->dibuat_pada?->format('Y-m-d H:i:s'),
            'foto'            => $task->foto->map(fn($f) => [
                'id_tugas_foto' => $f->id_tugas_foto,
                'url'           => Storage::url($f->nama_file),
                'nama_file'     => $f->nama_file,
                'tipe'          => $f->tipe,
            ])->toArray(),
        ];
    }
}
