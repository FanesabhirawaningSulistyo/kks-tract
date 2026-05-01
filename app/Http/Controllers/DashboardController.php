<?php

namespace App\Http\Controllers;

use App\Models\KategoriProjek;
use App\Models\Perusahaan;
use App\Models\Projek;
use App\Models\ProjekTim;
use App\Models\Tugas;
use App\Models\User;
use App\Models\JobRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $role = Auth::user()->role;

        if ($role === 'PM') {
            return redirect()->route('dashboard.pm');
        }

        if ($role === 'karyawan') {
            return redirect()->route('dashboard.pegawai');
        }

        if ($role === 'klien') {
            return redirect()->route('dashboard.klien');
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 1. STAT CARDS
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $totalAdmin    = User::where('role', 'admin')->count();
        $totalPM       = User::where('role', 'PM')->count();
        $totalKaryawan = User::where('role', 'karyawan')->count();
        $totalUser     = $totalAdmin + $totalPM + $totalKaryawan;

        $totalKlienAktif    = User::where('role', 'klien')->where('status', true)->count();
        $totalKlienNonAktif = User::where('role', 'klien')->where('status', false)->count();
        $totalKlien         = $totalKlienAktif + $totalKlienNonAktif;

        $totalProjek           = Projek::count();
        $totalProjekPending    = Projek::where('status', 'pending')->count();
        $totalProjekAktif      = Projek::where('status', 'aktif')->count();
        $totalProjekSelesai    = Projek::where('status', 'selesai')->count();
        $totalProjekDikerjakan = Projek::where('status', 'in_progress')->count();

        $totalTaskTodo       = Tugas::where('status_progress', 'To Do')->count();
        $totalTaskInProgress = Tugas::where('status_progress', 'In Progress')->count();
        $totalTaskDone       = Tugas::where('status_progress', 'done')->count();
        $totalTask           = $totalTaskTodo + $totalTaskInProgress + $totalTaskDone;

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 2. PROGRESS PROJECT + STATUS TASK PER PROJECT
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $allProjeks = Projek::select('id_projek', 'nama_projek')
            ->orderBy('nama_projek')
            ->get();

        $progressData   = ['all' => $this->getProgressData(null)];
        $statusTaskData = ['all' => $this->getStatusTaskData(null)];

        foreach ($allProjeks as $p) {
            $progressData[$p->id_projek]   = $this->getProgressData($p->id_projek);
            $statusTaskData[$p->id_projek] = $this->getStatusTaskData($p->id_projek);
        }

        $progressData['all']['nama'] = 'Semua Project';
        foreach ($allProjeks as $p) {
            $progressData[$p->id_projek]['nama'] = $p->nama_projek;
        }

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 3. TOP 5 KARYAWAN TERBAIK
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $top5Karyawan = $this->getTop5Karyawan();

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 4. TREN PEROLEHAN PROJECT
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $trendProject = $this->buildTrendProject();

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 5. AKTIVITAS PENYELESAIAN TASK
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $activityWeek  = $this->buildActivityChart('week');
        $activityMonth = $this->buildActivityChart('month');
        $activityYear  = $this->buildActivityChart('year');

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 6. KATEGORI PROJECT
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $kategoriProject     = KategoriProjek::withCount('projek')
            ->orderByDesc('projek_count')
            ->get();
        $totalKategoriProjek = $kategoriProject->sum('projek_count');

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 7. DISTRIBUSI ROLE KARYAWAN
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $distribusiRole = JobRole::withCount([
            'users as jumlah' => fn($q) => $q->where('role', 'karyawan')->where('status', true),
        ])
            ->where('status', true)
            ->having('jumlah', '>', 0)
            ->orderByDesc('jumlah')
            ->get();

        $totalKaryawanAktif = User::where('role', 'karyawan')->where('status', true)->count();

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 8. HEALTH CHART (Kesehatan berdasarkan deadline) untuk SEMUA PROJECT
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        // Ambil semua ID project (untuk filter "Semua Project")
        $allProjekIds = $allProjeks->pluck('id_projek')->toArray();

        // Build health chart untuk semua project
        $healthWeek  = $this->buildKlienHealthChart('week',  $allProjekIds);
        $healthMonth = $this->buildKlienHealthChart('month', $allProjekIds);
        $healthYear  = $this->buildKlienHealthChart('year',  $allProjekIds);

        // Data health per project (untuk filter dropdown)
        $healthDataPerProject = ['all' => [
            'week'  => $healthWeek,
            'month' => $healthMonth,
            'year'  => $healthYear,
        ]];

        foreach ($allProjeks as $p) {
            $singleId = [$p->id_projek];
            $healthDataPerProject[$p->id_projek] = [
                'week'  => $this->buildKlienHealthChart('week',  $singleId),
                'month' => $this->buildKlienHealthChart('month', $singleId),
                'year'  => $this->buildKlienHealthChart('year',  $singleId),
            ];
        }

        return view('dashboard.index', compact(
            'totalAdmin',
            'totalPM',
            'totalKaryawan',
            'totalUser',
            'totalKlienAktif',
            'totalKlienNonAktif',
            'totalKlien',
            'totalProjek',
            'totalProjekPending',
            'totalProjekAktif',
            'totalProjekSelesai',
            'totalProjekDikerjakan',
            'totalTaskTodo',
            'totalTaskInProgress',
            'totalTaskDone',
            'totalTask',
            'allProjeks',
            'progressData',
            'statusTaskData',
            'top5Karyawan',
            'trendProject',
            'activityWeek',
            'activityMonth',
            'activityYear',
            'kategoriProject',
            'totalKategoriProjek',
            'distribusiRole',
            'totalKaryawanAktif',
            'healthWeek',
            'healthMonth',
            'healthYear',
            'healthDataPerProject'
        ));
    }
    /* ══════════════════════════════════════════════════════════
     | PRIVATE — Progress Data per Project
     | Menghitung persen task done + approved vs total task
    ══════════════════════════════════════════════════════════ */
    private function getProgressData(?int $idProjek): array
    {
        $q = Tugas::where('status_progress', '!=', 'draft');
        if ($idProjek) {
            $q->where('id_projek', $idProjek);
        }

        $total   = (clone $q)->count();
        $selesai = (clone $q)
            ->where('status_progress', 'done')
            ->where('status_akhir', 'approved')
            ->count();
        $persen  = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;

        return [
            'total'   => $total,
            'selesai' => $selesai,
            'persen'  => $persen,
        ];
    }

    /* ══════════════════════════════════════════════════════════
     | PRIVATE — Status Task Data per Project
     | Ringkasan Status Task + Final Status Task
    ══════════════════════════════════════════════════════════ */
    private function getStatusTaskData(?int $idProjek): array
    {
        $q = Tugas::query();
        if ($idProjek) {
            $q->where('id_projek', $idProjek);
        }

        // Ringkasan Status Task (status_progress)
        $todo       = (clone $q)->where('status_progress', 'To Do')->count();
        $inprogress = (clone $q)->where('status_progress', 'In Progress')->count();
        $done       = (clone $q)->where('status_progress', 'done')->count();
        $total      = $todo + $inprogress + $done;

        // Final Status Task (status_akhir)
        $preview    = (clone $q)->where('status_akhir', 'review')->count();
        $revisi     = (clone $q)->where('status_akhir', 'revisi')->count();
        $approved   = (clone $q)->where('status_akhir', 'approved')->count();
        $totalFinal = $preview + $revisi + $approved;

        $pct = fn(int $n, int $d): float => $d > 0 ? round(($n / $d) * 100, 2) : 0;

        return [
            // Ringkasan Status Task
            'todo'           => $todo,
            'todo_pct'       => $pct($todo, $total),
            'inprogress'     => $inprogress,
            'inprogress_pct' => $pct($inprogress, $total),
            'done'           => $done,
            'done_pct'       => $pct($done, $total),
            'total'          => $total,
            // Final Status Task
            'preview'        => $preview,
            'preview_pct'    => $pct($preview, $totalFinal),
            'revisi'         => $revisi,
            'revisi_pct'     => $pct($revisi, $totalFinal),
            'approved'       => $approved,
            'approved_pct'   => $pct($approved, $totalFinal),
            'total_final'    => $totalFinal,
        ];
    }

    /* ══════════════════════════════════════════════════════════
     | PRIVATE — Top 5 Karyawan
     | Rumus poin identik dengan PerformaKaryawanController
    ══════════════════════════════════════════════════════════ */
    private function getTop5Karyawan(): \Illuminate\Support\Collection
    {
        $karyawans = User::with('jobRole')
            ->where('role', 'karyawan')
            ->where('status', true)
            ->get();

        return $karyawans->map(function (User $k) {
            $timEntries    = ProjekTim::where('id_user', $k->id_user)->get();
            $timIds        = $timEntries->pluck('id_tim')->toArray();
            $jumlahProject = $timEntries->pluck('id_projek')->unique()->count();

            // Semua task non-draft yang di-assign ke tim user ini
            $allTasks = Tugas::whereIn('id_tim', $timIds)
                ->where('status_progress', '!=', 'draft')
                ->get();
            $jumlahTask = $allTasks->count();

            $sebelumDeadline = 0;
            $tepatWaktu      = 0;
            $terlambat       = 0;

            foreach ($allTasks as $task) {
                if ($task->status_progress !== 'done') {
                    continue;
                }
                if (! $task->tanggal_selesai || ! $task->tenggat_waktu) {
                    continue;
                }
                // positif  → selesai sebelum deadline
                // 0        → tepat waktu
                // negatif  → terlambat
                $diff = Carbon::parse($task->tanggal_selesai)
                    ->diffInDays(Carbon::parse($task->tenggat_waktu), false);

                if ($diff > 0) {
                    $sebelumDeadline++;
                } elseif ($diff === 0) {
                    $tepatWaktu++;
                } else {
                    $terlambat++;
                }
            }

            // Poin sama persis dengan PerformaKaryawanController
            $poin = ($jumlahProject  * 5)
                + ($jumlahTask       * 2)
                + ($sebelumDeadline  * 3)
                + ($tepatWaktu       * 2)
                + ($terlambat        * -2);

            $totalDenganDeadline = $tepatWaktu + $sebelumDeadline + $terlambat;

            return [
                'nama'             => $k->nama,
                'jabatan'          => optional($k->jobRole)->nama_job_role ?? 'Karyawan',
                'tepat_waktu'      => $tepatWaktu,
                'sebelum_deadline' => $sebelumDeadline,
                'terlambat'        => $terlambat,
                'total_task'       => $totalDenganDeadline,
                'poin'             => $poin,
            ];
        })
            ->sortByDesc('poin')
            ->take(5)
            ->values();
    }

    /* ══════════════════════════════════════════════════════════
     | PRIVATE — Tren Project 12 Bulan
     | Dihitung dari dibuat_pada Projek, sertakan nama bulan+tahun
    ══════════════════════════════════════════════════════════ */
    private function buildTrendProject(): array
    {
        $monthNames = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agt',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        $start = Carbon::now()->subMonths(11)->startOfMonth();

        $raw = Projek::select(
            DB::raw('DATE_FORMAT(dibuat_pada, "%Y-%m") AS ym'),
            DB::raw('COUNT(*) AS cnt')
        )
            ->where('dibuat_pada', '>=', $start)
            ->groupBy('ym')
            ->pluck('cnt', 'ym');

        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $m        = Carbon::now()->subMonths($i);
            $ym       = $m->format('Y-m');
            $result[] = [
                'label' => $monthNames[$m->month - 1] . ' ' . $m->year,
                'total' => (int) ($raw[$ym] ?? 0),
            ];
        }
        return $result;
    }

    /* ══════════════════════════════════════════════════════════
     | PRIVATE — Aktivitas Penyelesaian Task
     | Hanya task done + approved, lalu dibandingkan dengan tenggat
    ══════════════════════════════════════════════════════════ */
    private function buildActivityChart(string $period): array
    {
        return match ($period) {
            'month' => $this->activityMonthly(),
            'year'  => $this->activityYearly(),
            default => $this->activityWeekly(),
        };
    }

    /** Per hari — 7 hari terakhir */
    private function activityWeekly(): array
    {
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $raw = $this->rawActivityByDate(
            Carbon::now()->subDays(6)->format('Y-m-d'),
            Carbon::now()->format('Y-m-d')
        )->keyBy('tgl');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $d        = Carbon::now()->subDays($i);
            $key      = $d->format('Y-m-d');
            $row      = $raw->get($key);
            $result[] = [
                'label'      => $dayNames[$d->dayOfWeek] . ' ' . $d->format('d/m'),
                'tepat'      => (int) ($row->tepat      ?? 0),
                'terlambat'  => (int) ($row->terlambat  ?? 0),
                'lebih_awal' => (int) ($row->lebih_awal ?? 0),
            ];
        }
        return $result;
    }

    /** Per bulan — 12 bulan terakhir */
    private function activityMonthly(): array
    {
        $monthNames = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agt',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        $start = Carbon::now()->subMonths(11)->startOfMonth();

        $raw = Tugas::select(
            DB::raw('DATE_FORMAT(tanggal_selesai, "%Y-%m") AS ym'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS lebih_awal'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS tepat'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS terlambat')
        )
            ->where('status_progress', 'done')
            ->where('status_akhir', 'approved')
            ->whereNotNull('tanggal_selesai')
            ->whereNotNull('tenggat_waktu')
            ->where('tanggal_selesai', '>=', $start)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $m        = Carbon::now()->subMonths($i);
            $ym       = $m->format('Y-m');
            $row      = $raw->get($ym);
            $result[] = [
                'label'      => $monthNames[$m->month - 1] . ' ' . $m->year,
                'tepat'      => (int) ($row->tepat      ?? 0),
                'terlambat'  => (int) ($row->terlambat  ?? 0),
                'lebih_awal' => (int) ($row->lebih_awal ?? 0),
            ];
        }
        return $result;
    }

    /** Per tahun — 5 tahun terakhir */
    private function activityYearly(): array
    {
        $raw = Tugas::select(
            DB::raw('YEAR(tanggal_selesai) AS yr'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS lebih_awal'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS tepat'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS terlambat')
        )
            ->where('status_progress', 'done')
            ->where('status_akhir', 'approved')
            ->whereNotNull('tanggal_selesai')
            ->whereNotNull('tenggat_waktu')
            ->groupBy('yr')
            ->orderBy('yr')
            ->get()
            ->keyBy('yr');

        $currentYear = (int) now()->format('Y');
        $result      = [];
        for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
            $row      = $raw->get($yr);
            $result[] = [
                'label'      => (string) $yr,
                'tepat'      => (int) ($row->tepat      ?? 0),
                'terlambat'  => (int) ($row->terlambat  ?? 0),
                'lebih_awal' => (int) ($row->lebih_awal ?? 0),
            ];
        }
        return $result;
    }

    /** Query mentah per tanggal (untuk weekly) */
    private function rawActivityByDate(string $from, string $to)
    {
        return Tugas::select(
            DB::raw('DATE(tanggal_selesai) AS tgl'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS lebih_awal'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS tepat'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS terlambat')
        )
            ->where('status_progress', 'done')
            ->where('status_akhir', 'approved')
            ->whereNotNull('tanggal_selesai')
            ->whereNotNull('tenggat_waktu')
            ->whereBetween(DB::raw('DATE(tanggal_selesai)'), [$from, $to])
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get();
    }

    /* ── Halaman lain ── */

    public function index2(): \Illuminate\View\View
    {
        /** @var \App\Models\User $pm */
        $pm = Auth::user();

        // Ambil semua project yang dikelola PM yang sedang login
        $myProjeks    = Projek::where('dibuat_oleh', $pm->id_user)
            ->orderBy('nama_projek')
            ->get();
        $myProjekIds  = $myProjeks->pluck('id_projek')->toArray();

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
         | 1. STAT CARDS  (berdasarkan project PM)
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        // Total Project
        $pmTotalProjek           = count($myProjekIds);
        $pmTotalProjekPending    = $myProjeks->where('status', 'pending')->count();
        $pmTotalProjekAktif      = $myProjeks->where('status', 'aktif')->count();
        $pmTotalProjekSelesai    = $myProjeks->where('status', 'selesai')->count();
        $pmTotalProjekDikerjakan = $myProjeks->where('status', 'in_progress')->count();

        // Approval Task — hanya task yang punya status_akhir (review / revisi / approved)
        $pmApprovalApproved = Tugas::whereIn('id_projek', $myProjekIds)
            ->where('status_akhir', 'approved')->count();

        $pmApprovalReview   = Tugas::whereIn('id_projek', $myProjekIds)
            ->where('status_progress', 'done')
            ->where('status_akhir', 'review')->count();

        $pmApprovalRevisi   = Tugas::whereIn('id_projek', $myProjekIds)
            ->where('status_akhir', 'revisi')->count();

        $pmApprovalTotal = $pmApprovalApproved + $pmApprovalReview + $pmApprovalRevisi;

        // Total Task
        $pmTotalTask           = Tugas::whereIn('id_projek', $myProjekIds)
            ->where('status_progress', '!=', 'draft')->count();
        $pmTotalTaskTodo       = Tugas::whereIn('id_projek', $myProjekIds)
            ->where('status_progress', 'To Do')->count();
        $pmTotalTaskInProgress = Tugas::whereIn('id_projek', $myProjekIds)
            ->where('status_progress', 'In Progress')->count();
        $pmTotalTaskDone       = Tugas::whereIn('id_projek', $myProjekIds)
            ->where('status_progress', 'done')->count();

        

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
         | 2. PROGRESS PROJECT + STATUS TASK PER PROJECT
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $pmProgressData   = ['all' => $this->getPmProgressData(null, $myProjekIds)];
        $pmStatusTaskData = ['all' => $this->getPmStatusTaskData(null, $myProjekIds)];

        foreach ($myProjeks as $p) {
            $pmProgressData[$p->id_projek]   = $this->getPmProgressData($p->id_projek, $myProjekIds);
            $pmStatusTaskData[$p->id_projek] = $this->getPmStatusTaskData($p->id_projek, $myProjekIds);
        }

        $pmProgressData['all']['nama'] = 'Semua Project Saya';
        foreach ($myProjeks as $p) {
            $pmProgressData[$p->id_projek]['nama'] = $p->nama_projek;
        }
        $pmHealthWeek  = $this->buildPmHealthChart('week',  $myProjekIds);
        $pmHealthMonth = $this->buildPmHealthChart('month', $myProjekIds);
        $pmHealthYear  = $this->buildPmHealthChart('year',  $myProjekIds);
        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
         | 3. AKTIVITAS PENYELESAIAN TASK  (project PM saja)
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $pmActivityWeek  = $this->buildPmActivityChart('week',  $myProjekIds);
        $pmActivityMonth = $this->buildPmActivityChart('month', $myProjekIds);
        $pmActivityYear  = $this->buildPmActivityChart('year',  $myProjekIds);

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
         | 4. TOP 5 KARYAWAN TERBAIK  (dari project PM saja)
        ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $pmTop5Karyawan = $this->getPmTop5Karyawan($myProjekIds);

        $pmRiskProjects = $this->getRiskProjectsForPM($myProjeks, $pmProgressData, $pmStatusTaskData);

        /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
 | HEALTH CHART PER PROJECT (untuk dropdown)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
        $pmHealthWeek  = $this->buildPmHealthChart('week',  $myProjekIds);
        $pmHealthMonth = $this->buildPmHealthChart('month', $myProjekIds);
        $pmHealthYear  = $this->buildPmHealthChart('year',  $myProjekIds);

        // Data health per project (untuk filter dropdown)
        $pmHealthDataPerProject = [
            'all' => [
                'week'  => $pmHealthWeek,
                'month' => $pmHealthMonth,
                'year'  => $pmHealthYear,
            ]
        ];

        foreach ($myProjeks as $p) {
            $singleId = [$p->id_projek];
            $pmHealthDataPerProject[$p->id_projek] = [
                'week'  => $this->buildPmHealthChart('week', $singleId),
                'month' => $this->buildPmHealthChart('month', $singleId),
                'year'  => $this->buildPmHealthChart('year', $singleId),
            ];
        }

        return view('dashboard.index2', compact(
            'myProjeks',
            'pmTotalProjek',
            'pmTotalProjekPending',
            'pmTotalProjekAktif',
            'pmTotalProjekSelesai',
            'pmTotalProjekDikerjakan',
            'pmApprovalTotal',
            'pmApprovalApproved',
            'pmApprovalReview',
            'pmApprovalRevisi',
            'pmTotalTask',
            'pmTotalTaskTodo',
            'pmTotalTaskInProgress',
            'pmTotalTaskDone',
            'pmHealthWeek',
            'pmHealthMonth',
            'pmHealthYear',
            'pmHealthDataPerProject',  // <-- TAMBAHKAN INI PENTING
            'pmProgressData',
            'pmStatusTaskData',
            'pmActivityWeek',
            'pmActivityMonth',
            'pmActivityYear',
            'pmTop5Karyawan'
        ));
    }

    /* ══════════════════════════════════════════════════════════
 | PRIVATE — Health Chart Kesehatan Task (tepat + sebelum deadline)
 | Untuk dashboard PM
══════════════════════════════════════════════════════════ */
    private function buildPmHealthChart(string $period, array $projekIds): array
    {
        if (empty($projekIds)) {
            return $this->emptyPmHealthData($period);
        }

        return match ($period) {
            'month' => $this->pmHealthMonthly($projekIds),
            'year'  => $this->pmHealthYearly($projekIds),
            default => $this->pmHealthWeekly($projekIds),
        };
    }

    private function pmHealthWeekly(array $projekIds): array
    {
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        $raw = Tugas::select(
            DB::raw('DATE(tenggat_waktu) AS tgl'),
            DB::raw('SUM(1) AS total'),
            DB::raw('SUM(CASE WHEN status_progress = "done" AND DATE(tanggal_selesai) <= DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS on_time')
        )
            ->whereIn('id_projek', $projekIds)
            ->whereNotNull('tenggat_waktu')
            ->whereBetween(DB::raw('DATE(tenggat_waktu)'), [
                Carbon::now()->subDays(6)->format('Y-m-d'),
                Carbon::now()->format('Y-m-d'),
            ])
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get()
            ->keyBy('tgl');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $d   = Carbon::now()->subDays($i);
            $key = $d->format('Y-m-d');
            $row = $raw->get($key);

            $total   = $row ? (int) $row->total   : 0;
            $onTime  = $row ? (int) $row->on_time : 0;
            $pct     = $total > 0 ? round(($onTime / $total) * 100, 1) : null;

            $result[] = [
                'label'   => $dayNames[$d->dayOfWeek] . ' ' . $d->format('d/m'),
                'total'   => $total,
                'on_time' => $onTime,
                'pct'     => $pct,
            ];
        }
        return $result;
    }

    private function pmHealthMonthly(array $projekIds): array
    {
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $start      = Carbon::now()->subMonths(11)->startOfMonth();

        $raw = Tugas::select(
            DB::raw('DATE_FORMAT(tenggat_waktu, "%Y-%m") AS ym'),
            DB::raw('SUM(1) AS total'),
            DB::raw('SUM(CASE WHEN status_progress = "done" AND DATE(tanggal_selesai) <= DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS on_time')
        )
            ->whereIn('id_projek', $projekIds)
            ->whereNotNull('tenggat_waktu')
            ->where('tenggat_waktu', '>=', $start)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $m   = Carbon::now()->subMonths($i);
            $ym  = $m->format('Y-m');
            $row = $raw->get($ym);

            $total  = $row ? (int) $row->total   : 0;
            $onTime = $row ? (int) $row->on_time : 0;
            $pct    = $total > 0 ? round(($onTime / $total) * 100, 1) : null;

            $result[] = [
                'label'   => $monthNames[$m->month - 1] . ' ' . $m->year,
                'total'   => $total,
                'on_time' => $onTime,
                'pct'     => $pct,
            ];
        }
        return $result;
    }

    private function pmHealthYearly(array $projekIds): array
    {
        $raw = Tugas::select(
            DB::raw('YEAR(tenggat_waktu) AS yr'),
            DB::raw('SUM(1) AS total'),
            DB::raw('SUM(CASE WHEN status_progress = "done" AND DATE(tanggal_selesai) <= DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS on_time')
        )
            ->whereIn('id_projek', $projekIds)
            ->whereNotNull('tenggat_waktu')
            ->groupBy('yr')
            ->orderBy('yr')
            ->get()
            ->keyBy('yr');

        $currentYear = (int) now()->format('Y');
        $result      = [];

        for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
            $row    = $raw->get($yr);
            $total  = $row ? (int) $row->total   : 0;
            $onTime = $row ? (int) $row->on_time : 0;
            $pct    = $total > 0 ? round(($onTime / $total) * 100, 1) : null;

            $result[] = [
                'label'   => (string) $yr,
                'total'   => $total,
                'on_time' => $onTime,
                'pct'     => $pct,
            ];
        }
        return $result;
    }

    private function emptyPmHealthData(string $period): array
    {
        if ($period === 'year') {
            $currentYear = (int) now()->format('Y');
            $result = [];
            for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
                $result[] = ['label' => (string) $yr, 'total' => 0, 'on_time' => 0, 'pct' => null];
            }
            return $result;
        }

        if ($period === 'month') {
            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
            $result = [];
            for ($i = 11; $i >= 0; $i--) {
                $m = Carbon::now()->subMonths($i);
                $result[] = [
                    'label' => $monthNames[$m->month - 1] . ' ' . $m->year,
                    'total' => 0,
                    'on_time' => 0,
                    'pct' => null,
                ];
            }
            return $result;
        }

        // week
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i);
            $result[] = [
                'label' => $dayNames[$d->dayOfWeek] . ' ' . $d->format('d/m'),
                'total' => 0,
                'on_time' => 0,
                'pct' => null,
            ];
        }
        return $result;
    }

    /**
     * Get risk projects data for PM dashboard
     * Menampilkan project dengan tanggal_selesai dalam 7 hari ke depan 
     * yang progress-nya masih rendah atau task belum selesai
     */
    private function getRiskProjectsForPM($myProjeks, $pmProgressData, $pmStatusTaskData): array
    {
        $riskProjects = [];
        $today = Carbon::now();
        $sevenDaysLater = Carbon::now()->addDays(7);

        foreach ($myProjeks as $project) {
            // Skip project yang sudah selesai
            if ($project->status === 'selesai') continue;

            // Cek apakah project memiliki tanggal_selesai
            if (!$project->tanggal_selesai) continue;

            $tanggalSelesai = Carbon::parse($project->tanggal_selesai);

            // Hanya project yang deadline-nya dalam 7 hari ke depan
            if (!$tanggalSelesai->between($today, $sevenDaysLater)) continue;

            $daysLeft = $today->diffInDays($tanggalSelesai, false);

            // Ambil data progress
            $progData = $pmProgressData[$project->id_projek] ?? ['total' => 0, 'selesai' => 0, 'persen' => 0];
            $statusData = $pmStatusTaskData[$project->id_projek] ?? [
                'total_final' => 0,
                'preview' => 0,
                'revisi' => 0,
                'inprogress' => 0,
                'todo' => 0
            ];

            // Hitung task yang belum selesai (To Do + In Progress)
            $taskBelumSelesai = ($statusData['todo'] ?? 0) + ($statusData['inprogress'] ?? 0);
            $progressPersen = $progData['persen'] ?? 0;

            // Hitung task terlambat (melewati deadline individual task)
            $taskTerlambat = Tugas::where('id_projek', $project->id_projek)
                ->where('status_progress', '!=', 'done')
                ->where('status_progress', '!=', 'draft')
                ->where('tenggat_waktu', '<', $today)
                ->count();

            // Tentukan level risiko berdasarkan:
            // 1. Hari tersisa sampai deadline project
            // 2. Progress project
            // 3. Jumlah task belum selesai

            $riskScore = 0;

            // Faktor deadline project
            if ($daysLeft <= 0) {
                $riskScore += 50; // Sudah melewati deadline
            } elseif ($daysLeft <= 3) {
                $riskScore += 40; // H-3 (kritis)
            } elseif ($daysLeft <= 7) {
                $riskScore += 20; // H-7
            }

            // Faktor progress rendah
            if ($progressPersen < 30) {
                $riskScore += 35;
            } elseif ($progressPersen < 60) {
                $riskScore += 20;
            } elseif ($progressPersen < 80) {
                $riskScore += 10;
            }

            // Faktor task belum selesai
            if ($taskBelumSelesai > 10) {
                $riskScore += 25;
            } elseif ($taskBelumSelesai > 5) {
                $riskScore += 15;
            } elseif ($taskBelumSelesai > 0) {
                $riskScore += 5;
            }

            // Faktor task terlambat
            if ($taskTerlambat > 5) {
                $riskScore += 30;
            } elseif ($taskTerlambat > 0) {
                $riskScore += 15;
            }

            // Tentukan level risiko
            if ($riskScore >= 70) {
                $riskLevel = 'high';
                $riskColor = '#e74c3c';
                $riskIcon = '🔴';
                $riskLabel = 'Risiko Tinggi';
            } elseif ($riskScore >= 40) {
                $riskLevel = 'medium';
                $riskColor = '#f5a623';
                $riskIcon = '🟡';
                $riskLabel = 'Risiko Sedang';
            } else {
                $riskLevel = 'low';
                $riskColor = '#ffd93d';
                $riskIcon = '🟢';
                $riskLabel = 'Risiko Rendah';
                // Skip low risk, tampilkan hanya high & medium
                continue;
            }

            // Ambil beberapa task terdekat deadline-nya untuk ditampilkan
            $nearestTasks = Tugas::where('id_projek', $project->id_projek)
                ->where('status_progress', '!=', 'done')
                ->where('status_progress', '!=', 'draft')
                ->whereNotNull('tenggat_waktu')
                ->orderBy('tenggat_waktu', 'asc')
                ->limit(5)
                ->get()
                ->map(function ($task) use ($today) {
                    $deadline = Carbon::parse($task->tenggat_waktu);
                    return [
                        'id' => $task->id_tugas,
                        'judul' => mb_substr($task->judul_tugas, 40),
                        'deadline' => $deadline->format('d/m/Y'),
                        'days_left' => $today->diffInDays($deadline, false),
                        'status' => $task->status_progress,
                    ];
                });

            $riskProjects[] = [
                'id' => $project->id_projek,
                'nama' => $project->nama_projek,
                'status' => $project->status,
                'tanggal_selesai' => $tanggalSelesai->format('d/m/Y'),
                'days_left' => $daysLeft,
                'progress' => $progressPersen,
                'progress_total' => $progData['total'] ?? 0,
                'progress_selesai' => $progData['selesai'] ?? 0,
                'task_belum_selesai' => $taskBelumSelesai,
                'task_todo' => $statusData['todo'] ?? 0,
                'task_inprogress' => $statusData['inprogress'] ?? 0,
                'task_terlambat' => $taskTerlambat,
                'task_review' => $statusData['preview'] ?? 0,
                'task_revisi' => $statusData['revisi'] ?? 0,
                'nearest_tasks' => $nearestTasks,
                'risk_level' => $riskLevel,
                'risk_color' => $riskColor,
                'risk_icon' => $riskIcon,
                'risk_label' => $riskLabel,
                'risk_score' => $riskScore,
            ];
        }

        // Urutkan berdasarkan days_left (paling dekat dulu) dan risk_score
        usort($riskProjects, function ($a, $b) {
            if ($a['days_left'] != $b['days_left']) {
                return $a['days_left'] <=> $b['days_left'];
            }
            return $b['risk_score'] <=> $a['risk_score'];
        });

        return $riskProjects;
    }
    /* ══════════════════════════════════════════════════════════
     | Progress Data PM  (filtered by project PM)
    ══════════════════════════════════════════════════════════ */
    private function getPmProgressData(?int $idProjek, array $projekIds): array
    {
        $q = Tugas::whereIn('id_projek', $projekIds)
            ->where('status_progress', '!=', 'draft');

        if ($idProjek) {
            $q->where('id_projek', $idProjek);
        }

        $total   = (clone $q)->count();
        $selesai = (clone $q)
            ->where('status_progress', 'done')
            ->where('status_akhir', 'approved')
            ->count();
        $persen  = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;

        return ['total' => $total, 'selesai' => $selesai, 'persen' => $persen];
    }

    /* ══════════════════════════════════════════════════════════
     | Status Task Data PM
    ══════════════════════════════════════════════════════════ */
    private function getPmStatusTaskData(?int $idProjek, array $projekIds): array
    {
        $q = Tugas::whereIn('id_projek', $projekIds);

        if ($idProjek) {
            $q->where('id_projek', $idProjek);
        }

        $todo       = (clone $q)->where('status_progress', 'To Do')->count();
        $inprogress = (clone $q)->where('status_progress', 'In Progress')->count();
        $done       = (clone $q)->where('status_progress', 'done')->count();
        $total      = $todo + $inprogress + $done;

        $preview    = (clone $q)->where('status_akhir', 'review')->count();
        $revisi     = (clone $q)->where('status_akhir', 'revisi')->count();
        $approved   = (clone $q)->where('status_akhir', 'approved')->count();
        $totalFinal = $preview + $revisi + $approved;

        $pct = fn(int $n, int $d): float => $d > 0 ? round(($n / $d) * 100, 2) : 0;

        return [
            'todo'           => $todo,
            'todo_pct'       => $pct($todo, $total),
            'inprogress'     => $inprogress,
            'inprogress_pct' => $pct($inprogress, $total),
            'done'           => $done,
            'done_pct'       => $pct($done, $total),
            'total'          => $total,
            'preview'        => $preview,
            'preview_pct'    => $pct($preview, $totalFinal),
            'revisi'         => $revisi,
            'revisi_pct'     => $pct($revisi, $totalFinal),
            'approved'       => $approved,
            'approved_pct'   => $pct($approved, $totalFinal),
            'total_final'    => $totalFinal,
        ];
    }

    /* ══════════════════════════════════════════════════════════
     | Top 5 Karyawan PM  (hanya karyawan dari project PM)
    ══════════════════════════════════════════════════════════ */
    private function getPmTop5Karyawan(array $projekIds): \Illuminate\Support\Collection
    {
        // Ambil semua tim dari project PM
        $timEntries = ProjekTim::whereIn('id_projek', $projekIds)->get();
        $timIds     = $timEntries->pluck('id_tim')->unique()->toArray();

        // Ambil user yang terlibat (role karyawan)
        $userIds = ProjekTim::whereIn('id_tim', $timIds)
            ->pluck('id_user')
            ->unique()
            ->toArray();

        $karyawans = User::with('jobRole')
            ->whereIn('id_user', $userIds)
            ->where('role', 'karyawan')
            ->get();

        return $karyawans->map(function (User $k) {
            // ← SAMA PERSIS dengan getTop5Karyawan() admin:
            // ambil SEMUA project karyawan, bukan hanya project PM
            $timEntries    = ProjekTim::where('id_user', $k->id_user)->get();
            $timIds        = $timEntries->pluck('id_tim')->toArray();
            $jumlahProject = $timEntries->pluck('id_projek')->unique()->count();

            // Semua task non-draft dari SEMUA project karyawan
            $allTasks = Tugas::whereIn('id_tim', $timIds)
                ->where('status_progress', '!=', 'draft')
                ->get();

            $jumlahTask      = $allTasks->count();
            $sebelumDeadline = 0;
            $tepatWaktu      = 0;
            $terlambat       = 0;

            foreach ($allTasks as $task) {
                if ($task->status_progress !== 'done') continue;
                if (!$task->tanggal_selesai || !$task->tenggat_waktu) continue;

                $diff = Carbon::parse($task->tanggal_selesai)
                    ->diffInDays(Carbon::parse($task->tenggat_waktu), false);

                if ($diff > 0)       $sebelumDeadline++;
                elseif ($diff === 0) $tepatWaktu++;
                else                 $terlambat++;
            }

            $poin = ($jumlahProject  * 5)
                + ($jumlahTask     * 2)
                + ($sebelumDeadline * 3)
                + ($tepatWaktu     * 2)
                + ($terlambat      * -2);

            return [
                'nama'             => $k->nama,
                'jabatan'          => optional($k->jobRole)->nama_job_role ?? 'Karyawan',
                'tepat_waktu'      => $tepatWaktu,
                'sebelum_deadline' => $sebelumDeadline,
                'terlambat'        => $terlambat,
                'total_task'       => $tepatWaktu + $sebelumDeadline + $terlambat,
                'poin'             => $poin,
            ];
        })
            ->sortByDesc('poin')
            ->take(5)
            ->values();
    }
    /* ══════════════════════════════════════════════════════════
     | Aktivitas Task PM  (filtered by project PM)
    ══════════════════════════════════════════════════════════ */
    private function buildPmActivityChart(string $period, array $projekIds): array
    {
        return match ($period) {
            'month' => $this->pmActivityMonthly($projekIds),
            'year'  => $this->pmActivityYearly($projekIds),
            default => $this->pmActivityWeekly($projekIds),
        };
    }

    private function pmActivityWeekly(array $projekIds): array
    {
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        $raw = Tugas::select(
            DB::raw('DATE(tanggal_selesai) AS tgl'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS lebih_awal'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS tepat'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS terlambat')
        )
            ->whereIn('id_projek', $projekIds)
            ->where('status_progress', 'done')
            ->where('status_akhir', 'approved')
            ->whereNotNull('tanggal_selesai')
            ->whereNotNull('tenggat_waktu')
            ->whereBetween(DB::raw('DATE(tanggal_selesai)'), [
                Carbon::now()->subDays(6)->format('Y-m-d'),
                Carbon::now()->format('Y-m-d'),
            ])
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get()
            ->keyBy('tgl');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $d        = Carbon::now()->subDays($i);
            $key      = $d->format('Y-m-d');
            $row      = $raw->get($key);
            $result[] = [
                'label'      => $dayNames[$d->dayOfWeek],
                'tepat'      => (int) ($row->tepat      ?? 0),
                'terlambat'  => (int) ($row->terlambat  ?? 0),
                'lebih_awal' => (int) ($row->lebih_awal ?? 0),
            ];
        }
        return $result;
    }

    private function pmActivityMonthly(array $projekIds): array
    {
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $start      = Carbon::now()->subMonths(11)->startOfMonth();

        $raw = Tugas::select(
            DB::raw('DATE_FORMAT(tanggal_selesai, "%Y-%m") AS ym'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS lebih_awal'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS tepat'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS terlambat')
        )
            ->whereIn('id_projek', $projekIds)
            ->where('status_progress', 'done')
            ->where('status_akhir', 'approved')
            ->whereNotNull('tanggal_selesai')
            ->whereNotNull('tenggat_waktu')
            ->where('tanggal_selesai', '>=', $start)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $m        = Carbon::now()->subMonths($i);
            $ym       = $m->format('Y-m');
            $row      = $raw->get($ym);
            $result[] = [
                'label'      => $monthNames[$m->month - 1] . ' ' . $m->year,
                'tepat'      => (int) ($row->tepat      ?? 0),
                'terlambat'  => (int) ($row->terlambat  ?? 0),
                'lebih_awal' => (int) ($row->lebih_awal ?? 0),
            ];
        }
        return $result;
    }

    private function pmActivityYearly(array $projekIds): array
    {
        $raw = Tugas::select(
            DB::raw('YEAR(tanggal_selesai) AS yr'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS lebih_awal'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS tepat'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS terlambat')
        )
            ->whereIn('id_projek', $projekIds)
            ->where('status_progress', 'done')
            ->where('status_akhir', 'approved')
            ->whereNotNull('tanggal_selesai')
            ->whereNotNull('tenggat_waktu')
            ->groupBy('yr')
            ->orderBy('yr')
            ->get()
            ->keyBy('yr');

        $currentYear = (int) now()->format('Y');
        $result      = [];
        for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
            $row      = $raw->get($yr);
            $result[] = [
                'label'      => (string) $yr,
                'tepat'      => (int) ($row->tepat      ?? 0),
                'terlambat'  => (int) ($row->terlambat  ?? 0),
                'lebih_awal' => (int) ($row->lebih_awal ?? 0),
            ];
        }
        return $result;
    }

public function index3(): \Illuminate\View\View
{
    /** @var \App\Models\User $karyawan */
    $karyawan = Auth::user();

    // ── Ambil semua tim tempat karyawan ini bergabung ──
    $timEntries  = ProjekTim::where('id_user', $karyawan->id_user)->get();
    $timIds      = $timEntries->pluck('id_tim')->toArray();
    $myProjekIds = $timEntries->pluck('id_projek')->unique()->toArray();

    // ── Ambil data project yang diikuti karyawan ──
    $myProjeks = Projek::whereIn('id_projek', $myProjekIds)
        ->select('id_projek', 'nama_projek', 'status')
        ->orderBy('nama_projek')
        ->get();

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 1. STAT CARDS
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */

    // Total Project
    $kyTotalProjek           = $myProjeks->count();
    $kyTotalProjekPending    = $myProjeks->where('status', 'pending')->count();
    $kyTotalProjekAktif      = $myProjeks->where('status', 'aktif')->count();
    $kyTotalProjekSelesai    = $myProjeks->where('status', 'selesai')->count();
    $kyTotalProjekDikerjakan = $myProjeks->where('status', 'in_progress')->count();

    // Total Task milik karyawan (dari tim yang diikutinya)
    $kyTotalTask           = Tugas::whereIn('id_tim', $timIds)
        ->where('status_progress', '!=', 'draft')->count();
    $kyTotalTaskTodo       = Tugas::whereIn('id_tim', $timIds)
        ->where('status_progress', 'To Do')->count();
    $kyTotalTaskInProgress = Tugas::whereIn('id_tim', $timIds)
        ->where('status_progress', 'In Progress')->count();
    $kyTotalTaskDone       = Tugas::whereIn('id_tim', $timIds)
        ->where('status_progress', 'done')->count();

    // Approval Task
    $kyApprovalApproved = Tugas::whereIn('id_tim', $timIds)
        ->where('status_akhir', 'approved')->count();
    $kyApprovalReview   = Tugas::whereIn('id_tim', $timIds)
        ->where('status_progress', 'done')
        ->where('status_akhir', 'review')->count();
    $kyApprovalRevisi   = Tugas::whereIn('id_tim', $timIds)
        ->where('status_akhir', 'revisi')->count();
    $kyApprovalTotal    = $kyApprovalApproved + $kyApprovalReview + $kyApprovalRevisi;

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 2. PERFORMA PRIBADI
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    $allTasks = Tugas::whereIn('id_tim', $timIds)
        ->where('status_progress', '!=', 'draft')
        ->get();

    $jumlahTask      = $allTasks->count();
    $kySebelumDeadline = 0;
    $kyTepatWaktu      = 0;
    $kyTerlambat       = 0;

    foreach ($allTasks as $task) {
        if ($task->status_progress !== 'done') continue;
        if (!$task->tanggal_selesai || !$task->tenggat_waktu) continue;

        $diff = Carbon::parse($task->tanggal_selesai)
            ->diffInDays(Carbon::parse($task->tenggat_waktu), false);

        if ($diff > 0)       $kySebelumDeadline++;
        elseif ($diff === 0) $kyTepatWaktu++;
        else                 $kyTerlambat++;
    }

    // Poin performa (rumus sama dengan PerformaKaryawanController)
    $kyPoin = ($kyTotalProjek      * 5)
            + ($jumlahTask         * 2)
            + ($kySebelumDeadline  * 3)
            + ($kyTepatWaktu       * 2)
            + ($kyTerlambat        * -2);

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 3. PROGRESS PROJECT + STATUS TASK PER PROJECT
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    $kyProgressData   = ['all' => $this->getKaryawanProgressData(null, $timIds)];
    $kyStatusTaskData = ['all' => $this->getKaryawanStatusTaskData(null, $timIds)];

    $kyProgressData['all']['nama'] = 'Semua Project Saya';

    foreach ($myProjeks as $p) {
        // Tim yang relevan untuk project ini
        $timIdsForProject = $timEntries
            ->where('id_projek', $p->id_projek)
            ->pluck('id_tim')
            ->toArray();

        $kyProgressData[$p->id_projek]   = $this->getKaryawanProgressData($p->id_projek, $timIdsForProject);
        $kyStatusTaskData[$p->id_projek] = $this->getKaryawanStatusTaskData($p->id_projek, $timIdsForProject);
        $kyProgressData[$p->id_projek]['nama'] = $p->nama_projek;
    }

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 4. AKTIVITAS PENYELESAIAN TASK (week/month/year)
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    $kyActivityWeek  = $this->buildKaryawanActivityChart('week',  $timIds);
    $kyActivityMonth = $this->buildKaryawanActivityChart('month', $timIds);
    $kyActivityYear  = $this->buildKaryawanActivityChart('year',  $timIds);

    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 5. HEALTH CHART (Kesehatan Task berdasarkan deadline)
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    $kyHealthWeek  = $this->buildKaryawanHealthChart('week',  $timIds);
    $kyHealthMonth = $this->buildKaryawanHealthChart('month', $timIds);
    $kyHealthYear  = $this->buildKaryawanHealthChart('year',  $timIds);

    // Health per project (untuk filter dropdown)
    $kyHealthDataPerProject = [
        'all' => [
            'week'  => $kyHealthWeek,
            'month' => $kyHealthMonth,
            'year'  => $kyHealthYear,
        ]
    ];

    foreach ($myProjeks as $p) {
        $timIdsForProject = $timEntries
            ->where('id_projek', $p->id_projek)
            ->pluck('id_tim')
            ->toArray();

        $kyHealthDataPerProject[$p->id_projek] = [
            'week'  => $this->buildKaryawanHealthChart('week',  $timIdsForProject),
            'month' => $this->buildKaryawanHealthChart('month', $timIdsForProject),
            'year'  => $this->buildKaryawanHealthChart('year',  $timIdsForProject),
        ];
    }

        $kyTop5Karyawan = $this->getTop5Karyawan();

    return view('dashboard.index3', compact(
        'myProjeks',
        // Stat cards
        'kyTotalProjek',
        'kyTotalProjekPending',
        'kyTotalProjekAktif',
        'kyTotalProjekSelesai',
        'kyTotalProjekDikerjakan',
        'kyApprovalTotal',
        'kyApprovalApproved',
        'kyApprovalReview',
        'kyApprovalRevisi',
        'kyTotalTask',
        'kyTotalTaskTodo',
        'kyTotalTaskInProgress',
        'kyTotalTaskDone',
        // Performa
        'kyPoin',
        'kySebelumDeadline',
        'kyTepatWaktu',
        'kyTerlambat',
        // Progress + status
        'kyProgressData',
        'kyStatusTaskData',
        // Aktivitas
        'kyActivityWeek',
        'kyActivityMonth',
        'kyActivityYear',
        // Health chart
        'kyHealthWeek',
        'kyHealthMonth',
        'kyHealthYear',
        'kyHealthDataPerProject',
        'kyTop5Karyawan',
    ));
}

    /* ══════════════════════════════════════════════════════════
 | Top 5 Karyawan berdasarkan Tim IDs (satu tim dengan karyawan login)
══════════════════════════════════════════════════════════ */
    private function getTop5KaryawanByTimIds(array $timIds): \Illuminate\Support\Collection
    {
        if (empty($timIds)) {
            return collect([]);
        }

        // Ambil user yang terlibat di tim yang sama (role karyawan)
        $userIds = ProjekTim::whereIn('id_tim', $timIds)
            ->pluck('id_user')
            ->unique()
            ->toArray();

        $karyawans = User::with('jobRole')
            ->whereIn('id_user', $userIds)
            ->where('role', 'karyawan')
            ->where('status', true)
            ->get();

        return $karyawans->map(function (User $k) {
            $timEntries    = ProjekTim::where('id_user', $k->id_user)->get();
            $timIdsKaryawan = $timEntries->pluck('id_tim')->toArray();
            $jumlahProject = $timEntries->pluck('id_projek')->unique()->count();

            $allTasks = Tugas::whereIn('id_tim', $timIdsKaryawan)
                ->where('status_progress', '!=', 'draft')
                ->get();

            $jumlahTask      = $allTasks->count();
            $sebelumDeadline = 0;
            $tepatWaktu      = 0;
            $terlambat       = 0;

            foreach ($allTasks as $task) {
                if ($task->status_progress !== 'done') continue;
                if (!$task->tanggal_selesai || !$task->tenggat_waktu) continue;

                $diff = Carbon::parse($task->tanggal_selesai)
                    ->diffInDays(Carbon::parse($task->tenggat_waktu), false);

                if ($diff > 0)       $sebelumDeadline++;
                elseif ($diff === 0) $tepatWaktu++;
                else                 $terlambat++;
            }

            $poin = ($jumlahProject  * 5)
                + ($jumlahTask     * 2)
                + ($sebelumDeadline * 3)
                + ($tepatWaktu     * 2)
                + ($terlambat      * -2);

            return [
                'nama'             => $k->nama,
                'jabatan'          => optional($k->jobRole)->nama_job_role ?? 'Karyawan',
                'tepat_waktu'      => $tepatWaktu,
                'sebelum_deadline' => $sebelumDeadline,
                'terlambat'        => $terlambat,
                'total_task'       => $tepatWaktu + $sebelumDeadline + $terlambat,
                'poin'             => $poin,
            ];
        })
            ->sortByDesc('poin')
            ->take(5)
            ->values();
    }
/* ══════════════════════════════════════════════════════════
 | PRIVATE — Progress Data Karyawan
 | Task dari tim yang diikuti karyawan
══════════════════════════════════════════════════════════ */
private function getKaryawanProgressData(?int $idProjek, array $timIds): array
{
    if (empty($timIds)) {
        return ['total' => 0, 'selesai' => 0, 'persen' => 0];
    }

    $q = Tugas::whereIn('id_tim', $timIds)
        ->where('status_progress', '!=', 'draft');

    if ($idProjek) {
        $q->where('id_projek', $idProjek);
    }

    $total   = (clone $q)->count();
    $selesai = (clone $q)
        ->where('status_progress', 'done')
        ->where('status_akhir', 'approved')
        ->count();
    $persen  = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;

    return [
        'total'   => $total,
        'selesai' => $selesai,
        'persen'  => $persen,
    ];
}

/* ══════════════════════════════════════════════════════════
 | PRIVATE — Status Task Data Karyawan
══════════════════════════════════════════════════════════ */
private function getKaryawanStatusTaskData(?int $idProjek, array $timIds): array
{
    if (empty($timIds)) {
        return [
            'todo' => 0, 'todo_pct' => 0,
            'inprogress' => 0, 'inprogress_pct' => 0,
            'done' => 0, 'done_pct' => 0,
            'total' => 0,
            'preview' => 0, 'preview_pct' => 0,
            'revisi' => 0, 'revisi_pct' => 0,
            'approved' => 0, 'approved_pct' => 0,
            'total_final' => 0,
        ];
    }

    $q = Tugas::whereIn('id_tim', $timIds);

    if ($idProjek) {
        $q->where('id_projek', $idProjek);
    }

    $todo       = (clone $q)->where('status_progress', 'To Do')->count();
    $inprogress = (clone $q)->where('status_progress', 'In Progress')->count();
    $done       = (clone $q)->where('status_progress', 'done')->count();
    $total      = $todo + $inprogress + $done;

    $preview    = (clone $q)->where('status_akhir', 'review')->count();
    $revisi     = (clone $q)->where('status_akhir', 'revisi')->count();
    $approved   = (clone $q)->where('status_akhir', 'approved')->count();
    $totalFinal = $preview + $revisi + $approved;

    $pct = fn(int $n, int $d): float => $d > 0 ? round(($n / $d) * 100, 2) : 0;

    return [
        'todo'           => $todo,
        'todo_pct'       => $pct($todo, $total),
        'inprogress'     => $inprogress,
        'inprogress_pct' => $pct($inprogress, $total),
        'done'           => $done,
        'done_pct'       => $pct($done, $total),
        'total'          => $total,
        'preview'        => $preview,
        'preview_pct'    => $pct($preview, $totalFinal),
        'revisi'         => $revisi,
        'revisi_pct'     => $pct($revisi, $totalFinal),
        'approved'       => $approved,
        'approved_pct'   => $pct($approved, $totalFinal),
        'total_final'    => $totalFinal,
    ];
}

/* ══════════════════════════════════════════════════════════
 | PRIVATE — Aktivitas Penyelesaian Task Karyawan
══════════════════════════════════════════════════════════ */
private function buildKaryawanActivityChart(string $period, array $timIds): array
{
    return match ($period) {
        'month' => $this->karyawanActivityMonthly($timIds),
        'year'  => $this->karyawanActivityYearly($timIds),
        default => $this->karyawanActivityWeekly($timIds),
    };
}

private function karyawanActivityWeekly(array $timIds): array
{
    $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    if (empty($timIds)) {
        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i);
            $result[] = ['label' => $dayNames[$d->dayOfWeek], 'tepat' => 0, 'terlambat' => 0, 'lebih_awal' => 0];
        }
        return $result;
    }

    $raw = Tugas::select(
        DB::raw('DATE(tanggal_selesai) AS tgl'),
        DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS lebih_awal'),
        DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS tepat'),
        DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS terlambat')
    )
        ->whereIn('id_tim', $timIds)
        ->where('status_progress', 'done')
        ->where('status_akhir', 'approved')
        ->whereNotNull('tanggal_selesai')
        ->whereNotNull('tenggat_waktu')
        ->whereBetween(DB::raw('DATE(tanggal_selesai)'), [
            Carbon::now()->subDays(6)->format('Y-m-d'),
            Carbon::now()->format('Y-m-d'),
        ])
        ->groupBy('tgl')
        ->orderBy('tgl')
        ->get()
        ->keyBy('tgl');

    $result = [];
    for ($i = 6; $i >= 0; $i--) {
        $d        = Carbon::now()->subDays($i);
        $key      = $d->format('Y-m-d');
        $row      = $raw->get($key);
        $result[] = [
            'label'      => $dayNames[$d->dayOfWeek],
            'tepat'      => (int) ($row->tepat      ?? 0),
            'terlambat'  => (int) ($row->terlambat  ?? 0),
            'lebih_awal' => (int) ($row->lebih_awal ?? 0),
        ];
    }
    return $result;
}

private function karyawanActivityMonthly(array $timIds): array
{
    $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    $start      = Carbon::now()->subMonths(11)->startOfMonth();

    if (empty($timIds)) {
        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $result[] = ['label' => $monthNames[$m->month - 1] . ' ' . $m->year, 'tepat' => 0, 'terlambat' => 0, 'lebih_awal' => 0];
        }
        return $result;
    }

    $raw = Tugas::select(
        DB::raw('DATE_FORMAT(tanggal_selesai, "%Y-%m") AS ym'),
        DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS lebih_awal'),
        DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS tepat'),
        DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS terlambat')
    )
        ->whereIn('id_tim', $timIds)
        ->where('status_progress', 'done')
        ->where('status_akhir', 'approved')
        ->whereNotNull('tanggal_selesai')
        ->whereNotNull('tenggat_waktu')
        ->where('tanggal_selesai', '>=', $start)
        ->groupBy('ym')
        ->orderBy('ym')
        ->get()
        ->keyBy('ym');

    $result = [];
    for ($i = 11; $i >= 0; $i--) {
        $m        = Carbon::now()->subMonths($i);
        $ym       = $m->format('Y-m');
        $row      = $raw->get($ym);
        $result[] = [
            'label'      => $monthNames[$m->month - 1] . ' ' . $m->year,
            'tepat'      => (int) ($row->tepat      ?? 0),
            'terlambat'  => (int) ($row->terlambat  ?? 0),
            'lebih_awal' => (int) ($row->lebih_awal ?? 0),
        ];
    }
    return $result;
}

private function karyawanActivityYearly(array $timIds): array
{
    if (empty($timIds)) {
        $currentYear = (int) now()->format('Y');
        $result = [];
        for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
            $result[] = ['label' => (string) $yr, 'tepat' => 0, 'terlambat' => 0, 'lebih_awal' => 0];
        }
        return $result;
    }

    $raw = Tugas::select(
        DB::raw('YEAR(tanggal_selesai) AS yr'),
        DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS lebih_awal'),
        DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS tepat'),
        DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS terlambat')
    )
        ->whereIn('id_tim', $timIds)
        ->where('status_progress', 'done')
        ->where('status_akhir', 'approved')
        ->whereNotNull('tanggal_selesai')
        ->whereNotNull('tenggat_waktu')
        ->groupBy('yr')
        ->orderBy('yr')
        ->get()
        ->keyBy('yr');

    $currentYear = (int) now()->format('Y');
    $result      = [];
    for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
        $row      = $raw->get($yr);
        $result[] = [
            'label'      => (string) $yr,
            'tepat'      => (int) ($row->tepat      ?? 0),
            'terlambat'  => (int) ($row->terlambat  ?? 0),
            'lebih_awal' => (int) ($row->lebih_awal ?? 0),
        ];
    }
    return $result;
}

/* ══════════════════════════════════════════════════════════
 | PRIVATE — Health Chart Karyawan
 | Kesehatan = task selesai tepat/sebelum deadline / total × 100
══════════════════════════════════════════════════════════ */
private function buildKaryawanHealthChart(string $period, array $timIds): array
{
    if (empty($timIds)) {
        return $this->emptyKaryawanHealthData($period);
    }

    return match ($period) {
        'month' => $this->karyawanHealthMonthly($timIds),
        'year'  => $this->karyawanHealthYearly($timIds),
        default => $this->karyawanHealthWeekly($timIds),
    };
}

private function karyawanHealthWeekly(array $timIds): array
{
    $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    $raw = Tugas::select(
        DB::raw('DATE(tenggat_waktu) AS tgl'),
        DB::raw('SUM(1) AS total'),
        DB::raw('SUM(CASE WHEN status_progress = "done" AND DATE(tanggal_selesai) <= DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS on_time')
    )
        ->whereIn('id_tim', $timIds)
        ->whereNotNull('tenggat_waktu')
        ->whereBetween(DB::raw('DATE(tenggat_waktu)'), [
            Carbon::now()->subDays(6)->format('Y-m-d'),
            Carbon::now()->format('Y-m-d'),
        ])
        ->groupBy('tgl')
        ->orderBy('tgl')
        ->get()
        ->keyBy('tgl');

    $result = [];
    for ($i = 6; $i >= 0; $i--) {
        $d   = Carbon::now()->subDays($i);
        $key = $d->format('Y-m-d');
        $row = $raw->get($key);

        $total   = $row ? (int) $row->total   : 0;
        $onTime  = $row ? (int) $row->on_time : 0;
        $pct     = $total > 0 ? round(($onTime / $total) * 100, 1) : null;

        $result[] = [
            'label'   => $dayNames[$d->dayOfWeek] . ' ' . $d->format('d/m'),
            'total'   => $total,
            'on_time' => $onTime,
            'pct'     => $pct,
        ];
    }
    return $result;
}

private function karyawanHealthMonthly(array $timIds): array
{
    $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    $start      = Carbon::now()->subMonths(11)->startOfMonth();

    $raw = Tugas::select(
        DB::raw('DATE_FORMAT(tenggat_waktu, "%Y-%m") AS ym'),
        DB::raw('SUM(1) AS total'),
        DB::raw('SUM(CASE WHEN status_progress = "done" AND DATE(tanggal_selesai) <= DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS on_time')
    )
        ->whereIn('id_tim', $timIds)
        ->whereNotNull('tenggat_waktu')
        ->where('tenggat_waktu', '>=', $start)
        ->groupBy('ym')
        ->orderBy('ym')
        ->get()
        ->keyBy('ym');

    $result = [];
    for ($i = 11; $i >= 0; $i--) {
        $m   = Carbon::now()->subMonths($i);
        $ym  = $m->format('Y-m');
        $row = $raw->get($ym);

        $total  = $row ? (int) $row->total   : 0;
        $onTime = $row ? (int) $row->on_time : 0;
        $pct    = $total > 0 ? round(($onTime / $total) * 100, 1) : null;

        $result[] = [
            'label'   => $monthNames[$m->month - 1] . ' ' . $m->year,
            'total'   => $total,
            'on_time' => $onTime,
            'pct'     => $pct,
        ];
    }
    return $result;
}

private function karyawanHealthYearly(array $timIds): array
{
    $raw = Tugas::select(
        DB::raw('YEAR(tenggat_waktu) AS yr'),
        DB::raw('SUM(1) AS total'),
        DB::raw('SUM(CASE WHEN status_progress = "done" AND DATE(tanggal_selesai) <= DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS on_time')
    )
        ->whereIn('id_tim', $timIds)
        ->whereNotNull('tenggat_waktu')
        ->groupBy('yr')
        ->orderBy('yr')
        ->get()
        ->keyBy('yr');

    $currentYear = (int) now()->format('Y');
    $result      = [];

    for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
        $row    = $raw->get($yr);
        $total  = $row ? (int) $row->total   : 0;
        $onTime = $row ? (int) $row->on_time : 0;
        $pct    = $total > 0 ? round(($onTime / $total) * 100, 1) : null;

        $result[] = [
            'label'   => (string) $yr,
            'total'   => $total,
            'on_time' => $onTime,
            'pct'     => $pct,
        ];
    }
    return $result;
}

private function emptyKaryawanHealthData(string $period): array
{
    if ($period === 'year') {
        $currentYear = (int) now()->format('Y');
        $result = [];
        for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
            $result[] = ['label' => (string) $yr, 'total' => 0, 'on_time' => 0, 'pct' => null];
        }
        return $result;
    }

    if ($period === 'month') {
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $result[] = [
                'label' => $monthNames[$m->month - 1] . ' ' . $m->year,
                'total' => 0, 'on_time' => 0, 'pct' => null,
            ];
        }
        return $result;
    }

    // week
    $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    $result = [];
    for ($i = 6; $i >= 0; $i--) {
        $d = Carbon::now()->subDays($i);
        $result[] = [
            'label' => $dayNames[$d->dayOfWeek] . ' ' . $d->format('d/m'),
            'total' => 0, 'on_time' => 0, 'pct' => null,
        ];
    }
    return $result;
}
    /* ══════════════════════════════════════════════════════════
 | PRIVATE — Health Chart KLIEN dengan filter per project
══════════════════════════════════════════════════════════ */
    private function getKlienHealthDataForProject(?int $idProjek, array $projekIds): array
    {
        if ($idProjek) {
            $singleId = [$idProjek];
            return [
                'week'  => $this->buildKlienHealthChart('week', $singleId),
                'month' => $this->buildKlienHealthChart('month', $singleId),
                'year'  => $this->buildKlienHealthChart('year', $singleId),
            ];
        }

        return [
            'week'  => $this->buildKlienHealthChart('week', $projekIds),
            'month' => $this->buildKlienHealthChart('month', $projekIds),
            'year'  => $this->buildKlienHealthChart('year', $projekIds),
        ];
    }
    
public function index4(): \Illuminate\View\View
{
    /** @var \App\Models\User $klien */
    $klien = Auth::user();
    
    // Ambil perusahaan milik klien ini
    $perusahaan = $klien->perusahaan;
    
    // Jika klien tidak memiliki perusahaan, tampilkan view kosong dengan pesan
    if (!$perusahaan) {
        return view('dashboard.index4', [
            'error' => 'Akun Anda belum terhubung dengan perusahaan. Silakan hubungi administrator.',
            'hasCompany' => false,
        ]);
    }
    
    // ── Ambil semua project dari perusahaan klien ──
    $allProjeks = Projek::where('id_perusahaan', $perusahaan->id_perusahaan)
        ->select('id_projek', 'nama_projek', 'status', 'nominal_projek', 'sisa_tanggungan')
        ->orderBy('nama_projek')
        ->get();
    
    $projekIds = $allProjeks->pluck('id_projek')->toArray();
    
    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 1. SECTION 1 — STAT CARDS (untuk Klien)
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    
    // Card 1: Total Project perusahaan klien
    $totalProjek      = $allProjeks->count();
    $totalPending     = $allProjeks->where('status', 'pending')->count();
    $totalAktif       = $allProjeks->where('status', 'aktif')->count();
    $totalDikerjakan  = $allProjeks->where('status', 'in_progress')->count();
    $totalSelesai     = $allProjeks->where('status', 'selesai')->count();

        $totalNominal = $allProjeks->sum('nominal_projek');
        $totalSisa    = $allProjeks->sum('sisa_tanggungan');
        $totalTerbayar = max(0, $totalNominal - $totalSisa);
        $totalBelumTerbayar = $totalSisa; // Sisa tagihan = belum terbayar

        // Persentase
        $totalTerbayarPersen = $totalNominal > 0 ? round(($totalTerbayar / $totalNominal) * 100, 2) : 0;
        $totalBelumTerbayarPersen = $totalNominal > 0 ? round(($totalBelumTerbayar / $totalNominal) * 100, 2) : 0;
    // Status pembayaran per project
    $lunasCount      = $allProjeks->where('sisa_tanggungan', '<=', 0)->count();
    $belumDicilCount = 0;
    $dicicilCount    = 0;
    
    foreach ($allProjeks as $p) {
        if ($p->sisa_tanggungan <= 0) {
            continue; // sudah lunas, dihitung di atas
        }
        // Cek apakah sudah ada pembayaran masuk (pernah dicicil)
        $adaPembayaran = DB::table('pembayaran_projek')
            ->where('id_projek', $p->id_projek)
            ->where('status', 'valid')
            ->exists();
        
        if ($adaPembayaran) {
            $dicicilCount++;
        } else {
            $belumDicilCount++;
        }
    }
    // ──────────────────────────────────────────────────────────────
    
    // Card 2: Progress Task dari semua project perusahaan (tetap dipertahankan untuk konten lain)
    $allTasks = Tugas::whereIn('id_projek', $projekIds)
        ->where('status_progress', '!=', 'draft')
        ->get();
    
    $totalTask           = $allTasks->count();
    $totalTaskTodo       = $allTasks->where('status_progress', 'To Do')->count();
    $totalTaskInProgress = $allTasks->where('status_progress', 'In Progress')->count();
    $totalTaskDone       = $allTasks->where('status_progress', 'done')->count();
    
    // Hitung tepat waktu / sebelum deadline / terlambat (untuk semua task)
    $totalSebelumDeadline = 0;
    $totalTepatWaktu      = 0;
    $totalTerlambat       = 0;
    
    foreach ($allTasks as $task) {
        if ($task->status_progress !== 'done') continue;
        if (!$task->tanggal_selesai || !$task->tenggat_waktu) continue;
        
        $diff = Carbon::parse($task->tanggal_selesai)
            ->diffInDays(Carbon::parse($task->tenggat_waktu), false);
        
        if ($diff > 0)       $totalSebelumDeadline++;
        elseif ($diff === 0) $totalTepatWaktu++;
        else                 $totalTerlambat++;
    }
    
    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 2. SECTION 2 — PROGRESS PROJECT + STATUS TASK
     |    (per project atau semua)
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    
    // Build data per project
    $progressData   = ['all' => $this->getKlienProgressData(null, $projekIds)];
    $statusTaskData = ['all' => $this->getKlienStatusTaskData(null, $projekIds)];
    
    $progressData['all']['nama'] = 'Semua Project';
    
    foreach ($allProjeks as $p) {
        $progressData[$p->id_projek]   = $this->getKlienProgressData($p->id_projek, $projekIds);
        $statusTaskData[$p->id_projek] = $this->getKlienStatusTaskData($p->id_projek, $projekIds);
        $progressData[$p->id_projek]['nama'] = $p->nama_projek;
    }
    
    /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
     | 3. SECTION 2 — HEALTH CHART (Kesehatan Project)
     |    Berdasarkan task dari semua project perusahaan
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ */
    
    $healthWeek  = $this->buildKlienHealthChart('week',  $projekIds);
    $healthMonth = $this->buildKlienHealthChart('month', $projekIds);
    $healthYear  = $this->buildKlienHealthChart('year',  $projekIds);

        $healthDataPerProject = ['all' => [
            'week'  => $healthWeek,
            'month' => $healthMonth,
            'year'  => $healthYear,
        ]];
        foreach ($allProjeks as $p) {
            $singleId = [$p->id_projek];
            $healthDataPerProject[$p->id_projek] = [
                'week'  => $this->buildKlienHealthChart('week',  $singleId),
                'month' => $this->buildKlienHealthChart('month', $singleId),
                'year'  => $this->buildKlienHealthChart('year',  $singleId),
            ];
        }

    return view('dashboard.index4', compact(
        'perusahaan',
        'allProjeks',
        'projekIds',
        // Stat cards
        'totalProjek',
        'totalPending',
        'totalAktif',
        'totalDikerjakan',
        'totalSelesai',
        'totalTask',
        'totalTaskTodo',
        'totalTaskInProgress',
        'totalTaskDone',
        'totalSebelumDeadline',
        'totalTepatWaktu',
        'totalTerlambat',
        'healthDataPerProject',
        // DATA PEMBAYARAN (baru)
        'totalNominal',
        'totalSisa',
        'totalTerbayar',
        'totalBelumTerbayar',
        'totalTerbayarPersen',
        'totalBelumTerbayarPersen',
        'lunasCount',
        'dicicilCount',
        'belumDicilCount',
        // Progress + status
        'progressData',
        'statusTaskData',
        // Health chart
        'healthWeek',
        'healthMonth',
        'healthYear',
    ));
}
/* ══════════════════════════════════════════════════════════
 | PRIVATE — Progress Data khusus KLIEN
 | Hanya task dari project perusahaan klien
══════════════════════════════════════════════════════════ */
private function getKlienProgressData(?int $idProjek, array $projekIds): array
{
    if (empty($projekIds)) {
        return ['total' => 0, 'selesai' => 0, 'persen' => 0];
    }
    
    $q = Tugas::whereIn('id_projek', $projekIds)
        ->where('status_progress', '!=', 'draft');
    
    if ($idProjek) {
        $q->where('id_projek', $idProjek);
    }
    
    $total   = (clone $q)->count();
    $selesai = (clone $q)
        ->where('status_progress', 'done')
        ->where('status_akhir', 'approved')
        ->count();
    $persen  = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;
    
    return [
        'total'   => $total,
        'selesai' => $selesai,
        'persen'  => $persen,
    ];
}

/* ══════════════════════════════════════════════════════════
 | PRIVATE — Status Task Data khusus KLIEN
 | Hanya task dari project perusahaan klien
══════════════════════════════════════════════════════════ */
private function getKlienStatusTaskData(?int $idProjek, array $projekIds): array
{
    if (empty($projekIds)) {
        return [
            'todo' => 0, 'todo_pct' => 0,
            'inprogress' => 0, 'inprogress_pct' => 0,
            'done' => 0, 'done_pct' => 0,
            'total' => 0,
            'preview' => 0, 'preview_pct' => 0,
            'revisi' => 0, 'revisi_pct' => 0,
            'approved' => 0, 'approved_pct' => 0,
            'total_final' => 0,
        ];
    }
    
    $q = Tugas::whereIn('id_projek', $projekIds);
    
    if ($idProjek) {
        $q->where('id_projek', $idProjek);
    }
    
    $todo       = (clone $q)->where('status_progress', 'To Do')->count();
    $inprogress = (clone $q)->where('status_progress', 'In Progress')->count();
    $done       = (clone $q)->where('status_progress', 'done')->count();
    $total      = $todo + $inprogress + $done;
    
    $preview    = (clone $q)->where('status_akhir', 'review')->count();
    $revisi     = (clone $q)->where('status_akhir', 'revisi')->count();
    $approved   = (clone $q)->where('status_akhir', 'approved')->count();
    $totalFinal = $preview + $revisi + $approved;
    
    $pct = fn(int $n, int $d): float => $d > 0 ? round(($n / $d) * 100, 2) : 0;
    
    return [
        'todo'           => $todo,
        'todo_pct'       => $pct($todo, $total),
        'inprogress'     => $inprogress,
        'inprogress_pct' => $pct($inprogress, $total),
        'done'           => $done,
        'done_pct'       => $pct($done, $total),
        'total'          => $total,
        'preview'        => $preview,
        'preview_pct'    => $pct($preview, $totalFinal),
        'revisi'         => $revisi,
        'revisi_pct'     => $pct($revisi, $totalFinal),
        'approved'       => $approved,
        'approved_pct'   => $pct($approved, $totalFinal),
        'total_final'    => $totalFinal,
    ];
}

/* ══════════════════════════════════════════════════════════
 | PRIVATE — Health Chart KLIEN
 | Kesehatan = (tepat + sebelum deadline) / total × 100
 | Difilter hanya task dari project perusahaan klien
══════════════════════════════════════════════════════════ */
private function buildKlienHealthChart(string $period, array $projekIds): array
{
    if (empty($projekIds)) {
        return $this->emptyKlienHealthData($period);
    }
    
    return match ($period) {
        'month' => $this->klienHealthMonthly($projekIds),
        'year'  => $this->klienHealthYearly($projekIds),
        default => $this->klienHealthWeekly($projekIds),
    };
}

/** Per hari — 7 hari terakhir */
private function klienHealthWeekly(array $projekIds): array
{
    $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    
    $raw = Tugas::select(
        DB::raw('DATE(tenggat_waktu) AS tgl'),
        DB::raw('SUM(1) AS total'),
        DB::raw('SUM(CASE WHEN status_progress = "done" AND DATE(tanggal_selesai) <= DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS on_time')
    )
        ->whereIn('id_projek', $projekIds)
        ->whereNotNull('tenggat_waktu')
        ->whereBetween(DB::raw('DATE(tenggat_waktu)'), [
            Carbon::now()->subDays(6)->format('Y-m-d'),
            Carbon::now()->format('Y-m-d'),
        ])
        ->groupBy('tgl')
        ->orderBy('tgl')
        ->get()
        ->keyBy('tgl');
    
    $result = [];
    for ($i = 6; $i >= 0; $i--) {
        $d   = Carbon::now()->subDays($i);
        $key = $d->format('Y-m-d');
        $row = $raw->get($key);
        
        $total   = $row ? (int) $row->total   : 0;
        $onTime  = $row ? (int) $row->on_time : 0;
        $pct     = $total > 0 ? round(($onTime / $total) * 100, 1) : null;
        
        $result[] = [
            'label'   => $dayNames[$d->dayOfWeek] . ' ' . $d->format('d/m'),
            'total'   => $total,
            'on_time' => $onTime,
            'pct'     => $pct,
        ];
    }
    return $result;
}

/** Per bulan — 12 bulan terakhir */
private function klienHealthMonthly(array $projekIds): array
{
    $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    $start      = Carbon::now()->subMonths(11)->startOfMonth();
    
    $raw = Tugas::select(
        DB::raw('DATE_FORMAT(tenggat_waktu, "%Y-%m") AS ym'),
        DB::raw('SUM(1) AS total'),
        DB::raw('SUM(CASE WHEN status_progress = "done" AND DATE(tanggal_selesai) <= DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS on_time')
    )
        ->whereIn('id_projek', $projekIds)
        ->whereNotNull('tenggat_waktu')
        ->where('tenggat_waktu', '>=', $start)
        ->groupBy('ym')
        ->orderBy('ym')
        ->get()
        ->keyBy('ym');
    
    $result = [];
    for ($i = 11; $i >= 0; $i--) {
        $m   = Carbon::now()->subMonths($i);
        $ym  = $m->format('Y-m');
        $row = $raw->get($ym);
        
        $total  = $row ? (int) $row->total   : 0;
        $onTime = $row ? (int) $row->on_time : 0;
        $pct    = $total > 0 ? round(($onTime / $total) * 100, 1) : null;
        
        $result[] = [
            'label'   => $monthNames[$m->month - 1] . ' ' . $m->year,
            'total'   => $total,
            'on_time' => $onTime,
            'pct'     => $pct,
        ];
    }
    return $result;
}

/** Per tahun — 5 tahun terakhir */
private function klienHealthYearly(array $projekIds): array
{
    $raw = Tugas::select(
        DB::raw('YEAR(tenggat_waktu) AS yr'),
        DB::raw('SUM(1) AS total'),
        DB::raw('SUM(CASE WHEN status_progress = "done" AND DATE(tanggal_selesai) <= DATE(tenggat_waktu) THEN 1 ELSE 0 END) AS on_time')
    )
        ->whereIn('id_projek', $projekIds)
        ->whereNotNull('tenggat_waktu')
        ->groupBy('yr')
        ->orderBy('yr')
        ->get()
        ->keyBy('yr');
    
    $currentYear = (int) now()->format('Y');
    $result      = [];
    
    for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
        $row    = $raw->get($yr);
        $total  = $row ? (int) $row->total   : 0;
        $onTime = $row ? (int) $row->on_time : 0;
        $pct    = $total > 0 ? round(($onTime / $total) * 100, 1) : null;
        
        $result[] = [
            'label'   => (string) $yr,
            'total'   => $total,
            'on_time' => $onTime,
            'pct'     => $pct,
        ];
    }
    return $result;
}

/** Helper: data kosong untuk health chart */
private function emptyKlienHealthData(string $period): array
{
    if ($period === 'year') {
        $currentYear = (int) now()->format('Y');
        $result = [];
        for ($yr = $currentYear - 4; $yr <= $currentYear; $yr++) {
            $result[] = ['label' => (string) $yr, 'total' => 0, 'on_time' => 0, 'pct' => null];
        }
        return $result;
    }
    
    if ($period === 'month') {
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $result[] = [
                'label' => $monthNames[$m->month - 1] . ' ' . $m->year,
                'total' => 0, 'on_time' => 0, 'pct' => null,
            ];
        }
        return $result;
    }
    
    // week
    $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    $result = [];
    for ($i = 6; $i >= 0; $i--) {
        $d = Carbon::now()->subDays($i);
        $result[] = [
            'label' => $dayNames[$d->dayOfWeek] . ' ' . $d->format('d/m'),
            'total' => 0, 'on_time' => 0, 'pct' => null,
        ];
    }
    return $result;
}
}
