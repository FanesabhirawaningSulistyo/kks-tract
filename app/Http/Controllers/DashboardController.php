<?php

namespace App\Http\Controllers;

use App\Models\Projek;
use App\Models\Tugas;
use App\Models\User;
use App\Models\JobRole;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        /* ── 1. STATISTIK PROYEK ── */
        $statsProyek = [
            'total'       => Projek::count(),
            'aktif'       => Projek::where('status', 'aktif')->count(),
            'selesai'     => Projek::where('status', 'selesai')->count(),
            'in_progress' => Projek::where('status', 'in_progress')->count(),
        ];

        /* ── 2. STATISTIK TASK ── */
        $taskTodo       = Tugas::where('status_progress', 'To Do')->count();
        $taskInProgress = Tugas::where('status_progress', 'In Progress')->count();
        $taskReview     = Tugas::where('status_akhir', 'review')->count();
        $taskDone       = Tugas::where('status_progress', 'done')
            ->where('status_akhir', 'approved')->count();

        $statsTasks = [
            'todo'       => $taskTodo,
            'inprogress' => $taskInProgress,
            'review'     => $taskReview,
            'done'       => $taskDone,
            'total'      => $taskTodo + $taskInProgress + $taskReview + $taskDone,
        ];

        /* ── 3. TASK PER PROYEK AKTIF (filter pie chart) ── */
        $taskPerProjek = Projek::whereIn('status', ['aktif', 'in_progress'])
            ->withCount([
                'tugas as todo_count'       => fn($q) => $q->where('status_progress', 'To Do'),
                'tugas as inprogress_count' => fn($q) => $q->where('status_progress', 'In Progress'),
                'tugas as review_count'     => fn($q) => $q->where('status_akhir', 'review'),
                'tugas as done_count'       => fn($q) => $q->where('status_progress', 'done')
                    ->where('status_akhir', 'approved'),
            ])
            ->get(['id_projek', 'nama_projek'])
            ->map(fn($p) => [
                'id'         => $p->id_projek,
                'nama'       => $p->nama_projek,
                'todo'       => (int) $p->todo_count,
                'inprogress' => (int) $p->inprogress_count,
                'review'     => (int) $p->review_count,
                'done'       => (int) $p->done_count,
            ])
            ->values();

        /* ── 4. PEROLEHAN PROYEK 12 BULAN ── */
        $chartAcquisition = $this->buildAcquisitionChart();

        /* ── 5. DEADLINE PERFORMANCE — default minggu ini ── */
        $chartDeadline = $this->buildDeadlineChart('week');

        /* ── 6. TOP 5 PERFORMERS ── */
        $topPerformers = DB::table('users')
            ->join('projek_tim', 'users.id_user', '=', 'projek_tim.id_user')
            ->join('tugas', 'projek_tim.id_tim', '=', 'tugas.id_tim')
            ->leftJoin('job_roles', 'users.id_job_role', '=', 'job_roles.id_job_role')
            ->where('users.role', 'karyawan')
            ->where('users.status', true)
            ->where('tugas.status_progress', 'done')
            ->where(function ($q) {
                $q->whereNull('tugas.tenggat_waktu')
                    ->orWhereRaw('DATE(tugas.tanggal_selesai) <= DATE(tugas.tenggat_waktu)');
            })
            ->select(
                'users.id_user',
                'users.nama',
                'users.foto',
                'job_roles.nama_job_role',
                DB::raw('COUNT(tugas.id_tugas) as task_count')
            )
            ->groupBy('users.id_user', 'users.nama', 'users.foto', 'job_roles.nama_job_role')
            ->orderByDesc('task_count')
            ->limit(5)
            ->get();

        /* ── 7. KARYAWAN PER JOB ROLE ── */
        $karyawanPerRole = JobRole::withCount([
            'users as jumlah' => fn($q) =>
            $q->where('role', 'karyawan')->where('status', true)
        ])
            ->where('status', true)
            ->having('jumlah', '>', 0)
            ->orderByDesc('jumlah')
            ->get();

        $totalKaryawan = User::where('role', 'karyawan')->where('status', true)->count();

        /* ── 8. TASK REVIEW MODAL ── */
        $reviewTaskList = Tugas::with(['projek', 'tim.user'])
            ->where('status_akhir', 'review')
            ->orderByDesc('dibuat_pada')
            ->limit(10)
            ->get();

        /* ── 9. NOTIFIKASI MINGGU INI ── */
        $newProjekWeek = Projek::where('dibuat_pada', '>=', Carbon::now()->startOfWeek())->count();
        $newTaskWeek   = Tugas::where('dibuat_pada', '>=', Carbon::now()->startOfWeek())->count();

        return view('dashboard.index', compact(
            'statsProyek',
            'statsTasks',
            'taskPerProjek',
            'chartAcquisition',
            'chartDeadline',
            'topPerformers',
            'karyawanPerRole',
            'totalKaryawan',
            'reviewTaskList',
            'newProjekWeek',
            'newTaskWeek'
        ));
    }

    /* ══════════════════════════════════════════════════════════
     | AJAX — GET /dashboard/deadline-chart?period=week|month|year
    ══════════════════════════════════════════════════════════ */
    public function deadlineChartData(Request $request)
    {
        $period = $request->get('period', 'week');
        return response()->json([
            'success' => true,
            'data'    => $this->buildDeadlineChart($period),
        ]);
    }

    /* ── DISPATCHER ── */
    private function buildDeadlineChart(string $period): array
    {
        return match ($period) {
            'month' => $this->deadlineMonthly(),
            'year'  => $this->deadlineYearly(),
            default => $this->deadlineWeekly(),
        };
    }

    /* ──────────────────────────────────────────────────────────
     | WEEKLY  — 7 titik, tiap 1 hari
    ────────────────────────────────────────────────────────── */
    private function deadlineWeekly(): array
    {
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        $raw = $this->rawDeadlineByDate(
            Carbon::now()->subDays(6)->format('Y-m-d'),
            Carbon::now()->format('Y-m-d')
        )->keyBy('tgl');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $d   = Carbon::now()->subDays($i);
            $key = $d->format('Y-m-d');
            $row = $raw->get($key);
            $result[] = [
                'label'      => $dayNames[$d->dayOfWeek] . ' ' . $d->format('d/m'),
                'tepat'      => (int) ($row->tepat      ?? 0),
                'lebih_awal' => (int) ($row->lebih_awal ?? 0),
                'terlambat'  => (int) ($row->terlambat  ?? 0),
            ];
        }
        return $result;
    }

    /* ──────────────────────────────────────────────────────────
     | MONTHLY — 4 titik, tiap ~1 minggu (30 hari terakhir)
    ────────────────────────────────────────────────────────── */
    private function deadlineMonthly(): array
    {
        $raw = $this->rawDeadlineByDate(
            Carbon::now()->subDays(29)->format('Y-m-d'),
            Carbon::now()->format('Y-m-d')
        )->keyBy('tgl');

        $result = [];
        // Bagi 30 hari → 4 periode @ ~7-8 hari
        $periods = [
            ['days_from' => 29, 'days_to' => 22, 'label' => '4 mgg lalu'],
            ['days_from' => 21, 'days_to' => 15, 'label' => '3 mgg lalu'],
            ['days_from' => 14, 'days_to' =>  8, 'label' => '2 mgg lalu'],
            ['days_from' =>  7, 'days_to' =>  1, 'label' => 'Mgg lalu'],
            ['days_from' =>  0, 'days_to' =>  0, 'label' => 'Hari ini'],
        ];

        foreach ($periods as $p) {
            $start  = Carbon::now()->subDays($p['days_from'])->startOfDay();
            $end    = Carbon::now()->subDays($p['days_to'])->endOfDay();
            $sub    = $p['days_to'] === 0 ? 'Hari ini' : ($start->format('d/m') . '–' . $end->format('d/m'));

            $tepat = $lebih = $telat = 0;
            $cur   = $start->copy();
            while ($cur->lte($end)) {
                if ($row = $raw->get($cur->format('Y-m-d'))) {
                    $tepat += (int) $row->tepat;
                    $lebih += (int) $row->lebih_awal;
                    $telat += (int) $row->terlambat;
                }
                $cur->addDay();
            }
            $result[] = [
                'label'      => $p['label'],
                'sublabel'   => $sub,
                'tepat'      => $tepat,
                'lebih_awal' => $lebih,
                'terlambat'  => $telat,
            ];
        }
        return $result;
    }

    /* ──────────────────────────────────────────────────────────
     | YEARLY — 12 titik, tiap 1 bulan
    ────────────────────────────────────────────────────────── */
    private function deadlineYearly(): array
    {
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

        $raw = Tugas::select(
            DB::raw('DATE_FORMAT(tanggal_selesai, "%Y-%m") as ym'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu)  THEN 1 ELSE 0 END) as lebih_awal'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu)  THEN 1 ELSE 0 END) as tepat'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu)  THEN 1 ELSE 0 END) as terlambat')
        )
            ->where('status_progress', 'done')
            ->whereNotNull('tanggal_selesai')->whereNotNull('tenggat_waktu')
            ->where('tanggal_selesai', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('ym')->orderBy('ym')
            ->get()->keyBy('ym');

        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $m   = Carbon::now()->subMonths($i);
            $ym  = $m->format('Y-m');
            $row = $raw->get($ym);
            $result[] = [
                'label'      => $monthNames[(int) $m->format('m') - 1] . ' ' . $m->format('Y'),
                'tepat'      => (int) ($row->tepat      ?? 0),
                'lebih_awal' => (int) ($row->lebih_awal ?? 0),
                'terlambat'  => (int) ($row->terlambat  ?? 0),
            ];
        }
        return $result;
    }

    /* ── Raw query harian ── */
    private function rawDeadlineByDate(string $from, string $to)
    {
        return Tugas::select(
            DB::raw('DATE(tanggal_selesai) as tgl'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) < DATE(tenggat_waktu)  THEN 1 ELSE 0 END) as lebih_awal'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) = DATE(tenggat_waktu)  THEN 1 ELSE 0 END) as tepat'),
            DB::raw('SUM(CASE WHEN DATE(tanggal_selesai) > DATE(tenggat_waktu)  THEN 1 ELSE 0 END) as terlambat')
        )
            ->where('status_progress', 'done')
            ->whereNotNull('tanggal_selesai')->whereNotNull('tenggat_waktu')
            ->whereBetween(DB::raw('DATE(tanggal_selesai)'), [$from, $to])
            ->groupBy('tgl')->orderBy('tgl')
            ->get();
    }

    /* ── Acquisition Chart (12 bulan) ── */
    private function buildAcquisitionChart(): array
    {
        $monthNames      = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $startOf12Months = Carbon::now()->subMonths(11)->startOfMonth();

        $projekDidapat = Projek::select(DB::raw('DATE_FORMAT(dibuat_pada,"%Y-%m") as ym'), DB::raw('COUNT(*) as cnt'))
            ->where('dibuat_pada', '>=', $startOf12Months)->groupBy('ym')->pluck('cnt', 'ym');

        $projekSelesai = Projek::select(DB::raw('DATE_FORMAT(tanggal_selesai,"%Y-%m") as ym'), DB::raw('COUNT(*) as cnt'))
            ->where('status', 'selesai')->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '>=', $startOf12Months)->groupBy('ym')->pluck('cnt', 'ym');

        $projekAktif = Projek::select(DB::raw('DATE_FORMAT(dibuat_pada,"%Y-%m") as ym'), DB::raw('COUNT(*) as cnt'))
            ->whereIn('status', ['aktif', 'in_progress'])
            ->where('dibuat_pada', '>=', $startOf12Months)->groupBy('ym')->pluck('cnt', 'ym');

        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $m  = Carbon::now()->subMonths($i);
            $ym = $m->format('Y-m');
            $result[] = [
                'label'   => $monthNames[(int) $m->format('m') - 1],
                'didapat' => (int) ($projekDidapat[$ym] ?? 0),
                'aktif'   => (int) ($projekAktif[$ym]  ?? 0),
                'selesai' => (int) ($projekSelesai[$ym] ?? 0),
            ];
        }
        return $result;
    }

    public function index3()
    {
        return view('dashboard.index3');
    }
    public function index4()
    {
        return view('dashboard.index4');
    }
}
