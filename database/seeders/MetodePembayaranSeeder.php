<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodePembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('metode_pembayaran')->insert([
            [
                'nama_metode'  => 'Transfer BCA',
                'deskripsi'    => 'No Rekening: 1234567890 a.n PT Contoh Digital',
                'status'       => 'aktif',
                'dibuat_pada'  => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode'  => 'Transfer BNI',
                'deskripsi'    => 'No Rekening: 0987654321 a.n PT Contoh Digital',
                'status'       => 'aktif',
                'dibuat_pada'  => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode'  => 'Transfer BRI',
                'deskripsi'    => 'No Rekening: 1122334455 a.n PT Contoh Digital',
                'status'       => 'aktif',
                'dibuat_pada'  => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode'  => 'Transfer Mandiri',
                'deskripsi'    => 'No Rekening: 5566778899 a.n PT Contoh Digital',
                'status'       => 'aktif',
                'dibuat_pada'  => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode'  => 'OVO',
                'deskripsi'    => 'No HP: 081234567890',
                'status'       => 'aktif',
                'dibuat_pada'  => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode'  => 'DANA',
                'deskripsi'    => 'No HP: 081234567890',
                'status'       => 'aktif',
                'dibuat_pada'  => $now,
                'diperbarui_pada' => $now,
            ],
        ]);
    }
}
