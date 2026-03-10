<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranProjekSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // ambil admin
        $admin = DB::table('users')
            ->where('role', 'admin')
            ->first();

        // ambil metode pembayaran
        $metodeList = DB::table('metode_pembayaran')
            ->pluck('id_metode_pembayaran')
            ->toArray();

        // ambil semua projek
        $projekList = DB::table('projek')->get();

        $counter = 1;

        foreach ($projekList as $projek) {

            $totalDibayar = $projek->nominal_projek - $projek->sisa_tanggungan;

            if ($totalDibayar <= 0) {
                continue;
            }

            // buat 2–3 termin pembayaran
            $terminCount = rand(2, 3);

            if ($terminCount == 3) {
                $termin = [
                    $totalDibayar * 0.25,
                    $totalDibayar * 0.50,
                    $totalDibayar * 0.25
                ];
            } else {
                $termin = [
                    $totalDibayar * 0.50,
                    $totalDibayar * 0.50
                ];
            }

            foreach ($termin as $jumlah) {

                $tanggal = now()->subDays(rand(10, 60));

                $kodePembayaran =
                    'PAY-' .
                    $tanggal->format('Ymd') . '-' .
                    str_pad($counter, 3, '0', STR_PAD_LEFT);

                DB::table('pembayaran_projek')->insert([
                    'kode_pembayaran' => $kodePembayaran,
                    'id_projek' => $projek->id_projek,
                    'id_petugas' => $admin->id_user,
                    'id_metode_pembayaran' => $metodeList[array_rand($metodeList)],
                    'jumlah_bayar' => round($jumlah, 2),
                    'tanggal_bayar' => $tanggal,
                    'bukti_bayar' => 'bukti_transfer.jpg',
                    'status' => 'valid',
                    'dibuat_pada' => $now,
                    'diperbarui_pada' => $now,
                ]);

                $counter++;
            }
        }

        $this->command->info("Seeder pembayaran projek berhasil dibuat.");
    }
}
