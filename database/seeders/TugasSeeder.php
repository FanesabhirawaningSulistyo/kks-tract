<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TugasSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil ID projek dan pegawai
        $projekIds = DB::table('projek')->pluck('id_projek')->toArray();
        $pegawaiIds = DB::table('users')->where('role', 'pegawai')->pluck('id_user')->toArray();

        // Pastikan data ada sebelum di-seed
        if (empty($projekIds) || empty($pegawaiIds)) {
            $this->command->warn('⚠️ Tidak ada data projek atau pegawai. Seeder dilewati.');
            return;
        }

        $jumlahTugas = rand(15, 18);

        for ($i = 1; $i <= $jumlahTugas; $i++) {
            $level = $faker->randomElement(['mudah', 'medium', 'susah']);
            $weight = match ($level) {
                'mudah'  => 1,
                'medium' => 2,
                'susah'  => 3,
            };

            DB::table('tugas')->insert([
                'id_projek'         => $faker->randomElement($projekIds),
                'judul_tugas'       => ucfirst($faker->sentence(3)),
                'deskripsi_tugas'   => $faker->paragraph(2),
                'level'             => $level,
                'weight'            => $weight,
                'penanggung_jawab'  => $faker->randomElement($pegawaiIds),
                'status'            => $faker->randomElement(['draft', 'publis', 'progres', 'done']),
                'tenggat_waktu'     => Carbon::now()->addDays(rand(7, 30))->format('Y-m-d'),
                'dibuat_pada'       => now(),
                'diubah_pada'       => now(),
            ]);
        }

        $this->command->info("✅ Seeder tugas berhasil menambahkan {$jumlahTugas} data tugas.");
    }
}
