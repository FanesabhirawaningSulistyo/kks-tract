<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TugasSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $totalInserted = 0;

        $projekList = DB::table('projek')->pluck('id_projek');

        if ($projekList->isEmpty()) {
            $this->command->error('Tidak ada projek ditemukan.');
            return;
        }

        foreach ($projekList as $idProjek) {

            $timMembers = DB::table('projek_tim')
                ->where('id_projek', $idProjek)
                ->pluck('id_tim')
                ->toArray();

            if (empty($timMembers)) {
                continue;
            }

            $jumlahTask = rand(5, 7);

            for ($i = 1; $i <= $jumlahTask; $i++) {

                $idTim = $timMembers[array_rand($timMembers)];

                $levelList = ['mudah', 'medium', 'susah'];
                $level = $levelList[array_rand($levelList)];

                $weightMap = [
                    'mudah'  => 1,
                    'medium' => 2,
                    'susah'  => 3,
                ];

                $statusProgressList = ['draft', 'To Do', 'In Progress', 'done'];
                $statusProgress = $statusProgressList[array_rand($statusProgressList)];

                $statusAkhir = null;

                if ($statusProgress === 'In Progress') {
                    $statusAkhir = 'review';
                }

                if ($statusProgress === 'done') {
                    $statusAkhirList = ['review', 'revisi', 'approved'];
                    $statusAkhir = $statusAkhirList[array_rand($statusAkhirList)];
                }

                /*
                 * ATURAN BARU:
                 * - Tahun 2026
                 * - Bulan hanya Januari (1) atau Februari (2)
                 * - Tanggal mulai 7–14
                 * - Durasi 5–12 hari
                 * - Tidak boleh lewat Februari
                 */

                // Bulan hanya Januari atau Februari
                $bulan = rand(1, 2);

                // Tanggal mulai antara 7–14
                $tanggalMulai = Carbon::create(
                    2026,
                    $bulan,
                    rand(7, 14)
                );

                // Durasi 5–12 hari
                $durasi = rand(5, 12);

                $tenggatWaktu = (clone $tanggalMulai)->addDays($durasi);

                // Jika melewati Februari, paksa ke 28 Februari 2026
                $batasAkhirFeb = Carbon::create(2026, 2, 28);

                if ($tenggatWaktu->gt($batasAkhirFeb)) {
                    $tenggatWaktu = $batasAkhirFeb;
                }

                // tanggal_selesai hanya jika task selesai
                $tanggalSelesai = null;

                if ($statusProgress === 'done') {
                    $tanggalSelesai = (clone $tenggatWaktu)
                        ->subDays(rand(0, 2)); // selesai mendekati deadline
                }

                DB::table('tugas')->insert([
                    'id_projek'       => $idProjek,
                    'id_tim'          => $idTim,
                    'judul_tugas'     => "Task {$i} Projek {$idProjek}",
                    'deskripsi_tugas' => "Deskripsi pekerjaan untuk task {$i} pada projek {$idProjek}",
                    'level'           => $level,
                    'weight'          => $weightMap[$level],
                    'status_progress' => $statusProgress,
                    'status_akhir'    => $statusAkhir,
                    'tanggal_mulai'   => $tanggalMulai,
                    'tenggat_waktu'   => $tenggatWaktu,
                    'tanggal_selesai' => $tanggalSelesai,
                    'dibuat_pada'     => $now,
                    'diubah_pada'     => $now,
                ]);

                $totalInserted++;
            }
        }

        $this->command->info("Seeder tugas selesai.");
        $this->command->info("Total task dibuat: {$totalInserted}");
    }
}
