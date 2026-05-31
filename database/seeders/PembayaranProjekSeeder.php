<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranProjekSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | LOGIKA PERHITUNGAN
        |--------------------------------------------------------------------------
        | terbayar = nominal_projek - sisa_tanggungan
        |
        | id_petugas  : 2  = PM pertama  (projek 1–12)
        |               23 = PM kedua    (projek 13–24)
        |
        | Metode pembayaran (sesuai MetodePembayaranSeeder):
        |   1 = Bank BCA  | 2 = Bank Mandiri | 3 = Bank BRI
        |   4 = DANA      | 5 = OVO          | 6 = GoPay
        |   7 = ShopeePay | 8 = Cash
        |
        | Pola pembayaran yang dipakai:
        |   - Projek kecil  (< 5 jt)  : 1 termin atau 2 termin
        |   - Projek sedang (5–15 jt) : 2 termin (DP 50% + pelunasan/cicilan)
        |   - Projek besar  (> 15 jt) : 3 termin (DP 30% + cicilan + pelunasan/terakhir)
        |   - Sisa tanggungan > 0     : belum ada termin pelunasan terakhir
        |--------------------------------------------------------------------------
        */

        DB::table('pembayaran_projek')->insert([

            // =====================================================================
            // PROJEK 1 — Website Company Profile PT Digital Nusantara
            // nominal: 12.000.000 | sisa: 0 | TERBAYAR: 12.000.000 (LUNAS)
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0001',
                'id_projek'            => 1,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 4000000.00,
                'tanggal_bayar'        => '2025-01-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0002',
                'id_projek'            => 1,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2025-02-05',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0003',
                'id_projek'            => 1,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 3000000.00,
                'tanggal_bayar'        => '2025-03-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P1: 4.000.000 + 5.000.000 + 3.000.000 = 12.000.000 ✓

            // =====================================================================
            // PROJEK 2 — SEO Website Corporate PT Digital Nusantara
            // nominal: 5.000.000 | sisa: 2.000.000 | TERBAYAR: 3.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0004',
                'id_projek'            => 2,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 2500000.00,
                'tanggal_bayar'        => '2026-02-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0005',
                'id_projek'            => 2,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 500000.00,
                'tanggal_bayar'        => '2026-03-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P2: 2.500.000 + 500.000 = 3.000.000 ✓ (sisa 2.000.000 belum dibayar)

            // =====================================================================
            // PROJEK 3 — Landing Page CV Kreasi Media
            // nominal: 4.500.000 | sisa: 0 | TERBAYAR: 4.500.000 (LUNAS)
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0006',
                'id_projek'            => 3,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 4, // DANA
                'jumlah_bayar'         => 2000000.00,
                'tanggal_bayar'        => '2025-02-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0007',
                'id_projek'            => 3,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 4, // DANA
                'jumlah_bayar'         => 2500000.00,
                'tanggal_bayar'        => '2025-03-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P3: 2.000.000 + 2.500.000 = 4.500.000 ✓

            // =====================================================================
            // PROJEK 4 — Social Media Management Kreasi Media
            // nominal: 3.500.000 | sisa: 1.500.000 | TERBAYAR: 2.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0008',
                'id_projek'            => 4,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 6, // GoPay
                'jumlah_bayar'         => 2000000.00,
                'tanggal_bayar'        => '2026-01-05',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P4: 2.000.000 ✓ (sisa 1.500.000 belum dibayar)

            // =====================================================================
            // PROJEK 5 — Sistem POS PT Solusi Teknologi
            // nominal: 25.000.000 | sisa: 10.000.000 | TERBAYAR: 15.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0009',
                'id_projek'            => 5,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 7500000.00,
                'tanggal_bayar'        => '2025-06-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0010',
                'id_projek'            => 5,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2025-07-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0011',
                'id_projek'            => 5,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 2500000.00,
                'tanggal_bayar'        => '2025-08-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P5: 7.500.000 + 5.000.000 + 2.500.000 = 15.000.000 ✓ (sisa 10.000.000)

            // =====================================================================
            // PROJEK 6 — Mobile App Customer Service
            // nominal: 32.000.000 | sisa: 12.000.000 | TERBAYAR: 20.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0012',
                'id_projek'            => 6,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 10000000.00,
                'tanggal_bayar'        => '2026-03-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0013',
                'id_projek'            => 6,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 7000000.00,
                'tanggal_bayar'        => '2026-04-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0014',
                'id_projek'            => 6,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 3000000.00,
                'tanggal_bayar'        => '2026-05-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P6: 10.000.000 + 7.000.000 + 3.000.000 = 20.000.000 ✓ (sisa 12.000.000)

            // =====================================================================
            // PROJEK 7 — Website Sekolah PT Inovasi Kreatif
            // nominal: 15.000.000 | sisa: 0 | TERBAYAR: 15.000.000 (LUNAS)
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0015',
                'id_projek'            => 7,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 3, // Bank BRI
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2025-04-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0016',
                'id_projek'            => 7,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 3, // Bank BRI
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2025-05-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0017',
                'id_projek'            => 7,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 3, // Bank BRI
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2025-06-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P7: 5.000.000 + 5.000.000 + 5.000.000 = 15.000.000 ✓

            // =====================================================================
            // PROJEK 8 — Digital Marketing PT Maju Bersama
            // nominal: 7.000.000 | sisa: 3.000.000 | TERBAYAR: 4.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0018',
                'id_projek'            => 8,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 5, // OVO
                'jumlah_bayar'         => 2500000.00,
                'tanggal_bayar'        => '2026-01-12',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0019',
                'id_projek'            => 8,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 5, // OVO
                'jumlah_bayar'         => 1500000.00,
                'tanggal_bayar'        => '2026-02-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P8: 2.500.000 + 1.500.000 = 4.000.000 ✓ (sisa 3.000.000)

            // =====================================================================
            // PROJEK 9 — Website UMKM PT Global Media
            // nominal: 8.000.000 | sisa: 0 | TERBAYAR: 8.000.000 (LUNAS)
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0020',
                'id_projek'            => 9,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 8, // Cash
                'jumlah_bayar'         => 4000000.00,
                'tanggal_bayar'        => '2025-03-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0021',
                'id_projek'            => 9,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 8, // Cash
                'jumlah_bayar'         => 4000000.00,
                'tanggal_bayar'        => '2025-04-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P9: 4.000.000 + 4.000.000 = 8.000.000 ✓

            // =====================================================================
            // PROJEK 10 — Hosting dan Deployment Website UMKM
            // nominal: 2.500.000 | sisa: 0 | TERBAYAR: 2.500.000 (LUNAS)
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0022',
                'id_projek'            => 10,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 8, // Cash
                'jumlah_bayar'         => 2500000.00,
                'tanggal_bayar'        => '2025-04-21',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P10: 2.500.000 ✓ (lunas sekaligus)

            // =====================================================================
            // PROJEK 11 — Aplikasi Absensi Mobile Nusantara Tech
            // nominal: 28.000.000 | sisa: 8.000.000 | TERBAYAR: 20.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0023',
                'id_projek'            => 11,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 8000000.00,
                'tanggal_bayar'        => '2026-02-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0024',
                'id_projek'            => 11,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 7000000.00,
                'tanggal_bayar'        => '2026-03-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0025',
                'id_projek'            => 11,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2026-04-25',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P11: 8.000.000 + 7.000.000 + 5.000.000 = 20.000.000 ✓ (sisa 8.000.000)

            // =====================================================================
            // PROJEK 12 — SEO Website PT Cipta Karya Digital
            // nominal: 6.000.000 | sisa: 2.500.000 | TERBAYAR: 3.500.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0026',
                'id_projek'            => 12,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 7, // ShopeePay
                'jumlah_bayar'         => 2000000.00,
                'tanggal_bayar'        => '2026-03-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0027',
                'id_projek'            => 12,
                'id_petugas'           => 2,
                'id_metode_pembayaran' => 7, // ShopeePay
                'jumlah_bayar'         => 1500000.00,
                'tanggal_bayar'        => '2026-04-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P12: 2.000.000 + 1.500.000 = 3.500.000 ✓ (sisa 2.500.000)

            // =====================================================================
            // PROJEK 13 — Company Profile PT Mitra Informatika
            // nominal: 10.000.000 | sisa: 0 | TERBAYAR: 10.000.000 (LUNAS)
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0028',
                'id_projek'            => 13,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2025-07-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0029',
                'id_projek'            => 13,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 3000000.00,
                'tanggal_bayar'        => '2025-08-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0030',
                'id_projek'            => 13,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 2000000.00,
                'tanggal_bayar'        => '2025-09-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P13: 5.000.000 + 3.000.000 + 2.000.000 = 10.000.000 ✓

            // =====================================================================
            // PROJEK 14 — Social Media Management Vision Creative
            // nominal: 4.000.000 | sisa: 1.000.000 | TERBAYAR: 3.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0031',
                'id_projek'            => 14,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 6, // GoPay
                'jumlah_bayar'         => 2000000.00,
                'tanggal_bayar'        => '2026-04-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0032',
                'id_projek'            => 14,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 6, // GoPay
                'jumlah_bayar'         => 1000000.00,
                'tanggal_bayar'        => '2026-05-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P14: 2.000.000 + 1.000.000 = 3.000.000 ✓ (sisa 1.000.000)

            // =====================================================================
            // PROJEK 15 — Maintenance Sistem Internal
            // nominal: 5.000.000 | sisa: 1.500.000 | TERBAYAR: 3.500.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0033',
                'id_projek'            => 15,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 3, // Bank BRI
                'jumlah_bayar'         => 2000000.00,
                'tanggal_bayar'        => '2026-01-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0034',
                'id_projek'            => 15,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 3, // Bank BRI
                'jumlah_bayar'         => 1500000.00,
                'tanggal_bayar'        => '2026-02-28',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P15: 2.000.000 + 1.500.000 = 3.500.000 ✓ (sisa 1.500.000)

            // =====================================================================
            // PROJEK 16 — Landing Page PT Smart Digital
            // nominal: 5.500.000 | sisa: 0 | TERBAYAR: 5.500.000 (LUNAS)
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0035',
                'id_projek'            => 16,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 4, // DANA
                'jumlah_bayar'         => 3000000.00,
                'tanggal_bayar'        => '2025-09-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0036',
                'id_projek'            => 16,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 4, // DANA
                'jumlah_bayar'         => 2500000.00,
                'tanggal_bayar'        => '2025-10-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P16: 3.000.000 + 2.500.000 = 5.500.000 ✓

            // =====================================================================
            // PROJEK 17 — Branding dan Website Creative Studio
            // nominal: 18.000.000 | sisa: 5.000.000 | TERBAYAR: 13.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0037',
                'id_projek'            => 17,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2026-02-05',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0038',
                'id_projek'            => 17,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2026-03-05',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0039',
                'id_projek'            => 17,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 3000000.00,
                'tanggal_bayar'        => '2026-04-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P17: 5.000.000 + 5.000.000 + 3.000.000 = 13.000.000 ✓ (sisa 5.000.000)

            // =====================================================================
            // PROJEK 18 — Google Ads Campaign Media Inovatif
            // nominal: 6.500.000 | sisa: 2.500.000 | TERBAYAR: 4.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0040',
                'id_projek'            => 18,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 5, // OVO
                'jumlah_bayar'         => 2500000.00,
                'tanggal_bayar'        => '2026-03-12',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0041',
                'id_projek'            => 18,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 5, // OVO
                'jumlah_bayar'         => 1500000.00,
                'tanggal_bayar'        => '2026-04-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P18: 2.500.000 + 1.500.000 = 4.000.000 ✓ (sisa 2.500.000)

            // =====================================================================
            // PROJEK 19 — E-Commerce PT Future Technology
            // nominal: 35.000.000 | sisa: 12.000.000 | TERBAYAR: 23.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0042',
                'id_projek'            => 19,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 10000000.00,
                'tanggal_bayar'        => '2025-11-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0043',
                'id_projek'            => 19,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 8000000.00,
                'tanggal_bayar'        => '2025-12-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0044',
                'id_projek'            => 19,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2026-01-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P19: 10.000.000 + 8.000.000 + 5.000.000 = 23.000.000 ✓ (sisa 12.000.000)

            // =====================================================================
            // PROJEK 20 — Mobile App Booking PT Teknologi Hebat
            // nominal: 40.000.000 | sisa: 15.000.000 | TERBAYAR: 25.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0045',
                'id_projek'            => 20,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 12000000.00,
                'tanggal_bayar'        => '2026-01-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0046',
                'id_projek'            => 20,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 8000000.00,
                'tanggal_bayar'        => '2026-02-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0047',
                'id_projek'            => 20,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 3, // Bank BRI
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2026-03-25',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P20: 12.000.000 + 8.000.000 + 5.000.000 = 25.000.000 ✓ (sisa 15.000.000)

            // =====================================================================
            // PROJEK 21 — Website Pemerintahan PT Berkah Digital
            // nominal: 45.000.000 | sisa: 20.000.000 | TERBAYAR: 25.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0048',
                'id_projek'            => 21,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 13500000.00,
                'tanggal_bayar'        => '2025-08-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0049',
                'id_projek'            => 21,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 7000000.00,
                'tanggal_bayar'        => '2025-09-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0050',
                'id_projek'            => 21,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 4500000.00,
                'tanggal_bayar'        => '2025-10-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P21: 13.500.000 + 7.000.000 + 4.500.000 = 25.000.000 ✓ (sisa 20.000.000)

            // =====================================================================
            // PROJEK 22 — SEO dan Maintenance PT Prima Teknologi
            // nominal: 8.500.000 | sisa: 3.000.000 | TERBAYAR: 5.500.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0051',
                'id_projek'            => 22,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 4, // DANA
                'jumlah_bayar'         => 3000000.00,
                'tanggal_bayar'        => '2026-02-10',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0052',
                'id_projek'            => 22,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 4, // DANA
                'jumlah_bayar'         => 2500000.00,
                'tanggal_bayar'        => '2026-03-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P22: 3.000.000 + 2.500.000 = 5.500.000 ✓ (sisa 3.000.000)

            // =====================================================================
            // PROJEK 23 — Sistem Custom Inventory
            // nominal: 22.000.000 | sisa: 7.000.000 | TERBAYAR: 15.000.000
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0053',
                'id_projek'            => 23,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 6600000.00,
                'tanggal_bayar'        => '2026-03-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0054',
                'id_projek'            => 23,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 1, // Bank BCA
                'jumlah_bayar'         => 5000000.00,
                'tanggal_bayar'        => '2026-04-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0055',
                'id_projek'            => 23,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 2, // Bank Mandiri
                'jumlah_bayar'         => 3400000.00,
                'tanggal_bayar'        => '2026-05-15',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P23: 6.600.000 + 5.000.000 + 3.400.000 = 15.000.000 ✓ (sisa 7.000.000)

            // =====================================================================
            // PROJEK 24 — Deployment dan Hosting Infinity Solution
            // nominal: 4.000.000 | sisa: 0 | TERBAYAR: 4.000.000 (LUNAS)
            // =====================================================================
            [
                'kode_pembayaran'      => 'PAY-0056',
                'id_projek'            => 24,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 8, // Cash
                'jumlah_bayar'         => 2000000.00,
                'tanggal_bayar'        => '2025-12-01',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            [
                'kode_pembayaran'      => 'PAY-0057',
                'id_projek'            => 24,
                'id_petugas'           => 23,
                'id_metode_pembayaran' => 8, // Cash
                'jumlah_bayar'         => 2000000.00,
                'tanggal_bayar'        => '2025-12-20',
                'bukti_bayar'          => null,
                'status'               => 'valid',
                'dibuat_pada'          => $now,
                'diperbarui_pada'      => $now,
            ],
            // TOTAL P24: 2.000.000 + 2.000.000 = 4.000.000 ✓
        ]);
    }
}
