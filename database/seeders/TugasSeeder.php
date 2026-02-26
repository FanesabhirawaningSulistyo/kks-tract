<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

                // RANDOM STATUS PROGRESS
                $statusProgressList = ['draft', 'progres', 'selesai'];
                $statusProgress = $statusProgressList[array_rand($statusProgressList)];

                // LOGIKA STATUS AKHIR
                $statusAkhir = null;

                if ($statusProgress === 'progres') {
                    $statusAkhir = 'review';
                }

                if ($statusProgress === 'selesai') {
                    $statusAkhirList = ['review', 'revisi', 'approved'];
                    $statusAkhir = $statusAkhirList[array_rand($statusAkhirList)];
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
                    'tenggat_waktu'   => now()->addDays(rand(5, 30)),
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
