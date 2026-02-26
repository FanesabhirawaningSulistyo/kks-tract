<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjekTimSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Ambil semua projek
        $projekList = DB::table('projek')->pluck('id_projek')->toArray();

        if (empty($projekList)) {
            $this->command->error('❌ Tidak ada projek ditemukan.');
            return;
        }

        // Ambil semua karyawan aktif
        $karyawan = DB::table('users')
            ->where('role', 'karyawan')
            ->where('status', true)
            ->pluck('id_user')
            ->toArray();

        if (count($karyawan) < 3) {
            $this->command->error('❌ Minimal harus ada 3 karyawan aktif.');
            return;
        }

        $inserted = 0;

        foreach ($projekList as $idProjek) {

            // Acak karyawan
            shuffle($karyawan);

            // Random jumlah anggota 3–5
            $jumlahTim = rand(3, min(5, count($karyawan)));

            $anggotaTim = array_slice($karyawan, 0, $jumlahTim);

            foreach ($anggotaTim as $userId) {
                $inserted += DB::table('projek_tim')->insertOrIgnore([
                    'id_projek'       => $idProjek,
                    'id_user'         => $userId,
                    'dibuat_pada'     => $now,
                    'diperbarui_pada' => $now,
                ]);
            }
        }

        $this->command->info("✓ ProjekTimSeeder selesai");
        $this->command->info("  - Total anggota tim ditambahkan: {$inserted}");
    }
}
