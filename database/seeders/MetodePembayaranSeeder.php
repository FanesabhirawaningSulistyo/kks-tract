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
                'nama_metode' => 'Bank BCA',
                'deskripsi' => 'No Rek: 1234567890 a/n PT Kawan Kita Solusindo',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode' => 'Bank Mandiri',
                'deskripsi' => 'No Rek: 0987654321 a/n PT Kawan Kita Solusindo',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode' => 'Bank BRI',
                'deskripsi' => 'No Rek: 1122334455 a/n PT Kawan Kita Solusindo',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode' => 'DANA',
                'deskripsi' => 'No DANA: 081234567890 a/n PT Kawan Kita Solusindo',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode' => 'OVO',
                'deskripsi' => 'No OVO: 081298765432 a/n PT Kawan Kita Solusindo',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode' => 'GoPay',
                'deskripsi' => 'No GoPay: 081355667788 a/n PT Kawan Kita Solusindo',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode' => 'ShopeePay',
                'deskripsi' => 'No ShopeePay: 081377889900 a/n PT Kawan Kita Solusindo',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_metode' => 'Cash',
                'deskripsi' => 'Pembayaran tunai langsung ke PT Kawan Kita Solusindo (a/n kasir/admin keuangan)',
                'status' => 'aktif',
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
        ]);
    }
}
