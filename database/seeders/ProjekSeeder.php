<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjekSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('projek')->insert([
            // -------------------------------------------------------
            // Projek PT Digital Nusantara (id_perusahaan: 1) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 1,
                'id_perusahaan'      => 1,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Website Company Profile PT Digital Nusantara',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Pembuatan website company profile modern',
                'status'             => 'selesai',
                'nominal_projek'     => 12000000.00,
                'sisa_tanggungan'    => 0.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-01-10',
                'tanggal_selesai'    => '2025-03-10',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Digital Nusantara (id_perusahaan: 1) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 2,
                'id_perusahaan'      => 1,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'SEO Website Corporate PT Digital Nusantara',
                'id_kategori_projek' => 3,
                'deskripsi'          => 'Optimasi SEO dan keyword perusahaan',
                'status'             => 'aktif',
                'nominal_projek'     => 5000000.00,
                'sisa_tanggungan'    => 2000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-02-01',
                'tanggal_selesai'    => '2026-05-01',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek CV Kreasi Media (id_perusahaan: 2) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 3,
                'id_perusahaan'      => 2,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Landing Page CV Kreasi Media',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Landing page promosi jasa digital',
                'status'             => 'selesai',
                'nominal_projek'     => 4500000.00,
                'sisa_tanggungan'    => 0.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-02-15',
                'tanggal_selesai'    => '2025-03-20',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek CV Kreasi Media (id_perusahaan: 2) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 4,
                'id_perusahaan'      => 2,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Social Media Management Kreasi Media',
                'id_kategori_projek' => 5,
                'deskripsi'          => 'Pengelolaan Instagram dan TikTok bisnis',
                'status'             => 'in_progress',
                'nominal_projek'     => 3500000.00,
                'sisa_tanggungan'    => 1500000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-01-05',
                'tanggal_selesai'    => '2026-03-05',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Solusi Teknologi (id_perusahaan: 3) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 5,
                'id_perusahaan'      => 3,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Sistem POS PT Solusi Teknologi',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Sistem kasir berbasis web untuk toko',
                'status'             => 'aktif',
                'nominal_projek'     => 25000000.00,
                'sisa_tanggungan'    => 10000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-06-01',
                'tanggal_selesai'    => '2025-08-30',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Solusi Teknologi (id_perusahaan: 3) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 6,
                'id_perusahaan'      => 3,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Mobile App Customer Service',
                'id_kategori_projek' => 2,
                'deskripsi'          => 'Aplikasi mobile layanan pelanggan Android',
                'status'             => 'in_progress',
                'nominal_projek'     => 32000000.00,
                'sisa_tanggungan'    => 12000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-03-10',
                'tanggal_selesai'    => '2026-06-10',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Inovasi Kreatif (id_perusahaan: 4) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 7,
                'id_perusahaan'      => 4,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Website Sekolah PT Inovasi Kreatif',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Website profil sekolah dan PPDB online',
                'status'             => 'selesai',
                'nominal_projek'     => 15000000.00,
                'sisa_tanggungan'    => 0.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-04-01',
                'tanggal_selesai'    => '2025-06-01',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Maju Bersama (id_perusahaan: 5) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 8,
                'id_perusahaan'      => 5,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Digital Marketing PT Maju Bersama',
                'id_kategori_projek' => 4,
                'deskripsi'          => 'Campaign Meta Ads dan Google Ads',
                'status'             => 'aktif',
                'nominal_projek'     => 7000000.00,
                'sisa_tanggungan'    => 3000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-01-12',
                'tanggal_selesai'    => '2026-04-12',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Global Media (id_perusahaan: 6) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 9,
                'id_perusahaan'      => 6,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Website UMKM PT Global Media',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Website katalog produk UMKM',
                'status'             => 'selesai',
                'nominal_projek'     => 8000000.00,
                'sisa_tanggungan'    => 0.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-03-01',
                'tanggal_selesai'    => '2025-04-20',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Global Media (id_perusahaan: 6) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 10,
                'id_perusahaan'      => 6,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Hosting dan Deployment Website UMKM',
                'id_kategori_projek' => 7,
                'deskripsi'          => 'Setup hosting dan deployment website',
                'status'             => 'selesai',
                'nominal_projek'     => 2500000.00,
                'sisa_tanggungan'    => 0.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-04-21',
                'tanggal_selesai'    => '2025-05-01',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Nusantara Tech (id_perusahaan: 7) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 11,
                'id_perusahaan'      => 7,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'Aplikasi Absensi Mobile Nusantara Tech',
                'id_kategori_projek' => 2,
                'deskripsi'          => 'Aplikasi absensi Android dengan GPS',
                'status'             => 'aktif',
                'nominal_projek'     => 28000000.00,
                'sisa_tanggungan'    => 8000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-02-15',
                'tanggal_selesai'    => '2026-05-15',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Cipta Karya Digital (id_perusahaan: 8) — PM: 2
            // -------------------------------------------------------
            [
                'id_projek'          => 12,
                'id_perusahaan'      => 8,
                'dibuat_oleh'        => 2,
                'nama_projek'        => 'SEO Website PT Cipta Karya Digital',
                'id_kategori_projek' => 3,
                'deskripsi'          => 'Optimasi SEO dan peningkatan traffic',
                'status'             => 'in_progress',
                'nominal_projek'     => 6000000.00,
                'sisa_tanggungan'    => 2500000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-03-01',
                'tanggal_selesai'    => '2026-06-01',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],

            // -------------------------------------------------------
            // Projek PT Mitra Informatika (id_perusahaan: 9) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 13,
                'id_perusahaan'      => 9,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Company Profile PT Mitra Informatika',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Website company profile responsive',
                'status'             => 'selesai',
                'nominal_projek'     => 10000000.00,
                'sisa_tanggungan'    => 0.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-07-10',
                'tanggal_selesai'    => '2025-09-10',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Vision Creative (id_perusahaan: 10) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 14,
                'id_perusahaan'      => 10,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Social Media Management Vision Creative',
                'id_kategori_projek' => 5,
                'deskripsi'          => 'Pengelolaan media sosial perusahaan',
                'status'             => 'aktif',
                'nominal_projek'     => 4000000.00,
                'sisa_tanggungan'    => 1000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-04-01',
                'tanggal_selesai'    => '2026-06-30',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Data Solution (id_perusahaan: 11) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 15,
                'id_perusahaan'      => 11,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Maintenance Sistem Internal',
                'id_kategori_projek' => 6,
                'deskripsi'          => 'Maintenance dan perbaikan bug sistem',
                'status'             => 'aktif',
                'nominal_projek'     => 5000000.00,
                'sisa_tanggungan'    => 1500000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-01-20',
                'tanggal_selesai'    => '2026-04-20',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Smart Digital (id_perusahaan: 12) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 16,
                'id_perusahaan'      => 12,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Landing Page PT Smart Digital',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Landing page promosi layanan digital',
                'status'             => 'selesai',
                'nominal_projek'     => 5500000.00,
                'sisa_tanggungan'    => 0.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-09-01',
                'tanggal_selesai'    => '2025-10-01',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Creative Studio (id_perusahaan: 13) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 17,
                'id_perusahaan'      => 13,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Branding dan Website Creative Studio',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Website company profile dan branding',
                'status'             => 'aktif',
                'nominal_projek'     => 18000000.00,
                'sisa_tanggungan'    => 5000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-02-05',
                'tanggal_selesai'    => '2026-05-05',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Media Inovatif (id_perusahaan: 14) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 18,
                'id_perusahaan'      => 14,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Google Ads Campaign Media Inovatif',
                'id_kategori_projek' => 4,
                'deskripsi'          => 'Campaign digital marketing perusahaan',
                'status'             => 'in_progress',
                'nominal_projek'     => 6500000.00,
                'sisa_tanggungan'    => 2500000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-03-12',
                'tanggal_selesai'    => '2026-05-12',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Future Technology (id_perusahaan: 15) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 19,
                'id_perusahaan'      => 15,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'E-Commerce PT Future Technology',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Website toko online lengkap pembayaran',
                'status'             => 'aktif',
                'nominal_projek'     => 35000000.00,
                'sisa_tanggungan'    => 12000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-11-01',
                'tanggal_selesai'    => '2026-02-01',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Teknologi Hebat (id_perusahaan: 16) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 20,
                'id_perusahaan'      => 16,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Mobile App Booking PT Teknologi Hebat',
                'id_kategori_projek' => 2,
                'deskripsi'          => 'Aplikasi booking service Android/iOS',
                'status'             => 'in_progress',
                'nominal_projek'     => 40000000.00,
                'sisa_tanggungan'    => 15000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-01-15',
                'tanggal_selesai'    => '2026-04-15',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Berkah Digital (id_perusahaan: 17) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 21,
                'id_perusahaan'      => 17,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Website Pemerintahan PT Berkah Digital',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Sistem informasi pemerintahan daerah',
                'status'             => 'aktif',
                'nominal_projek'     => 45000000.00,
                'sisa_tanggungan'    => 20000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-08-01',
                'tanggal_selesai'    => '2025-11-01',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Prima Teknologi (id_perusahaan: 18) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 22,
                'id_perusahaan'      => 18,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'SEO dan Maintenance PT Prima Teknologi',
                'id_kategori_projek' => 3,
                'deskripsi'          => 'Optimasi SEO serta maintenance website',
                'status'             => 'aktif',
                'nominal_projek'     => 8500000.00,
                'sisa_tanggungan'    => 3000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-02-10',
                'tanggal_selesai'    => '2026-05-10',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Kreatif Nusantara (id_perusahaan: 19) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 23,
                'id_perusahaan'      => 19,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Sistem Custom Inventory',
                'id_kategori_projek' => 1,
                'deskripsi'          => 'Sistem inventory custom berbasis web',
                'status'             => 'in_progress',
                'nominal_projek'     => 22000000.00,
                'sisa_tanggungan'    => 7000000.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2026-03-20',
                'tanggal_selesai'    => '2026-06-20',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
            // -------------------------------------------------------
            // Projek PT Infinity Solution (id_perusahaan: 20) — PM: 23
            // -------------------------------------------------------
            [
                'id_projek'          => 24,
                'id_perusahaan'      => 20,
                'dibuat_oleh'        => 23,
                'nama_projek'        => 'Deployment dan Hosting Infinity Solution',
                'id_kategori_projek' => 7,
                'deskripsi'          => 'Konfigurasi VPS dan deployment aplikasi',
                'status'             => 'selesai',
                'nominal_projek'     => 4000000.00,
                'sisa_tanggungan'    => 0.00,
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => '2025-12-01',
                'tanggal_selesai'    => '2025-12-20',
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ],
        ]);

        // Reset auto increment
        DB::statement('ALTER TABLE projek AUTO_INCREMENT = 25;');
    }
}
