<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Projek;
use App\Models\ProjekTim;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformaKaryawanController extends Controller
{
    /* ─────────────────────────────────────────────────────────────────
     | KONSTANTA POIN
     | - 1 project              : +5 poin
     | - 1 task assigned        : +2 poin
     | - selesai sebelum deadl. : +3 poin
     | - selesai tepat waktu    : +2 poin
     | - terlambat              : -2 poin
     ───────────────────────────────────────────────────────────────── */
    public const POIN_PROJECT         = 5;
    public const POIN_TASK            = 2;
    public const POIN_BEFORE_DEADLINE = 3;
    public const POIN_ON_TIME         = 2;
    public const POIN_LATE            = -2;

    /* ─────────────────────────────────────────────────────────────────
     | INDEX
     ───────────────────────────────────────────────────────────────── */
    /**
     * Tampilkan halaman performa karyawan.
     * - Karyawan  → top-5 saja di tabel, tapi bisa lihat detail dirinya sendiri
     * - PM/Admin  → semua karyawan, ADA tombol detail (isLimitedView = false)
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $isLimitedView = $user->isKaryawan();

        // Build semua performa dengan rank
        $allPerforma = $this->buildAllPerformaData();

        // Untuk tabel: karyawan hanya lihat top-5
        $performaData = $isLimitedView ? $allPerforma->take(5) : $allPerforma;

        // Untuk banner karyawan: data lengkap dirinya sendiri (mungkin tidak masuk top-5)
        $myPerforma = null;
        if ($isLimitedView) {
            $myPerforma = $allPerforma->firstWhere('id_user', $user->id_user);
        }

        return view('dashboard.performa-karyawan', compact(
            'performaData',
            'isLimitedView',
            'user',
            'myPerforma'
        ));
    }

    /* ─────────────────────────────────────────────────────────────────
     | DETAIL (Ajax / Modal)
     | - PM & Admin : boleh lihat detail siapapun
     | - Karyawan   : hanya boleh lihat detail dirinya sendiri
     ───────────────────────────────────────────────────────────────── */
    public function detail($id_user)
    {
        /** @var User $auth */
        $auth = Auth::user();

        // Karyawan hanya boleh akses detail dirinya sendiri
        if ($auth->isKaryawan() && $auth->id_user != $id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.',
            ], 403);
        }

        $karyawan = User::with('jobRole')->findOrFail($id_user);
        $detail   = $this->getDetailKaryawan((int) $id_user);

        return response()->json([
            'success' => true,
            'data'    => array_merge([
                'nama'    => $karyawan->nama,
                'email'   => $karyawan->email,
                'jabatan' => optional($karyawan->jobRole)->nama_job_role ?? 'Karyawan',
                'foto'    => $karyawan->foto,
            ], $detail),
        ]);
    }

    /* ─────────────────────────────────────────────────────────────────
     | PRIVATE HELPERS
     ───────────────────────────────────────────────────────────────── */

    /**
     * Bangun collection performa SELURUH karyawan aktif, diurutkan poin DESC,
     * lengkap dengan rank. Tidak ada filtering di sini.
     */
    private function buildAllPerformaData(): \Illuminate\Support\Collection
    {
        $karyawans = User::with('jobRole')
            ->where('role', 'karyawan')
            ->where('status', true)
            ->orderBy('nama')
            ->get();

        return $karyawans
            ->map(fn($k) => $this->hitungPerforma($k))
            ->sortByDesc('poin')
            ->values()
            ->map(function ($item, $index) {
                $item['rank'] = $index + 1;
                return $item;
            });
    }

    /**
     * Hitung ringkasan performa satu karyawan.
     */
    private function hitungPerforma(User $karyawan): array
    {
        $idUser     = $karyawan->id_user;
        $timEntries = ProjekTim::where('id_user', $idUser)->get();
        $timIds     = $timEntries->pluck('id_tim')->toArray();

        $jumlahProject = $timEntries->count();

        // Semua task (bukan draft) yang masuk dalam tim-tim user ini
        $allTasks = Tugas::whereIn('id_tim', $timIds)
            ->where('status_progress', '!=', 'draft')
            ->get();

        $jumlahTask      = $allTasks->count();
        $sebelumDeadline = 0;
        $tepatWaktu      = 0;
        $terlambat       = 0;

        foreach ($allTasks as $task) {
            if ($task->status_progress !== 'done') continue;

            $selesai  = $task->tanggal_selesai;
            $deadline = $task->tenggat_waktu;

            if (!$selesai || !$deadline) continue;

            $diff = \Carbon\Carbon::parse($selesai)
                ->diffInDays(\Carbon\Carbon::parse($deadline), false);

            if ($diff > 0)       $sebelumDeadline++;
            elseif ($diff === 0) $tepatWaktu++;
            else                 $terlambat++;
        }

        $poin = ($jumlahProject   * self::POIN_PROJECT)
            + ($jumlahTask       * self::POIN_TASK)
            + ($sebelumDeadline  * self::POIN_BEFORE_DEADLINE)
            + ($tepatWaktu       * self::POIN_ON_TIME)
            + ($terlambat        * self::POIN_LATE);

        return [
            'id_user'          => $karyawan->id_user,
            'nama'             => $karyawan->nama,
            'email'            => $karyawan->email,
            'jabatan'          => optional($karyawan->jobRole)->nama_job_role ?? 'Karyawan',
            'foto'             => $karyawan->foto,
            'jumlah_project'   => $jumlahProject,
            'jumlah_task'      => $jumlahTask,
            'sebelum_deadline' => $sebelumDeadline,
            'tepat_waktu'      => $tepatWaktu,
            'terlambat'        => $terlambat,
            'poin'             => $poin,
        ];
    }

    /**
     * Detail lengkap untuk modal: stat tiles + chart data + project cards.
     */
    private function getDetailKaryawan(int $idUser): array
    {
        $timEntries    = ProjekTim::with('projek')->where('id_user', $idUser)->get();
        $projectDetail = [];

        foreach ($timEntries as $tim) {
            $projek = $tim->projek;
            if (!$projek) continue;

            $tasks = Tugas::where('id_tim', $tim->id_tim)
                ->where('status_progress', '!=', 'draft')
                ->get();

            $sebelumDeadline = 0;
            $tepatWaktu      = 0;
            $terlambat       = 0;
            $belumSelesai    = 0;

            foreach ($tasks as $task) {
                if ($task->status_progress !== 'done') {
                    $belumSelesai++;
                    continue;
                }

                $selesai  = $task->tanggal_selesai;
                $deadline = $task->tenggat_waktu;

                if (!$selesai || !$deadline) {
                    $tepatWaktu++;
                    continue;
                }

                $diff = \Carbon\Carbon::parse($selesai)
                    ->diffInDays(\Carbon\Carbon::parse($deadline), false);

                if ($diff > 0)       $sebelumDeadline++;
                elseif ($diff === 0) $tepatWaktu++;
                else                 $terlambat++;
            }

            $totalTask    = $tasks->count();
            $selesaiCount = $tasks->where('status_progress', 'done')->count();

            $poinProject = self::POIN_PROJECT
                + ($totalTask        * self::POIN_TASK)
                + ($sebelumDeadline  * self::POIN_BEFORE_DEADLINE)
                + ($tepatWaktu       * self::POIN_ON_TIME)
                + ($terlambat        * self::POIN_LATE);

            $projectDetail[] = [
                'nama_projek'      => $projek->nama_projek,
                'status_projek'    => $projek->status,
                'total_task'       => $totalTask,
                'selesai'          => $selesaiCount,
                'belum_selesai'    => $belumSelesai,
                'sebelum_deadline' => $sebelumDeadline,
                'tepat_waktu'      => $tepatWaktu,
                'terlambat'        => $terlambat,
                'poin_project'     => $poinProject,
            ];
        }

        /* ── Agregat keseluruhan ── */
        $totalTask      = array_sum(array_column($projectDetail, 'total_task'));
        $totalSelesai   = array_sum(array_column($projectDetail, 'selesai'));
        $totalSblm      = array_sum(array_column($projectDetail, 'sebelum_deadline'));
        $totalTepat     = array_sum(array_column($projectDetail, 'tepat_waktu'));
        $totalTerlambat = array_sum(array_column($projectDetail, 'terlambat'));
        $totalProjek    = count($projectDetail);
        $totalPoin      = array_sum(array_column($projectDetail, 'poin_project'));

        $completionRate = $totalTask > 0
            ? round(($totalSelesai / $totalTask) * 100, 1)
            : 0;

        $totalWaktu = $totalSblm + $totalTepat + $totalTerlambat;
        $onTimeRate = $totalWaktu > 0
            ? round((($totalSblm + $totalTepat) / $totalWaktu) * 100, 1)
            : 0;

        return [
            'project_detail'   => $projectDetail,
            'total_project'    => $totalProjek,
            'total_task'       => $totalTask,
            'total_selesai'    => $totalSelesai,
            'sebelum_deadline' => $totalSblm,
            'tepat_waktu'      => $totalTepat,
            'terlambat'        => $totalTerlambat,
            'total_poin'       => $totalPoin,
            'completion_rate'  => $completionRate,
            'on_time_rate'     => $onTimeRate,
        ];
    }
}
