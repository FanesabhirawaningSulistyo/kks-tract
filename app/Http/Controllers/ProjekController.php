<?php
namespace App\Http\Controllers;
use App\Models\Projek;
use App\Models\Perusahaan;
use App\Models\KategoriProjek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class ProjekController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $query = Projek::with([
            'perusahaan',
            'kategoriProjek',
            'pembuat',
            'tugas',
            'tugas.foto',
            'tim.user.jobRole',
        ]);

        // ─────────────────────────────────────────────────────────────
        // ROLE-BASED FILTERING
        // Inisialisasi $proyekIdList di sini agar tersedia untuk $statsQuery
        // ─────────────────────────────────────────────────────────────
        $proyekIdList = []; // default kosong

        if ($user->isAdmin()) {
            // Admin: tampilkan semua project, tidak perlu filter
        } elseif ($user->isPM()) {
            $query->where('dibuat_oleh', $user->id_user);
        } elseif ($user->isKaryawan()) {
            // Karyawan: hanya project yang dia tergabung di projek_tim
            $idUser = (int) $user->id_user;

            $proyekIdList = DB::table('projek_tim')
                ->where('id_user', $idUser)
                ->pluck('id_projek')
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values()
                ->toArray();

            if (empty($proyekIdList)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('projek.id_projek', $proyekIdList);
            }

        } elseif ($user->isKlien()) {
            $perusahaan = $user->perusahaan;
            if ($perusahaan) {
                $query->where('id_perusahaan', $perusahaan->id_perusahaan);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // ─────────────────────────────────────────────────────────────
        // FILTER
        // ─────────────────────────────────────────────────────────────
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_projek', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('perusahaan', function ($q2) use ($search) {
                        $q2->where('nama_perusahaan', 'like', "%{$search}%")
                            ->orWhere('nama_perwakilan', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('id_kategori_projek')) {
            $query->where('id_kategori_projek', $request->id_kategori_projek);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ─────────────────────────────────────────────────────────────
        // SORTING
        // ─────────────────────────────────────────────────────────────
        $sortBy    = $request->get('sort_by', 'dibuat_pada');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['nama_projek', 'status', 'tanggal_mulai', 'nominal_projek', 'dibuat_pada', 'dibuat_oleh'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'dibuat_pada';
        }

        if ($sortBy === 'kategori') {
            $query->leftJoin('kategori_projek', 'projek.id_kategori_projek', '=', 'kategori_projek.id_kategori_projek')
                ->orderBy('kategori_projek.nama_kategori', $sortOrder)
                ->select('projek.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        // ─────────────────────────────────────────────────────────────
        // EXPORT CSV
        // ─────────────────────────────────────────────────────────────
        if ($request->get('export') === '1' && !$user->isKaryawan()) {
            return $this->exportExcel($query->get());
        }

        // ─────────────────────────────────────────────────────────────
        // PAGINATION
        // ─────────────────────────────────────────────────────────────
        $perPage = in_array((int) $request->get('per_page'), [10, 25, 50, 100])
            ? (int) $request->get('per_page') : 10;

        $projeks   = $query->paginate($perPage)->withQueryString();
        $projekIds = $projeks->pluck('id_projek')->toArray();

        // ─────────────────────────────────────────────────────────────
        // PROGRESS
        // ─────────────────────────────────────────────────────────────
        $progressDb = DB::table('tugas')
            ->select(
                'id_projek',
                DB::raw('SUM(CASE WHEN status_progress != "draft" THEN weight ELSE 0 END) as total_weight'),
                DB::raw('SUM(CASE WHEN status_progress = "done" AND status_akhir = "approved" THEN weight ELSE 0 END) as approved_weight'),
                DB::raw('SUM(CASE WHEN status_progress != "draft" THEN 1 ELSE 0 END) as total_count'),
                DB::raw('SUM(CASE WHEN status_progress = "done" AND status_akhir = "approved" THEN 1 ELSE 0 END) as approved_count')
            )
            ->whereIn('id_projek', $projekIds)
            ->groupBy('id_projek')
            ->get()
            ->keyBy('id_projek');

        $kategoris   = KategoriProjek::orderBy('nama_kategori')->get();
        $perusahaans = Perusahaan::orderBy('nama_perwakilan')->get();

        // ─────────────────────────────────────────────────────────────
        // DAFTAR PM untuk dropdown Penanggung Jawab (hanya role PM)
        // ─────────────────────────────────────────────────────────────
        $pmList = User::where('role', 'PM')
            ->orderBy('nama')
            ->get(['id_user', 'nama', 'email']);

        // ─────────────────────────────────────────────────────────────
        // STATS — menggunakan scope yang sama dengan query utama
        // ─────────────────────────────────────────────────────────────
        $statsQuery = Projek::query();

        if ($user->isAdmin()) {
            // Admin: semua project, tidak perlu filter tambahan

        } elseif ($user->isPM()) {
            $statsQuery->where('dibuat_oleh', $user->id_user);
        } elseif ($user->isKaryawan()) {
            // Gunakan $proyekIdList yang sudah diinisialisasi di atas
            if (empty($proyekIdList)) {
                $statsQuery->whereRaw('1 = 0');
            } else {
                $statsQuery->whereIn('id_projek', $proyekIdList);
            }

        } elseif ($user->isKlien()) {
            $perusahaan = $user->perusahaan;
            if ($perusahaan) {
                $statsQuery->where('id_perusahaan', $perusahaan->id_perusahaan);
            } else {
                $statsQuery->whereRaw('1 = 0');
            }
        }

        $stats = [
            'total'       => (clone $statsQuery)->count(),
            'pending'     => (clone $statsQuery)->where('status', 'pending')->count(),
            'aktif'       => (clone $statsQuery)->where('status', 'aktif')->count(),
            'in_progress' => (clone $statsQuery)->where('status', 'in_progress')->count(),
            'selesai'     => (clone $statsQuery)->where('status', 'selesai')->count(),
        ];

        // ─────────────────────────────────────────────────────────────
        // projeksData untuk JS
        // ─────────────────────────────────────────────────────────────
        $projeksData = $projeks->map(function ($projek) use ($progressDb, $user) {
            $prog = $progressDb->get($projek->id_projek);
            $tw   = $prog ? (float) $prog->total_weight    : 0;
            $aw   = $prog ? (float) $prog->approved_weight : 0;
            $tc   = $prog ? (int)   $prog->total_count     : 0;
            $ac   = $prog ? (int)   $prog->approved_count  : 0;

            if ($tw > 0) {
                $pg = round(($aw / $tw) * 100, 2);
            } elseif ($tc > 0) {
                $pg = round(($ac / $tc) * 100, 2);
            } else {
                $pg = 0;
            }

            $pLabel = (optional($projek->perusahaan)->nama_perusahaan ?? '—') .
                (optional($projek->perusahaan)->nama_perwakilan
                    ? ' – ' . optional($projek->perusahaan)->nama_perwakilan
                    : '');

            $timList = [];
            try {
                foreach ($projek->tim ?? [] as $tim) {
                    $timList[] = [
                        'id_tim'   => (int) $tim->id_tim,
                        'nama'     => optional($tim->user)->nama ?? '—',
                        'jabatan'  => optional(optional($tim->user)->jobRole)->nama_job_role ?? null,
                        'job_role' => optional(optional($tim->user)->jobRole)->nama_job_role ?? null,
                    ];
                }
            } catch (\Exception $e) {}

            $tasks = $projek->tugas->map(function ($t) {
                return [
                    'id_tugas'        => $t->id_tugas,
                    'judul_tugas'     => $t->judul_tugas,
                    'deskripsi_tugas' => $t->deskripsi_tugas,
                    'id_tim'          => (int) $t->id_tim,
                    'status_progress' => $t->status_progress,
                    'status_akhir'    => $t->status_akhir,
                    'level'           => $t->level,
                    'weight'          => (float) $t->weight,
                    'tanggal_mulai'   => $t->tanggal_mulai,
                    'tenggat_waktu'   => $t->tenggat_waktu,
                    'tanggal_selesai' => $t->tanggal_selesai,
                    'foto'            => $t->foto
                        ? $t->foto->map(fn($f) => [
                            'id_tugas_foto' => $f->id_tugas_foto,
                            'tipe'          => $f->tipe,
                            'nama_file'     => $f->nama_file,
                            'url'           => Storage::url($f->path),
                        ])->values()->all()
                        : [],
                ];
            })->values()->all();

            $showNominal = $user->isAdmin() || $user->isKlien();

            return [
                'id_projek'          => $projek->id_projek,
                'nama_projek'        => $projek->nama_projek,
                'id_perusahaan'      => $projek->id_perusahaan,
                'perusahaan_label'   => $pLabel,
                'perusahaan_nama'    => optional($projek->perusahaan)->nama_perwakilan ?? '—',
                'perusahaan_pt'      => optional($projek->perusahaan)->nama_perusahaan ?? '',
                'id_kategori_projek' => $projek->id_kategori_projek,
                'kategori_nama'      => optional($projek->kategoriProjek)->nama_kategori ?? '—',
                'status'             => $projek->status,
                'nominal_projek'     => $showNominal ? $projek->nominal_projek  : null,
                'sisa_tanggungan'    => $showNominal ? $projek->sisa_tanggungan : null,
                'tanggal_mulai'      => $projek->tanggal_mulai,
                'tanggal_selesai'    => $projek->tanggal_selesai,
                'deskripsi'          => $projek->deskripsi,
                'dokumen_perjanjian' => $projek->dokumen_perjanjian,
                'pembuat_nama'       => optional($projek->pembuat)->nama ?? '—',
                'pembuat_email'      => optional($projek->pembuat)->email ?? '',
                'dibuat_oleh'        => $projek->dibuat_oleh,
                'progress'           => $pg,
                'approved_weight'    => $aw,
                'total_weight'       => $tw,
                'approved_count'     => $ac,
                'total_count'        => $tc,
                'tim_list'           => $timList,
                'tasks'              => $tasks,
            ];
        })->values()->all();

        return view('dashboard.master-data-projek', compact(
            'projeks',
            'stats',
            'kategoris',
            'perusahaans',
            'pmList',
            'projeksData'
        ));
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isPM()) {
            abort(403, 'Anda tidak memiliki akses untuk menambah project.');
        }

        $validated = $request->validate([
            'nama_projek'        => 'required|string|max:255',
            'id_perusahaan'      => 'required|exists:perusahaan,id_perusahaan',
            'id_kategori_projek' => 'nullable|exists:kategori_projek,id_kategori_projek',
            'status'             => 'required|in:pending,in_progress,aktif,selesai',
            'tanggal_mulai'      => 'nullable|date',
            'tanggal_selesai'    => 'nullable|date|after_or_equal:tanggal_mulai',
            'nominal_projek'     => $user->isAdmin() ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'dokumen_perjanjian' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
            'deskripsi'          => 'nullable|string',
            'dibuat_oleh'        => 'nullable|exists:users,id_user',
        ]);

        if ($user->isAdmin()) {
            $validated['dibuat_oleh'] = $request->dibuat_oleh ?: $user->id_user;
        } else {
            $validated['dibuat_oleh'] = $user->id_user;
        }

        if (!$user->isAdmin()) {
            $validated['nominal_projek'] = 0;
        }

        $validated['sisa_tanggungan'] = $validated['nominal_projek'];

        if ($request->hasFile('dokumen_perjanjian')) {
            $validated['dokumen_perjanjian'] = $request->file('dokumen_perjanjian')
                ->store('dokumen/projek', 'public');
        }

        Projek::create($validated);

        return redirect()->route('master-data-projek.index')
            ->with('success', 'Project berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, $id)
    {
        /** @var User $user */
        $user   = Auth::user();
        $projek = Projek::findOrFail($id);

        if ($user->isKaryawan() || $user->isKlien()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        if ($user->isPM() && (int)$projek->dibuat_oleh !== (int)$user->id_user) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }
        $request->validate([
            'status' => 'required|in:pending,in_progress,aktif,selesai',
        ]);

        $projek->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'status'  => $projek->status,
            'message' => 'Status berhasil diperbarui.',
        ]);
    }

    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user   = Auth::user();
        $projek = Projek::findOrFail($id);

        if (!$user->isAdmin() && !$user->isPM()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit project.');
        }

        if ($user->isPM() && (int)$projek->dibuat_oleh !== (int)$user->id_user) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit project ini.');
        }

        $validated = $request->validate([
            'nama_projek'        => 'required|string|max:255',
            'id_perusahaan'      => 'required|exists:perusahaan,id_perusahaan',
            'id_kategori_projek' => 'nullable|exists:kategori_projek,id_kategori_projek',
            'status'             => 'required|in:pending,in_progress,aktif,selesai',
            'tanggal_selesai'    => 'nullable|date',
            'nominal_projek'     => $user->isAdmin() ? 'required|numeric|min:0' : 'nullable|numeric|min:0',
            'dokumen_perjanjian' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
            'deskripsi'          => 'nullable|string',
            'dibuat_oleh'        => 'nullable|exists:users,id_user',
        ]);

        unset($validated['tanggal_mulai']);

        if (!$user->isAdmin()) {
            unset($validated['dibuat_oleh']);
        } else {
            $validated['dibuat_oleh'] = $request->dibuat_oleh ?: $projek->dibuat_oleh;
        }

        if (!$user->isAdmin()) {
            $validated['nominal_projek']  = $projek->nominal_projek;
            $validated['sisa_tanggungan'] = $projek->sisa_tanggungan;
        } else {
            $selisih = $validated['nominal_projek'] - $projek->nominal_projek;
            $validated['sisa_tanggungan'] = max(0, $projek->sisa_tanggungan + $selisih);
        }

        if ($request->hasFile('dokumen_perjanjian')) {
            if ($projek->dokumen_perjanjian) {
                Storage::disk('public')->delete($projek->dokumen_perjanjian);
            }
            $validated['dokumen_perjanjian'] = $request->file('dokumen_perjanjian')
                ->store('dokumen/projek', 'public');
        }

        $projek->update($validated);

        return redirect()->route('master-data-projek.index')
            ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy($id)
    {
        /** @var User $user */
        $user   = Auth::user();
        $projek = Projek::findOrFail($id);

        if (!$user->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus project.');
        }

        if ($projek->dokumen_perjanjian) {
            Storage::disk('public')->delete($projek->dokumen_perjanjian);
        }

        $projek->delete();

        return redirect()->route('master-data-projek.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    public function updateTanggal(Request $request, $id)
    {
        /** @var User $user */
        $user   = Auth::user();
        $projek = Projek::findOrFail($id);

        if ($user->isKaryawan() || $user->isKlien()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        if ($user->isPM() && (int)$projek->dibuat_oleh !== (int)$user->id_user) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }
        $validator = Validator::make($request->all(), [
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $projek->update([
            'tanggal_mulai'   => $request->tanggal_mulai   ?: null,
            'tanggal_selesai' => $request->tanggal_selesai ?: null,
        ]);

        return response()->json([
            'success'         => true,
            'message'         => 'Tanggal project berhasil diperbarui.',
            'tanggal_mulai'   => $projek->tanggal_mulai,
            'tanggal_selesai' => $projek->tanggal_selesai,
        ]);
    }

    public function laporan($id)
    {
        $projek = Projek::with(['perusahaan', 'kategoriProjek', 'pembuat', 'tugas'])
            ->findOrFail($id);

        return response()->json([
            'message'  => 'Laporan project: ' . $projek->nama_projek,
            'progress' => $projek->progress_projek . '%',
        ]);
    }

    private function exportExcel($projeks)
    {
        $filename = 'projek_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($projeks) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, [
                'No', 'Nama Project', 'Perusahaan', 'Kategori', 'Status',
                'Tanggal Mulai', 'Target Selesai', 'Nominal', 'Sisa Tanggungan',
                'Penanggung Jawab (PM)', 'Progress (%)',
            ]);
            foreach ($projeks as $i => $p) {
                $tanggalMulai   = $p->tanggal_mulai   ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y')   : '—';
                $tanggalSelesai = $p->tanggal_selesai ? \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') : '—';
                fputcsv($handle, [
                    $i + 1,
                    $p->nama_projek,
                    optional($p->perusahaan)->nama_perusahaan ?? '—',
                    optional($p->kategoriProjek)->nama_kategori ?? '—',
                    $p->status,
                    $tanggalMulai,
                    $tanggalSelesai,
                    $p->nominal_projek,
                    $p->sisa_tanggungan,
                    optional($p->pembuat)->nama ?? '—',
                    $p->progress_projek,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}