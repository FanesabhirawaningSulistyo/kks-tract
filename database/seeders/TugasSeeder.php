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

        // Ambil projek beserta tanggal mulai
        $projekList = DB::table('projek')
            ->select('id_projek', 'tanggal_mulai')
            ->get();

        if ($projekList->isEmpty()) {
            $this->command->error('Tidak ada projek ditemukan.');
            return;
        }

        foreach ($projekList as $projek) {

            $idProjek = $projek->id_projek;
            $startProject = Carbon::parse($projek->tanggal_mulai);

            // Ambil anggota tim projek
            $timMembers = DB::table('projek_tim')
                ->where('id_projek', $idProjek)
                ->pluck('id_tim')
                ->toArray();

            if (empty($timMembers)) {
                continue;
            }

            // 4–10 task per proyek
            $jumlahTask = rand(4, 10);

            for ($i = 1; $i <= $jumlahTask; $i++) {

                $idTim = $timMembers[array_rand($timMembers)];

                // Level task
                $levelList = ['mudah', 'medium', 'susah'];
                $level = $levelList[array_rand($levelList)];

                $weightMap = [
                    'mudah' => 1,
                    'medium' => 2,
                    'susah' => 3
                ];

                // Variasi status progress
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
                LOGIKA TANGGAL TASK
                - tidak boleh sebelum project mulai
                - task dibuat bertahap setelah project berjalan
                */

                $tanggalMulai = (clone $startProject)
                    ->addDays(rand(0, 20));

                // durasi pengerjaan task
                $durasi = rand(5, 12);

                $tenggatWaktu = (clone $tanggalMulai)
                    ->addDays($durasi);

                // tanggal selesai hanya jika task done
                $tanggalSelesai = null;

                if ($statusProgress === 'done') {

                    $tanggalSelesai = (clone $tanggalMulai)
                        ->addDays(rand(3, $durasi));
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
