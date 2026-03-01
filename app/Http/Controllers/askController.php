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
    /**
     * Halaman Kelola Task (untuk PM - per project)
     */
    public function index(Request $request, $id_projek)
    {
        $projek = Projek::with([
            'perusahaan',
            'kategoriProjek',
            'pembuat',
            'tim.user',
            'tugas.tim.user',
            'tugas.foto',
        ])->findOrFail($id_projek);

        // Data tim project (anggota yang diundang)
        $timProject = ProjekTim::with('user')
            ->where('id_projek', $id_projek)
            ->get();

        // Semua user yang belum masuk tim ini (untuk modal undang)
        $userTersedia = User::whereNotIn('id_user', $timProject->pluck('id_user'))
            ->orderBy('nama')
            ->get();

        // Statistik task
        $tasks = $projek->tugas;
        $stats = [
            'total'      => $tasks->count(),
            'done'       => $tasks->where('status_progress', 'done')->count(),
            'progress'   => $tasks->whereIn('status_progress', ['progress', 'review'])->count(),
            'todo'       => $tasks->where('status_progress', 'todo')->count(),
            'approved'   => $tasks->where('status_akhir', 'approved')->count(),
        ];

        return view('kelola-task.index', compact(
            'projek',
            'timProject',
            'userTersedia',
            'stats'
        ));
    }

    /**
     * Halaman Task Karyawan (view untuk karyawan biasa)
     */
    public function index2(Request $request)
    {
        $user = Auth::user();

        // Ambil semua project tempat user ini menjadi tim
        $timList = ProjekTim::with(['projek', 'tugas.foto'])
            ->where('id_user', $user->id_user)
            ->get();

        return view('task-karyawan.index', compact('timList', 'user'));
    }

    /**
     * Halaman Kelola Project (list semua project)
     */
    public function kelolaproject(Request $request)
    {
        return redirect()->route('master-data-projek.index');
    }

    // ══════════════════════════════════════════════
    // INVITE TIM
    // ══════════════════════════════════════════════

    /**
     * Undang user ke dalam tim project
     */
    public function inviteTim(Request $request, $id_projek)
    {
        $request->validate([
            'id_user' => 'required|array|min:1',
            'id_user.*' => 'exists:users,id_user',
        ], [
            'id_user.required' => 'Pilih minimal satu user untuk diundang.',
            'id_user.min'      => 'Pilih minimal satu user untuk diundang.',
        ]);

        $projek = Projek::findOrFail($id_projek);

        $sudahAda = ProjekTim::where('id_projek', $id_projek)
            ->whereIn('id_user', $request->id_user)
            ->pluck('id_user')
            ->toArray();

        $userBaru = array_diff($request->id_user, $sudahAda);

        foreach ($userBaru as $id_user) {
            ProjekTim::create([
                'id_projek' => $id_projek,
                'id_user'   => $id_user,
            ]);
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

    /**
     * Keluarkan user dari tim
     */
    public function removeTim(Request $request, $id_projek, $id_tim)
    {
        $tim = ProjekTim::where('id_tim', $id_tim)
            ->where('id_projek', $id_projek)
            ->firstOrFail();

        // Cek apakah masih ada tugas aktif
        $tugasAktif = Tugas::where('id_tim', $id_tim)
            ->whereNotIn('status_progress', ['done'])
            ->count();

        if ($tugasAktif > 0) {
            return response()->json([
                'success' => false,
                'message' => "Tidak dapat menghapus anggota karena masih memiliki $tugasAktif tugas yang belum selesai.",
            ], 422);
        }

        $tim->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil dikeluarkan dari tim.',
        ]);
    }

    // ══════════════════════════════════════════════
    // TASK CRUD
    // ══════════════════════════════════════════════

    /**
     * Ambil semua task untuk project (JSON)
     */
    public function getTasks($id_projek)
    {
        $tasks = Tugas::with(['tim.user', 'foto'])
            ->where('id_projek', $id_projek)
            ->orderBy('dibuat_pada', 'asc')
            ->get()
            ->map(function ($task) {
                return $this->formatTask($task);
            });

        return response()->json([
            'success' => true,
            'data'    => $tasks,
        ]);
    }

    /**
     * Buat task baru
     */
    public function storeTask(Request $request, $id_projek)
    {
        $validator = Validator::make($request->all(), [
            'judul_tugas'    => 'required|string|max:255',
            'deskripsi_tugas' => 'nullable|string',
            'id_tim'         => 'required|exists:projek_tim,id_tim',
            'level'          => 'required|in:1,2,3,4,5',
            'weight'         => 'required|integer|min:1|max:100',
            'status_progress' => 'required|in:todo,progress,review,done',
            'status_akhir'   => 'nullable|in:review,revisi,approved',
            'tenggat_waktu'  => 'nullable|date',
            'tanggal_mulai'  => 'nullable|date',
            'foto'           => 'nullable|array',
            'foto.*'         => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'tipe_foto'      => 'nullable|string|in:brief,hasil',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Pastikan id_tim milik project ini
        $tim = ProjekTim::where('id_tim', $request->id_tim)
            ->where('id_projek', $id_projek)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $task = Tugas::create([
                'id_projek'      => $id_projek,
                'id_tim'         => $request->id_tim,
                'judul_tugas'    => $request->judul_tugas,
                'deskripsi_tugas' => $request->deskripsi_tugas,
                'level'          => $request->level,
                'weight'         => $request->weight,
                'status_progress' => $request->status_progress ?? 'todo',
                'status_akhir'   => $request->status_akhir,
                'tenggat_waktu'  => $request->tenggat_waktu,
                'tanggal_mulai'  => $request->tanggal_mulai,
            ]);

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $path = $file->store('tugas-foto', 'public');
                    TugasFoto::create([
                        'id_tugas'  => $task->id_tugas,
                        'nama_file' => $path,
                        'tipe'      => $request->tipe_foto ?? 'brief',
                    ]);
                }
            }

            DB::commit();

            $task->load(['tim.user', 'foto']);

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil ditambahkan.',
                'data'    => $this->formatTask($task),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan task: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update task
     */
    public function updateTask(Request $request, $id_projek, $id_tugas)
    {
        $task = Tugas::where('id_tugas', $id_tugas)
            ->where('id_projek', $id_projek)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'judul_tugas'    => 'sometimes|required|string|max:255',
            'deskripsi_tugas' => 'nullable|string',
            'id_tim'         => 'sometimes|exists:projek_tim,id_tim',
            'level'          => 'sometimes|in:1,2,3,4,5',
            'weight'         => 'sometimes|integer|min:1|max:100',
            'status_progress' => 'sometimes|in:todo,progress,review,done',
            'status_akhir'   => 'nullable|in:review,revisi,approved',
            'tenggat_waktu'  => 'nullable|date',
            'tanggal_mulai'  => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $task->update($request->only([
            'judul_tugas',
            'deskripsi_tugas',
            'id_tim',
            'level',
            'weight',
            'status_progress',
            'status_akhir',
            'tenggat_waktu',
            'tanggal_mulai',
        ]));

        $task->load(['tim.user', 'foto']);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diperbarui.',
            'data'    => $this->formatTask($task),
        ]);
    }

    /**
     * Update status akhir task (PM only)
     */
    public function updateStatusAkhir(Request $request, $id_projek, $id_tugas)
    {
        $request->validate([
            'status_akhir' => 'required|in:review,revisi,approved',
        ]);

        $task = Tugas::where('id_tugas', $id_tugas)
            ->where('id_projek', $id_projek)
            ->firstOrFail();

        $task->update(['status_akhir' => $request->status_akhir]);

        return response()->json([
            'success' => true,
            'message' => 'Status akhir task berhasil diperbarui.',
            'data'    => ['status_akhir' => $task->status_akhir],
        ]);
    }

    /**
     * Hapus task
     */
    public function destroyTask($id_projek, $id_tugas)
    {
        $task = Tugas::where('id_tugas', $id_tugas)
            ->where('id_projek', $id_projek)
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Hapus file foto dari storage
            foreach ($task->foto as $foto) {
                Storage::disk('public')->delete($foto->nama_file);
                $foto->delete();
            }

            $task->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus task: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ══════════════════════════════════════════════
    // FOTO TUGAS
    // ══════════════════════════════════════════════

    /**
     * Upload foto untuk task (bisa multiple)
     */
    public function uploadFoto(Request $request, $id_projek, $id_tugas)
    {
        $request->validate([
            'foto'     => 'required|array|min:1',
            'foto.*'   => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tipe'     => 'required|in:brief,hasil',
        ]);

        $task = Tugas::where('id_tugas', $id_tugas)
            ->where('id_projek', $id_projek)
            ->firstOrFail();

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
                'nama_file'     => $path,
                'tipe'          => $foto->tipe,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploaded) . ' foto berhasil diupload.',
            'data'    => $uploaded,
        ]);
    }

    /**
     * Hapus foto
     */
    public function destroyFoto($id_projek, $id_tugas, $id_foto)
    {
        $foto = TugasFoto::where('id_tugas_foto', $id_foto)
            ->where('id_tugas', $id_tugas)
            ->firstOrFail();

        Storage::disk('public')->delete($foto->nama_file);
        $foto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus.',
        ]);
    }

    // ══════════════════════════════════════════════
    // HELPER
    // ══════════════════════════════════════════════

    /**
     * Format task untuk response JSON
     */
    private function formatTask(Tugas $task): array
    {
        return [
            'id_tugas'       => $task->id_tugas,
            'judul_tugas'    => $task->judul_tugas,
            'deskripsi_tugas' => $task->deskripsi_tugas,
            'id_tim'         => $task->id_tim,
            'nama_assignee'  => optional($task->tim->user ?? null)->nama ?? '—',
            'avatar'         => strtoupper(substr(optional($task->tim->user ?? null)->nama ?? 'XX', 0, 2)),
            'level'          => $task->level,
            'weight'         => $task->weight,
            'status_progress' => $task->status_progress,
            'status_akhir'   => $task->status_akhir,
            'tenggat_waktu'  => $task->tenggat_waktu ? $task->tenggat_waktu->format('Y-m-d') : null,
            'tanggal_mulai'  => $task->tanggal_mulai ?? null,
            'dibuat_pada'    => $task->dibuat_pada?->format('Y-m-d H:i:s'),
            'foto'           => $task->foto->map(fn($f) => [
                'id_tugas_foto' => $f->id_tugas_foto,
                'url'           => Storage::url($f->nama_file),
                'nama_file'     => $f->nama_file,
                'tipe'          => $f->tipe,
            ])->toArray(),
        ];
    }
}
