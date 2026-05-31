<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('tugas')->insert([

            // =========================================================
            // PROJEK 1 — Website Company Profile PT Digital Nusantara
            // Tanggal: 2025-01-10 s/d 2025-03-10 | Status: SELESAI
            // Tim: id_tim 1(WebDev), 2(WebDev), 3(UI/UX), 4(QA)
            // Semua tugas sudah approved, ada yg telat & tepat waktu
            // =========================================================

            // --- Tugas 1 ---
            [
                'id_projek'       => 1, 'id_tim' => 3,
                'judul_tugas'     => 'Desain Wireframe Halaman Utama',
                'deskripsi_tugas' => 'Buat wireframe layout homepage company profile',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-01-10', 'tenggat_waktu' => '2025-01-17',
                'tanggal_selesai' => '2025-01-16',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 2 ---
            [
                'id_projek'       => 1, 'id_tim' => 3,
                'judul_tugas'     => 'Desain UI Halaman About Us',
                'deskripsi_tugas' => 'Buat desain UI halaman tentang perusahaan',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-01-17', 'tenggat_waktu' => '2025-01-24',
                'tanggal_selesai' => '2025-01-26', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 3 ---
            [
                'id_projek'       => 1, 'id_tim' => 3,
                'judul_tugas'     => 'Desain UI Halaman Layanan',
                'deskripsi_tugas' => 'Buat desain halaman daftar layanan perusahaan',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-01-24', 'tenggat_waktu' => '2025-01-31',
                'tanggal_selesai' => '2025-01-31',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 4 ---
            [
                'id_projek'       => 1, 'id_tim' => 3,
                'judul_tugas'     => 'Desain UI Halaman Kontak',
                'deskripsi_tugas' => 'Buat desain halaman kontak dan form',
                'level'           => 'mudah', 'weight' => 1,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-01-31', 'tenggat_waktu' => '2025-02-05',
                'tanggal_selesai' => '2025-02-04',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 5 ---
            [
                'id_projek'       => 1, 'id_tim' => 1,
                'judul_tugas'     => 'Setup Project & Repository',
                'deskripsi_tugas' => 'Inisialisasi project Laravel dan setup Git repository',
                'level'           => 'mudah', 'weight' => 1,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-01-10', 'tenggat_waktu' => '2025-01-13',
                'tanggal_selesai' => '2025-01-13',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 6 ---
            [
                'id_projek'       => 1, 'id_tim' => 1,
                'judul_tugas'     => 'Implementasi Layout & Template',
                'deskripsi_tugas' => 'Implementasi template dasar dan layout utama website',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-01-13', 'tenggat_waktu' => '2025-01-24',
                'tanggal_selesai' => '2025-01-25', // telat 1 hari
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 7 ---
            [
                'id_projek'       => 1, 'id_tim' => 2,
                'judul_tugas'     => 'Implementasi Halaman Homepage',
                'deskripsi_tugas' => 'Coding halaman utama sesuai desain UI',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-01-27', 'tenggat_waktu' => '2025-02-07',
                'tanggal_selesai' => '2025-02-06',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 8 ---
            [
                'id_projek'       => 1, 'id_tim' => 1,
                'judul_tugas'     => 'Implementasi Halaman About Us',
                'deskripsi_tugas' => 'Coding halaman about us dan tim perusahaan',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-02-07', 'tenggat_waktu' => '2025-02-14',
                'tanggal_selesai' => '2025-02-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 9 ---
            [
                'id_projek'       => 1, 'id_tim' => 2,
                'judul_tugas'     => 'Implementasi Halaman Layanan',
                'deskripsi_tugas' => 'Coding halaman daftar layanan dan detail layanan',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-02-07', 'tenggat_waktu' => '2025-02-14',
                'tanggal_selesai' => '2025-02-17', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 10 ---
            [
                'id_projek'       => 1, 'id_tim' => 2,
                'judul_tugas'     => 'Implementasi Halaman Kontak & Form',
                'deskripsi_tugas' => 'Coding halaman kontak dengan form dan validasi',
                'level'           => 'medium', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-02-17', 'tenggat_waktu' => '2025-02-24',
                'tanggal_selesai' => '2025-02-23',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 11 ---
            [
                'id_projek'       => 1, 'id_tim' => 1,
                'judul_tugas'     => 'Integrasi CMS untuk Konten',
                'deskripsi_tugas' => 'Setup dan integrasi CMS untuk manajemen konten',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-02-17', 'tenggat_waktu' => '2025-02-28',
                'tanggal_selesai' => '2025-02-28',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 12 ---
            [
                'id_projek'       => 1, 'id_tim' => 4,
                'judul_tugas'     => 'Testing Fungsional Seluruh Halaman',
                'deskripsi_tugas' => 'Uji semua halaman dan fitur website',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-03-01', 'tenggat_waktu' => '2025-03-07',
                'tanggal_selesai' => '2025-03-07',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 13 ---
            [
                'id_projek'       => 1, 'id_tim' => 4,
                'judul_tugas'     => 'Bug Fixing Hasil Testing',
                'deskripsi_tugas' => 'Perbaiki semua bug yang ditemukan saat testing',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-03-07', 'tenggat_waktu' => '2025-03-10',
                'tanggal_selesai' => '2025-03-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 2 — SEO Website Corporate PT Digital Nusantara
            // Tanggal: 2026-02-01 s/d 2026-05-01 | Status: AKTIF
            // Tim: id_tim 5(SEO), 6(SEO), 7(Content Writer)
            // Bulan Feb-Mar: done/approved. Apr-Mei 2026: ada in_progress, review, revisi
            // =========================================================

            // --- Tugas 14 ---
            [
                'id_projek'       => 2, 'id_tim' => 5,
                'judul_tugas'     => 'Audit SEO Website Awal',
                'deskripsi_tugas' => 'Analisis kondisi SEO website saat ini',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-01', 'tenggat_waktu' => '2026-02-10',
                'tanggal_selesai' => '2026-02-09',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 15 ---
            [
                'id_projek'       => 2, 'id_tim' => 6,
                'judul_tugas'     => 'Riset Keyword Utama',
                'deskripsi_tugas' => 'Riset dan analisis keyword target perusahaan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-01', 'tenggat_waktu' => '2026-02-10',
                'tanggal_selesai' => '2026-02-12', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 16 ---
            [
                'id_projek'       => 2, 'id_tim' => 7,
                'judul_tugas'     => 'Penulisan Konten Halaman Utama',
                'deskripsi_tugas' => 'Tulis ulang konten homepage sesuai keyword target',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-13', 'tenggat_waktu' => '2026-02-24',
                'tanggal_selesai' => '2026-02-24',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 17 ---
            [
                'id_projek'       => 2, 'id_tim' => 5,
                'judul_tugas'     => 'Optimasi On-Page SEO',
                'deskripsi_tugas' => 'Optimasi meta title, meta description, heading, alt tag',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-24', 'tenggat_waktu' => '2026-03-07',
                'tanggal_selesai' => '2026-03-07',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 18 ---
            [
                'id_projek'       => 2, 'id_tim' => 6,
                'judul_tugas'     => 'Optimasi Kecepatan Website',
                'deskripsi_tugas' => 'Tingkatkan PageSpeed Score dan Core Web Vitals',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-07', 'tenggat_waktu' => '2026-03-21',
                'tanggal_selesai' => '2026-03-24', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 19 ---
            [
                'id_projek'       => 2, 'id_tim' => 7,
                'judul_tugas'     => 'Penulisan Artikel Blog #1',
                'deskripsi_tugas' => 'Tulis artikel SEO-friendly sesuai keyword target',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-24', 'tenggat_waktu' => '2026-04-01',
                'tanggal_selesai' => '2026-03-31',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 20 ---
            [
                'id_projek'       => 2, 'id_tim' => 5,
                'judul_tugas'     => 'Link Building External',
                'deskripsi_tugas' => 'Bangun backlink dari situs relevan dan berkualitas',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'In Progress',  'status_akhir' => null,
                'tanggal_mulai'   => '2026-04-01', 'tenggat_waktu' => '2026-04-20',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 21 ---
            [
                'id_projek'       => 2, 'id_tim' => 6,
                'judul_tugas'     => 'Monitoring Ranking Keyword',
                'deskripsi_tugas' => 'Pantau posisi keyword di Google Search Console',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-04-01', 'tenggat_waktu' => '2026-04-15',
                'tanggal_selesai' => '2026-04-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 22 ---
            [
                'id_projek'       => 2, 'id_tim' => 7,
                'judul_tugas'     => 'Penulisan Artikel Blog #2',
                'deskripsi_tugas' => 'Artikel SEO kedua untuk keyword long-tail',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'revisi',
                'tanggal_mulai'   => '2026-04-15', 'tenggat_waktu' => '2026-04-25',
                'tanggal_selesai' => '2026-04-24',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 23 ---
            [
                'id_projek'       => 2, 'id_tim' => 5,
                'judul_tugas'     => 'Laporan SEO Bulanan',
                'deskripsi_tugas' => 'Buat laporan performa SEO bulan April',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-04-25', 'tenggat_waktu' => '2026-05-01',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 3 — Landing Page CV Kreasi Media
            // Tanggal: 2025-02-15 s/d 2025-03-20 | Status: SELESAI
            // Tim: id_tim 8(WebDev), 9(UI/UX), 10(QA)
            // =========================================================

            // --- Tugas 24 ---
            [
                'id_projek'       => 3, 'id_tim' => 9,
                'judul_tugas'     => 'Desain Mockup Landing Page',
                'deskripsi_tugas' => 'Buat desain mockup landing page promosi',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-02-15', 'tenggat_waktu' => '2025-02-22',
                'tanggal_selesai' => '2025-02-21',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 25 ---
            [
                'id_projek'       => 3, 'id_tim' => 8,
                'judul_tugas'     => 'Coding Landing Page Section Hero',
                'deskripsi_tugas' => 'Implementasi section hero dengan animasi',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-02-22', 'tenggat_waktu' => '2025-03-01',
                'tanggal_selesai' => '2025-03-03', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 26 ---
            [
                'id_projek'       => 3, 'id_tim' => 8,
                'judul_tugas'     => 'Coding Section Fitur & CTA',
                'deskripsi_tugas' => 'Implementasi section fitur layanan dan call-to-action',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-03-03', 'tenggat_waktu' => '2025-03-10',
                'tanggal_selesai' => '2025-03-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 27 ---
            [
                'id_projek'       => 3, 'id_tim' => 8,
                'judul_tugas'     => 'Integrasi Form Lead & Responsif',
                'deskripsi_tugas' => 'Form submit lead dan responsif mobile/tablet',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-03-10', 'tenggat_waktu' => '2025-03-15',
                'tanggal_selesai' => '2025-03-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 28 ---
            [
                'id_projek'       => 3, 'id_tim' => 10,
                'judul_tugas'     => 'Testing & Bug Fix Landing Page',
                'deskripsi_tugas' => 'Uji responsivitas dan semua fungsi landing page',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-03-15', 'tenggat_waktu' => '2025-03-20',
                'tanggal_selesai' => '2025-03-20',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 4 — Social Media Management Kreasi Media
            // Tanggal: 2026-01-05 s/d 2026-03-05 | Status: IN_PROGRESS
            // Tim: id_tim 11(Sosmed), 12(Content Writer), 13(Graphic), 14(Content Creator)
            // Jan-Feb: done/approved. Maret-sekarang: ada in_progress, review, revisi
            // =========================================================

            // --- Tugas 29 ---
            [
                'id_projek'       => 4, 'id_tim' => 11,
                'judul_tugas'     => 'Audit Akun Media Sosial Existing',
                'deskripsi_tugas' => 'Analisis performa akun Instagram dan TikTok saat ini',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-05', 'tenggat_waktu' => '2026-01-12',
                'tanggal_selesai' => '2026-01-11',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 30 ---
            [
                'id_projek'       => 4, 'id_tim' => 12,
                'judul_tugas'     => 'Pembuatan Content Plan Bulan Januari',
                'deskripsi_tugas' => 'Susun jadwal dan topik konten untuk bulan Januari',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-05', 'tenggat_waktu' => '2026-01-10',
                'tanggal_selesai' => '2026-01-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 31 ---
            [
                'id_projek'       => 4, 'id_tim' => 13,
                'judul_tugas'     => 'Desain Template Feed Instagram',
                'deskripsi_tugas' => 'Buat template visual konsisten untuk feed Instagram',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-10', 'tenggat_waktu' => '2026-01-17',
                'tanggal_selesai' => '2026-01-20', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 32 ---
            [
                'id_projek'       => 4, 'id_tim' => 14,
                'judul_tugas'     => 'Produksi Konten Video TikTok #1',
                'deskripsi_tugas' => 'Buat 4 video konten TikTok untuk bulan Januari',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-17', 'tenggat_waktu' => '2026-01-31',
                'tanggal_selesai' => '2026-01-31',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 33 ---
            [
                'id_projek'       => 4, 'id_tim' => 11,
                'judul_tugas'     => 'Posting & Engagement Bulan Januari',
                'deskripsi_tugas' => 'Kelola posting harian dan balas komentar/DM',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-10', 'tenggat_waktu' => '2026-01-31',
                'tanggal_selesai' => '2026-01-31',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 34 ---
            [
                'id_projek'       => 4, 'id_tim' => 12,
                'judul_tugas'     => 'Penulisan Caption Konten Februari',
                'deskripsi_tugas' => 'Tulis caption menarik untuk semua konten Februari',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-01', 'tenggat_waktu' => '2026-02-10',
                'tanggal_selesai' => '2026-02-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 35 ---
            [
                'id_projek'       => 4, 'id_tim' => 13,
                'judul_tugas'     => 'Desain Konten Grafis Februari',
                'deskripsi_tugas' => 'Buat desain grafis untuk 12 postingan Februari',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-01', 'tenggat_waktu' => '2026-02-15',
                'tanggal_selesai' => '2026-02-17', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 36 ---
            [
                'id_projek'       => 4, 'id_tim' => 11,
                'judul_tugas'     => 'Laporan Analitik Bulan Februari',
                'deskripsi_tugas' => 'Buat laporan performa akun sosmed bulan Februari',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-02-25', 'tenggat_waktu' => '2026-03-01',
                'tanggal_selesai' => '2026-02-28',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 37 ---
            [
                'id_projek'       => 4, 'id_tim' => 14,
                'judul_tugas'     => 'Produksi Video TikTok Maret',
                'deskripsi_tugas' => 'Produksi 4 konten video TikTok untuk bulan Maret',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-03-01', 'tenggat_waktu' => '2026-03-05',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 5 — Sistem POS PT Solusi Teknologi
            // Tanggal: 2025-06-01 s/d 2025-08-30 | Status: AKTIF (masih berjalan)
            // Tim: 15(WebDev), 16(Fullstack), 17(Fullstack), 18(UI/UX), 19(QA)
            // Semua sudah selesai dan approved karena sudah lewat
            // =========================================================

            // --- Tugas 38 ---
            [
                'id_projek'       => 5, 'id_tim' => 18,
                'judul_tugas'     => 'Desain UI Dashboard POS',
                'deskripsi_tugas' => 'Buat desain UI dashboard kasir POS',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-06-01', 'tenggat_waktu' => '2025-06-10',
                'tanggal_selesai' => '2025-06-09',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 39 ---
            [
                'id_projek'       => 5, 'id_tim' => 15,
                'judul_tugas'     => 'Setup Database & Struktur Tabel',
                'deskripsi_tugas' => 'Desain dan buat struktur database sistem POS',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-06-01', 'tenggat_waktu' => '2025-06-08',
                'tanggal_selesai' => '2025-06-08',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 40 ---
            [
                'id_projek'       => 5, 'id_tim' => 16,
                'judul_tugas'     => 'Modul Manajemen Produk',
                'deskripsi_tugas' => 'Buat modul CRUD produk dan kategori',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-06-10', 'tenggat_waktu' => '2025-06-25',
                'tanggal_selesai' => '2025-06-28', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 41 ---
            [
                'id_projek'       => 5, 'id_tim' => 17,
                'judul_tugas'     => 'Modul Transaksi Penjualan',
                'deskripsi_tugas' => 'Buat modul proses transaksi dan cetak struk',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-06-25', 'tenggat_waktu' => '2025-07-15',
                'tanggal_selesai' => '2025-07-15',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 42 ---
            [
                'id_projek'       => 5, 'id_tim' => 16,
                'judul_tugas'     => 'Modul Manajemen Stok',
                'deskripsi_tugas' => 'Buat modul stok barang, masuk dan keluar',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-07-10', 'tenggat_waktu' => '2025-07-28',
                'tanggal_selesai' => '2025-07-30', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 43 ---
            [
                'id_projek'       => 5, 'id_tim' => 17,
                'judul_tugas'     => 'Modul Laporan Penjualan',
                'deskripsi_tugas' => 'Buat modul laporan harian, mingguan, bulanan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-07-28', 'tenggat_waktu' => '2025-08-10',
                'tanggal_selesai' => '2025-08-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 44 ---
            [
                'id_projek'       => 5, 'id_tim' => 19,
                'judul_tugas'     => 'Testing End-to-End Sistem POS',
                'deskripsi_tugas' => 'Uji seluruh alur transaksi dan fitur POS',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-08-10', 'tenggat_waktu' => '2025-08-25',
                'tanggal_selesai' => '2025-08-24',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 45 ---
            [
                'id_projek'       => 5, 'id_tim' => 15,
                'judul_tugas'     => 'Bug Fix & Optimasi Performa',
                'deskripsi_tugas' => 'Perbaiki bug dan optimasi query database',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-08-24', 'tenggat_waktu' => '2025-08-30',
                'tanggal_selesai' => '2025-08-30',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 6 — Mobile App Customer Service
            // Tanggal: 2026-03-10 s/d 2026-06-10 | Status: IN_PROGRESS
            // Tim: 20(Mobile), 21(Mobile), 22(UI/UX), 23(QA)
            // Mar-Apr: done/approved. Mei 2026: in_progress/review/revisi
            // =========================================================

            // --- Tugas 46 ---
            [
                'id_projek'       => 6, 'id_tim' => 22,
                'judul_tugas'     => 'Desain UI Aplikasi Customer Service',
                'deskripsi_tugas' => 'Buat desain UI/UX seluruh halaman aplikasi',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-10', 'tenggat_waktu' => '2026-03-24',
                'tanggal_selesai' => '2026-03-23',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 47 ---
            [
                'id_projek'       => 6, 'id_tim' => 20,
                'judul_tugas'     => 'Setup Project Android & Struktur Kode',
                'deskripsi_tugas' => 'Inisialisasi project Android, setup arsitektur MVVM',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-10', 'tenggat_waktu' => '2026-03-17',
                'tanggal_selesai' => '2026-03-17',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 48 ---
            [
                'id_projek'       => 6, 'id_tim' => 21,
                'judul_tugas'     => 'Modul Login & Autentikasi',
                'deskripsi_tugas' => 'Implementasi login, register, dan JWT auth',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-17', 'tenggat_waktu' => '2026-03-28',
                'tanggal_selesai' => '2026-03-31', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 49 ---
            [
                'id_projek'       => 6, 'id_tim' => 20,
                'judul_tugas'     => 'Modul Tiket & Live Chat',
                'deskripsi_tugas' => 'Implementasi fitur buat tiket dan live chat customer',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-31', 'tenggat_waktu' => '2026-04-20',
                'tanggal_selesai' => '2026-04-20',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 50 ---
            [
                'id_projek'       => 6, 'id_tim' => 21,
                'judul_tugas'     => 'Modul Notifikasi Push',
                'deskripsi_tugas' => 'Integrasi FCM untuk push notification',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-04-20', 'tenggat_waktu' => '2026-05-05',
                'tanggal_selesai' => '2026-05-04',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 51 ---
            [
                'id_projek'       => 6, 'id_tim' => 23,
                'judul_tugas'     => 'Testing Fungsional Aplikasi',
                'deskripsi_tugas' => 'Uji semua fitur aplikasi di device Android',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-05-05', 'tenggat_waktu' => '2026-05-20',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 52 ---
            [
                'id_projek'       => 6, 'id_tim' => 20,
                'judul_tugas'     => 'Modul Riwayat & Rating',
                'deskripsi_tugas' => 'Fitur riwayat tiket dan rating layanan customer',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'revisi',
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-15',
                'tanggal_selesai' => '2026-05-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 7 — Website Sekolah PT Inovasi Kreatif
            // Tanggal: 2025-04-01 s/d 2025-06-01 | Status: SELESAI
            // Tim: 24(WebDev), 25(WebDev), 26(UI/UX), 27(QA)
            // =========================================================

            // --- Tugas 53 ---
            [
                'id_projek'       => 7, 'id_tim' => 26,
                'judul_tugas'     => 'Desain UI Website Sekolah',
                'deskripsi_tugas' => 'Buat desain UI halaman utama, profil, dan PPDB',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-04-01', 'tenggat_waktu' => '2025-04-10',
                'tanggal_selesai' => '2025-04-09',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 54 ---
            [
                'id_projek'       => 7, 'id_tim' => 24,
                'judul_tugas'     => 'Implementasi Halaman Beranda & Profil',
                'deskripsi_tugas' => 'Coding halaman beranda dan profil sekolah',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-04-10', 'tenggat_waktu' => '2025-04-25',
                'tanggal_selesai' => '2025-04-28', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 55 ---
            [
                'id_projek'       => 7, 'id_tim' => 25,
                'judul_tugas'     => 'Modul PPDB Online',
                'deskripsi_tugas' => 'Buat sistem pendaftaran siswa baru online',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-04-25', 'tenggat_waktu' => '2025-05-15',
                'tanggal_selesai' => '2025-05-15',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 56 ---
            [
                'id_projek'       => 7, 'id_tim' => 24,
                'judul_tugas'     => 'Modul Berita & Pengumuman',
                'deskripsi_tugas' => 'Buat sistem manajemen berita dan pengumuman sekolah',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-05-15', 'tenggat_waktu' => '2025-05-25',
                'tanggal_selesai' => '2025-05-24',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 57 ---
            [
                'id_projek'       => 7, 'id_tim' => 27,
                'judul_tugas'     => 'UAT & Bug Fix Website Sekolah',
                'deskripsi_tugas' => 'User acceptance test dan perbaikan bug akhir',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-05-25', 'tenggat_waktu' => '2025-06-01',
                'tanggal_selesai' => '2025-06-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 8 — Digital Marketing PT Maju Bersama
            // Tanggal: 2026-01-12 s/d 2026-04-12 | Status: AKTIF
            // Tim: 28(Digimkt), 29(Sosmed), 30(Graphic), 31(Video Editor)
            // Jan-Mar: done/approved. Apr 2026: in_progress, review, revisi
            // =========================================================

            // --- Tugas 58 ---
            [
                'id_projek'       => 8, 'id_tim' => 28,
                'judul_tugas'     => 'Riset Target Audience & Kompetitor',
                'deskripsi_tugas' => 'Analisis target pasar dan kompetitor digital',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-12', 'tenggat_waktu' => '2026-01-20',
                'tanggal_selesai' => '2026-01-19',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 59 ---
            [
                'id_projek'       => 8, 'id_tim' => 30,
                'judul_tugas'     => 'Desain Materi Iklan Meta Ads',
                'deskripsi_tugas' => 'Buat creative banner dan visual untuk Meta Ads',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-20', 'tenggat_waktu' => '2026-02-01',
                'tanggal_selesai' => '2026-02-03', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 60 ---
            [
                'id_projek'       => 8, 'id_tim' => 31,
                'judul_tugas'     => 'Produksi Video Iklan',
                'deskripsi_tugas' => 'Produksi video iklan untuk campaign digital',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-20', 'tenggat_waktu' => '2026-02-05',
                'tanggal_selesai' => '2026-02-05',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 61 ---
            [
                'id_projek'       => 8, 'id_tim' => 28,
                'judul_tugas'     => 'Setup Campaign Meta Ads',
                'deskripsi_tugas' => 'Buat dan konfigurasi campaign Facebook & Instagram Ads',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-05', 'tenggat_waktu' => '2026-02-10',
                'tanggal_selesai' => '2026-02-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 62 ---
            [
                'id_projek'       => 8, 'id_tim' => 28,
                'judul_tugas'     => 'Setup Campaign Google Ads',
                'deskripsi_tugas' => 'Konfigurasi Google Search dan Display Ads',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-10', 'tenggat_waktu' => '2026-02-17',
                'tanggal_selesai' => '2026-02-18', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 63 ---
            [
                'id_projek'       => 8, 'id_tim' => 29,
                'judul_tugas'     => 'Monitoring & Optimasi Campaign Bulan Feb',
                'deskripsi_tugas' => 'Monitor performa iklan dan lakukan A/B testing',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-17', 'tenggat_waktu' => '2026-03-01',
                'tanggal_selesai' => '2026-03-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 64 ---
            [
                'id_projek'       => 8, 'id_tim' => 30,
                'judul_tugas'     => 'Desain Materi Iklan Bulan Maret',
                'deskripsi_tugas' => 'Update creative iklan untuk bulan Maret',
                'level'           => 'medium', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-01', 'tenggat_waktu' => '2026-03-10',
                'tanggal_selesai' => '2026-03-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 65 ---
            [
                'id_projek'       => 8, 'id_tim' => 28,
                'judul_tugas'     => 'Laporan & Optimasi Akhir Campaign',
                'deskripsi_tugas' => 'Buat laporan ROAS dan optimasi campaign bulan April',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-04-01', 'tenggat_waktu' => '2026-04-12',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 9 — Website UMKM PT Global Media
            // Tanggal: 2025-03-01 s/d 2025-04-20 | Status: SELESAI
            // Tim: 32(WebDev), 33(UI/UX), 34(QA)
            // =========================================================

            // --- Tugas 66 ---
            [
                'id_projek'       => 9, 'id_tim' => 33,
                'judul_tugas'     => 'Desain UI Website Katalog UMKM',
                'deskripsi_tugas' => 'Buat desain tampilan katalog produk UMKM',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-03-01', 'tenggat_waktu' => '2025-03-08',
                'tanggal_selesai' => '2025-03-07',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 67 ---
            [
                'id_projek'       => 9, 'id_tim' => 32,
                'judul_tugas'     => 'Implementasi Halaman Katalog Produk',
                'deskripsi_tugas' => 'Coding halaman daftar dan detail produk UMKM',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-03-08', 'tenggat_waktu' => '2025-03-22',
                'tanggal_selesai' => '2025-03-25', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 68 ---
            [
                'id_projek'       => 9, 'id_tim' => 32,
                'judul_tugas'     => 'Implementasi Halaman Kontak & Map',
                'deskripsi_tugas' => 'Coding halaman kontak dengan Google Maps',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-03-25', 'tenggat_waktu' => '2025-04-05',
                'tanggal_selesai' => '2025-04-05',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 69 ---
            [
                'id_projek'       => 9, 'id_tim' => 34,
                'judul_tugas'     => 'Testing & Bug Fix Website UMKM',
                'deskripsi_tugas' => 'Uji website di berbagai browser dan device',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-04-05', 'tenggat_waktu' => '2025-04-20',
                'tanggal_selesai' => '2025-04-18',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 10 — Hosting dan Deployment Website UMKM
            // Tanggal: 2025-04-21 s/d 2025-05-01 | Status: SELESAI
            // Tim: 35(Fullstack), 36(Fullstack)
            // =========================================================

            // --- Tugas 70 ---
            [
                'id_projek'       => 10, 'id_tim' => 35,
                'judul_tugas'     => 'Setup Server & Konfigurasi Hosting',
                'deskripsi_tugas' => 'Konfigurasi web server Nginx dan SSL certificate',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-04-21', 'tenggat_waktu' => '2025-04-25',
                'tanggal_selesai' => '2025-04-24',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 71 ---
            [
                'id_projek'       => 10, 'id_tim' => 36,
                'judul_tugas'     => 'Deploy Website & DNS Setup',
                'deskripsi_tugas' => 'Upload file website dan konfigurasi DNS domain',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-04-25', 'tenggat_waktu' => '2025-04-28',
                'tanggal_selesai' => '2025-04-28',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 72 ---
            [
                'id_projek'       => 10, 'id_tim' => 35,
                'judul_tugas'     => 'Testing Pasca Deployment',
                'deskripsi_tugas' => 'Verifikasi website live berjalan normal di semua halaman',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-04-28', 'tenggat_waktu' => '2025-05-01',
                'tanggal_selesai' => '2025-05-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 11 — Aplikasi Absensi Mobile Nusantara Tech
            // Tanggal: 2026-02-15 s/d 2026-05-15 | Status: AKTIF
            // Tim: 37(Mobile), 38(Mobile), 39(UI/UX), 40(QA)
            // Feb-Apr: done/approved. Mei 2026: in_progress, review, revisi
            // =========================================================

            // --- Tugas 73 ---
            [
                'id_projek'       => 11, 'id_tim' => 39,
                'judul_tugas'     => 'Desain UI Aplikasi Absensi',
                'deskripsi_tugas' => 'Buat desain UI halaman login, dashboard, dan absensi',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-15', 'tenggat_waktu' => '2026-02-25',
                'tanggal_selesai' => '2026-02-24',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 74 ---
            [
                'id_projek'       => 11, 'id_tim' => 37,
                'judul_tugas'     => 'Setup Project Android Absensi',
                'deskripsi_tugas' => 'Inisialisasi project dan setup library GPS & Kamera',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-15', 'tenggat_waktu' => '2026-02-22',
                'tanggal_selesai' => '2026-02-22',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 75 ---
            [
                'id_projek'       => 11, 'id_tim' => 38,
                'judul_tugas'     => 'Modul Login & Manajemen User',
                'deskripsi_tugas' => 'Implementasi autentikasi dan manajemen akun karyawan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-22', 'tenggat_waktu' => '2026-03-08',
                'tanggal_selesai' => '2026-03-10', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 76 ---
            [
                'id_projek'       => 11, 'id_tim' => 37,
                'judul_tugas'     => 'Modul Absensi dengan GPS',
                'deskripsi_tugas' => 'Fitur check-in/check-out dengan validasi lokasi GPS',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-10', 'tenggat_waktu' => '2026-04-01',
                'tanggal_selesai' => '2026-04-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 77 ---
            [
                'id_projek'       => 11, 'id_tim' => 38,
                'judul_tugas'     => 'Modul Laporan Kehadiran',
                'deskripsi_tugas' => 'Fitur rekap dan laporan kehadiran karyawan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-04-01', 'tenggat_waktu' => '2026-04-20',
                'tanggal_selesai' => '2026-04-19',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 78 ---
            [
                'id_projek'       => 11, 'id_tim' => 40,
                'judul_tugas'     => 'Testing Aplikasi Absensi',
                'deskripsi_tugas' => 'Uji semua fitur termasuk GPS di berbagai device',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-04-20', 'tenggat_waktu' => '2026-05-05',
                'tanggal_selesai' => '2026-05-04',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 79 ---
            [
                'id_projek'       => 11, 'id_tim' => 37,
                'judul_tugas'     => 'Bug Fix & Finalisasi Aplikasi',
                'deskripsi_tugas' => 'Perbaiki bug hasil testing dan persiapan release',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-05-05', 'tenggat_waktu' => '2026-05-15',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 12 — SEO Website PT Cipta Karya Digital
            // Tanggal: 2026-03-01 s/d 2026-06-01 | Status: IN_PROGRESS
            // Tim: 41(SEO), 42(SEO), 43(Content Writer)
            // Mar-Apr: done/approved. Mei 2026: in_progress, review, revisi
            // =========================================================

            // --- Tugas 80 ---
            [
                'id_projek'       => 12, 'id_tim' => 41,
                'judul_tugas'     => 'Audit SEO & Technical Check',
                'deskripsi_tugas' => 'Analisis teknikal SEO: crawl errors, sitemap, robots',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-01', 'tenggat_waktu' => '2026-03-10',
                'tanggal_selesai' => '2026-03-09',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 81 ---
            [
                'id_projek'       => 12, 'id_tim' => 42,
                'judul_tugas'     => 'Riset Keyword & Kompetitor',
                'deskripsi_tugas' => 'Analisis keyword dan strategi konten vs kompetitor',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-01', 'tenggat_waktu' => '2026-03-12',
                'tanggal_selesai' => '2026-03-14', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 82 ---
            [
                'id_projek'       => 12, 'id_tim' => 43,
                'judul_tugas'     => 'Optimasi Konten Halaman Utama',
                'deskripsi_tugas' => 'Tulis ulang konten website sesuai keyword target',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-14', 'tenggat_waktu' => '2026-03-28',
                'tanggal_selesai' => '2026-03-28',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 83 ---
            [
                'id_projek'       => 12, 'id_tim' => 41,
                'judul_tugas'     => 'Optimasi On-Page & Schema Markup',
                'deskripsi_tugas' => 'Implementasi schema markup dan optimasi meta tags',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-28', 'tenggat_waktu' => '2026-04-10',
                'tanggal_selesai' => '2026-04-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 84 ---
            [
                'id_projek'       => 12, 'id_tim' => 42,
                'judul_tugas'     => 'Link Building & Guest Post',
                'deskripsi_tugas' => 'Bangun backlink melalui guest posting dan outreach',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-04-10', 'tenggat_waktu' => '2026-05-01',
                'tanggal_selesai' => '2026-04-30',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 85 ---
            [
                'id_projek'       => 12, 'id_tim' => 43,
                'judul_tugas'     => 'Penulisan Artikel SEO Bulan Mei',
                'deskripsi_tugas' => 'Tulis 2 artikel blog untuk meningkatkan traffic organik',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-20',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 86 ---
            [
                'id_projek'       => 12, 'id_tim' => 41,
                'judul_tugas'     => 'Laporan SEO Bulanan & Rekomendasi',
                'deskripsi_tugas' => 'Laporan performa organic traffic dan rekomendasi lanjutan',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'revisi',
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-10',
                'tanggal_selesai' => '2026-05-09',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 13 — Company Profile PT Mitra Informatika
            // Tanggal: 2025-07-10 s/d 2025-09-10 | Status: SELESAI
            // Tim: 44(WebDev), 45(Fullstack), 46(UI/UX), 47(QA)
            // =========================================================

            // --- Tugas 87 ---
            [
                'id_projek'       => 13, 'id_tim' => 46,
                'judul_tugas'     => 'Desain UI Company Profile',
                'deskripsi_tugas' => 'Buat desain seluruh halaman website company profile',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-07-10', 'tenggat_waktu' => '2025-07-20',
                'tanggal_selesai' => '2025-07-19',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 88 ---
            [
                'id_projek'       => 13, 'id_tim' => 44,
                'judul_tugas'     => 'Coding Halaman Utama & Tentang Kami',
                'deskripsi_tugas' => 'Implementasi homepage dan halaman about us',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-07-20', 'tenggat_waktu' => '2025-08-05',
                'tanggal_selesai' => '2025-08-07', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 89 ---
            [
                'id_projek'       => 13, 'id_tim' => 45,
                'judul_tugas'     => 'Coding Halaman Layanan & Portfolio',
                'deskripsi_tugas' => 'Implementasi halaman layanan dan portfolio perusahaan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-08-05', 'tenggat_waktu' => '2025-08-20',
                'tanggal_selesai' => '2025-08-20',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 90 ---
            [
                'id_projek'       => 13, 'id_tim' => 44,
                'judul_tugas'     => 'Integrasi Form Kontak & SEO Dasar',
                'deskripsi_tugas' => 'Form kontak fungsional dan optimasi meta tag dasar',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-08-20', 'tenggat_waktu' => '2025-08-30',
                'tanggal_selesai' => '2025-08-29',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 91 ---
            [
                'id_projek'       => 13, 'id_tim' => 47,
                'judul_tugas'     => 'Testing & UAT Website',
                'deskripsi_tugas' => 'User acceptance test dan perbaikan bug akhir',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-08-30', 'tenggat_waktu' => '2025-09-10',
                'tanggal_selesai' => '2025-09-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 14 — Social Media Management Vision Creative
            // Tanggal: 2026-04-01 s/d 2026-06-30 | Status: AKTIF
            // Tim: 48(Sosmed), 49(Content Writer), 50(Graphic), 51(Video), 52(Creator)
            // Apr-Mei 2026: ada in_progress, review, revisi (masih berjalan)
            // =========================================================

            // --- Tugas 92 ---
            [
                'id_projek'       => 14, 'id_tim' => 48,
                'judul_tugas'     => 'Audit & Strategi Sosial Media',
                'deskripsi_tugas' => 'Analisis akun existing dan buat strategi konten 3 bulan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-04-01', 'tenggat_waktu' => '2026-04-07',
                'tanggal_selesai' => '2026-04-06',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 93 ---
            [
                'id_projek'       => 14, 'id_tim' => 50,
                'judul_tugas'     => 'Desain Template Visual Brand',
                'deskripsi_tugas' => 'Buat template feed, story, dan highlight Instagram',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-04-07', 'tenggat_waktu' => '2026-04-14',
                'tanggal_selesai' => '2026-04-16', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 94 ---
            [
                'id_projek'       => 14, 'id_tim' => 49,
                'judul_tugas'     => 'Penulisan Content Plan April',
                'deskripsi_tugas' => 'Susun dan tulis konten 30 hari bulan April',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-04-07', 'tenggat_waktu' => '2026-04-14',
                'tanggal_selesai' => '2026-04-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 95 ---
            [
                'id_projek'       => 14, 'id_tim' => 51,
                'judul_tugas'     => 'Produksi Video Reels April',
                'deskripsi_tugas' => 'Produksi dan edit 4 video Reels untuk bulan April',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-04-14', 'tenggat_waktu' => '2026-04-25',
                'tanggal_selesai' => '2026-04-25',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 96 ---
            [
                'id_projek'       => 14, 'id_tim' => 52,
                'judul_tugas'     => 'Konten TikTok April',
                'deskripsi_tugas' => 'Produksi 4 konten TikTok organik bulan April',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-04-14', 'tenggat_waktu' => '2026-04-28',
                'tanggal_selesai' => '2026-04-28',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 97 ---
            [
                'id_projek'       => 14, 'id_tim' => 50,
                'judul_tugas'     => 'Desain Konten Grafis Mei',
                'deskripsi_tugas' => 'Buat desain 15 postingan feed untuk bulan Mei',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-15',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 98 ---
            [
                'id_projek'       => 14, 'id_tim' => 48,
                'judul_tugas'     => 'Posting & Community Management Mei',
                'deskripsi_tugas' => 'Kelola posting harian dan interaksi komunitas Mei',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-31',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 15 — Maintenance Sistem Internal
            // Tanggal: 2026-01-20 s/d 2026-04-20 | Status: AKTIF
            // Tim: 53(Fullstack), 54(Fullstack), 55(QA), 56(Customer Support)
            // Jan-Mar: done/approved. Apr 2026: in_progress, review
            // =========================================================

            // --- Tugas 99 ---
            [
                'id_projek'       => 15, 'id_tim' => 53,
                'judul_tugas'     => 'Analisis & Dokumentasi Bug Existing',
                'deskripsi_tugas' => 'Kumpulkan dan dokumentasikan seluruh bug yang dilaporkan',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-20', 'tenggat_waktu' => '2026-01-28',
                'tanggal_selesai' => '2026-01-27',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 100 ---
            [
                'id_projek'       => 15, 'id_tim' => 54,
                'judul_tugas'     => 'Perbaikan Bug Modul Login',
                'deskripsi_tugas' => 'Fix bug session timeout dan remember me tidak berfungsi',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-28', 'tenggat_waktu' => '2026-02-07',
                'tanggal_selesai' => '2026-02-10', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 101 ---
            [
                'id_projek'       => 15, 'id_tim' => 53,
                'judul_tugas'     => 'Update Dependensi & Security Patch',
                'deskripsi_tugas' => 'Update library, framework, dan patch celah keamanan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-10', 'tenggat_waktu' => '2026-02-24',
                'tanggal_selesai' => '2026-02-24',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 102 ---
            [
                'id_projek'       => 15, 'id_tim' => 55,
                'judul_tugas'     => 'Regression Testing Setelah Patch',
                'deskripsi_tugas' => 'Uji ulang semua modul setelah security patch',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-24', 'tenggat_waktu' => '2026-03-07',
                'tanggal_selesai' => '2026-03-07',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 103 ---
            [
                'id_projek'       => 15, 'id_tim' => 54,
                'judul_tugas'     => 'Optimasi Query Database',
                'deskripsi_tugas' => 'Optimasi query lambat dan tambah indexing',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-07', 'tenggat_waktu' => '2026-03-21',
                'tanggal_selesai' => '2026-03-21',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 104 ---
            [
                'id_projek'       => 15, 'id_tim' => 56,
                'judul_tugas'     => 'Dokumentasi Panduan Pengguna',
                'deskripsi_tugas' => 'Update dokumentasi dan panduan penggunaan sistem',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-03-21', 'tenggat_waktu' => '2026-04-01',
                'tanggal_selesai' => '2026-03-31',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 105 ---
            [
                'id_projek'       => 15, 'id_tim' => 53,
                'judul_tugas'     => 'Monitoring & Final Report',
                'deskripsi_tugas' => 'Monitoring sistem 2 minggu dan laporan akhir maintenance',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-04-01', 'tenggat_waktu' => '2026-04-20',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 16 — Landing Page PT Smart Digital
            // Tanggal: 2025-09-01 s/d 2025-10-01 | Status: SELESAI
            // Tim: 57(WebDev), 58(UI/UX), 59(QA)
            // =========================================================

            // --- Tugas 106 ---
            [
                'id_projek'       => 16, 'id_tim' => 58,
                'judul_tugas'     => 'Desain Mockup Landing Page',
                'deskripsi_tugas' => 'Desain mockup landing page layanan digital',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-09-01', 'tenggat_waktu' => '2025-09-07',
                'tanggal_selesai' => '2025-09-06',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 107 ---
            [
                'id_projek'       => 16, 'id_tim' => 57,
                'judul_tugas'     => 'Coding Landing Page',
                'deskripsi_tugas' => 'Implementasi landing page responsif sesuai mockup',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-09-07', 'tenggat_waktu' => '2025-09-20',
                'tanggal_selesai' => '2025-09-22', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 108 ---
            [
                'id_projek'       => 16, 'id_tim' => 57,
                'judul_tugas'     => 'Integrasi Form & Analytics',
                'deskripsi_tugas' => 'Integrasi form lead, Google Analytics dan Meta Pixel',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-09-22', 'tenggat_waktu' => '2025-09-27',
                'tanggal_selesai' => '2025-09-27',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 109 ---
            [
                'id_projek'       => 16, 'id_tim' => 59,
                'judul_tugas'     => 'Testing & Final Check',
                'deskripsi_tugas' => 'Uji cross-browser, responsif, dan kecepatan landing page',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-09-27', 'tenggat_waktu' => '2025-10-01',
                'tanggal_selesai' => '2025-10-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 17 — Branding dan Website Creative Studio
            // Tanggal: 2026-02-05 s/d 2026-05-05 | Status: AKTIF
            // Tim: 60(WebDev), 61(Fullstack), 62(UI/UX), 63(UI/UX), 64(QA), 65(Graphic)
            // Feb-Apr: done/approved. Mei 2026: in_progress, review, revisi
            // =========================================================

            // --- Tugas 110 ---
            [
                'id_projek'       => 17, 'id_tim' => 62,
                'judul_tugas'     => 'Desain Brand Identity',
                'deskripsi_tugas' => 'Buat panduan brand: warna, tipografi, logo usage',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-05', 'tenggat_waktu' => '2026-02-15',
                'tanggal_selesai' => '2026-02-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 111 ---
            [
                'id_projek'       => 17, 'id_tim' => 63,
                'judul_tugas'     => 'Desain UI Website Company Profile',
                'deskripsi_tugas' => 'Desain semua halaman website sesuai brand identity',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-15', 'tenggat_waktu' => '2026-02-28',
                'tanggal_selesai' => '2026-03-02', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 112 ---
            [
                'id_projek'       => 17, 'id_tim' => 65,
                'judul_tugas'     => 'Desain Materi Branding (Stationery)',
                'deskripsi_tugas' => 'Desain kartu nama, kop surat, dan amplop perusahaan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-15', 'tenggat_waktu' => '2026-03-01',
                'tanggal_selesai' => '2026-03-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 113 ---
            [
                'id_projek'       => 17, 'id_tim' => 60,
                'judul_tugas'     => 'Implementasi Halaman Utama & Portfolio',
                'deskripsi_tugas' => 'Coding homepage dan halaman portfolio karya',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-03', 'tenggat_waktu' => '2026-03-20',
                'tanggal_selesai' => '2026-03-20',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 114 ---
            [
                'id_projek'       => 17, 'id_tim' => 61,
                'judul_tugas'     => 'Implementasi Halaman Layanan & Blog',
                'deskripsi_tugas' => 'Coding halaman layanan dan sistem blog CMS',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-20', 'tenggat_waktu' => '2026-04-05',
                'tanggal_selesai' => '2026-04-05',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 115 ---
            [
                'id_projek'       => 17, 'id_tim' => 64,
                'judul_tugas'     => 'Testing Website & QA Branding',
                'deskripsi_tugas' => 'Uji website dan verifikasi konsistensi brand identity',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-04-05', 'tenggat_waktu' => '2026-04-20',
                'tanggal_selesai' => '2026-04-19',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 116 ---
            [
                'id_projek'       => 17, 'id_tim' => 60,
                'judul_tugas'     => 'Revisi & Finalisasi Website',
                'deskripsi_tugas' => 'Perbaikan berdasarkan feedback QA dan klien',
                'level'           => 'medium', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'revisi',
                'tanggal_mulai'   => '2026-04-20', 'tenggat_waktu' => '2026-05-01',
                'tanggal_selesai' => '2026-04-30',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 117 ---
            [
                'id_projek'       => 17, 'id_tim' => 61,
                'judul_tugas'     => 'Deploy & Launch Website',
                'deskripsi_tugas' => 'Deploy website ke server production dan launch resmi',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-05',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 18 — Google Ads Campaign Media Inovatif
            // Tanggal: 2026-03-12 s/d 2026-05-12 | Status: IN_PROGRESS
            // Tim: 66(Digimkt), 67(Sosmed), 68(Content Writer), 69(Graphic)
            // Mar-Apr: done/approved. Mei 2026: in_progress, review, revisi
            // =========================================================

            // --- Tugas 118 ---
            [
                'id_projek'       => 18, 'id_tim' => 66,
                'judul_tugas'     => 'Riset Keyword & Strategi Campaign',
                'deskripsi_tugas' => 'Riset keyword Google Ads dan buat strategi campaign',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-12', 'tenggat_waktu' => '2026-03-20',
                'tanggal_selesai' => '2026-03-19',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 119 ---
            [
                'id_projek'       => 18, 'id_tim' => 69,
                'judul_tugas'     => 'Desain Banner Display Ads',
                'deskripsi_tugas' => 'Buat berbagai ukuran banner untuk Google Display',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-20', 'tenggat_waktu' => '2026-03-28',
                'tanggal_selesai' => '2026-03-31', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 120 ---
            [
                'id_projek'       => 18, 'id_tim' => 68,
                'judul_tugas'     => 'Penulisan Ad Copy',
                'deskripsi_tugas' => 'Tulis copy iklan yang menarik untuk Search dan Display',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-20', 'tenggat_waktu' => '2026-03-28',
                'tanggal_selesai' => '2026-03-28',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 121 ---
            [
                'id_projek'       => 18, 'id_tim' => 66,
                'judul_tugas'     => 'Setup & Launch Campaign Google Ads',
                'deskripsi_tugas' => 'Konfigurasi dan aktifkan campaign Google Ads',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-31', 'tenggat_waktu' => '2026-04-05',
                'tanggal_selesai' => '2026-04-05',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 122 ---
            [
                'id_projek'       => 18, 'id_tim' => 67,
                'judul_tugas'     => 'Monitoring Campaign Bulan April',
                'deskripsi_tugas' => 'Monitor CTR, CPC, conversion rate dan buat optimasi',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-04-05', 'tenggat_waktu' => '2026-04-30',
                'tanggal_selesai' => '2026-04-30',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 123 ---
            [
                'id_projek'       => 18, 'id_tim' => 66,
                'judul_tugas'     => 'Laporan & Optimasi Akhir Campaign',
                'deskripsi_tugas' => 'Laporan performa campaign dan rekomendasi akhir',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-12',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 124 ---
            [
                'id_projek'       => 18, 'id_tim' => 68,
                'judul_tugas'     => 'Update Ad Copy Bulan Mei',
                'deskripsi_tugas' => 'Refresh copy iklan untuk meningkatkan CTR',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'revisi',
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-07',
                'tanggal_selesai' => '2026-05-06',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 19 — E-Commerce PT Future Technology
            // Tanggal: 2025-11-01 s/d 2026-02-01 | Status: AKTIF
            // Tim: 70(WebDev), 71(WebDev), 72(Fullstack), 73(Fullstack), 74(UI/UX), 75(QA)
            // Nov25-Jan26: done/approved. Feb 2026 sudah lewat: approved semua
            // =========================================================

            // --- Tugas 125 ---
            [
                'id_projek'       => 19, 'id_tim' => 74,
                'judul_tugas'     => 'Desain UI E-Commerce',
                'deskripsi_tugas' => 'Desain UI lengkap: home, product, cart, checkout',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-11-01', 'tenggat_waktu' => '2025-11-15',
                'tanggal_selesai' => '2025-11-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 126 ---
            [
                'id_projek'       => 19, 'id_tim' => 72,
                'judul_tugas'     => 'Setup Backend & Database E-Commerce',
                'deskripsi_tugas' => 'Setup Laravel API dan desain database e-commerce',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-11-01', 'tenggat_waktu' => '2025-11-15',
                'tanggal_selesai' => '2025-11-17', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 127 ---
            [
                'id_projek'       => 19, 'id_tim' => 70,
                'judul_tugas'     => 'Modul Katalog & Pencarian Produk',
                'deskripsi_tugas' => 'Frontend katalog produk dengan filter dan pencarian',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-11-17', 'tenggat_waktu' => '2025-12-05',
                'tanggal_selesai' => '2025-12-05',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 128 ---
            [
                'id_projek'       => 19, 'id_tim' => 71,
                'judul_tugas'     => 'Modul Keranjang & Checkout',
                'deskripsi_tugas' => 'Fitur keranjang belanja dan proses checkout',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-11-17', 'tenggat_waktu' => '2025-12-10',
                'tanggal_selesai' => '2025-12-12', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 129 ---
            [
                'id_projek'       => 19, 'id_tim' => 73,
                'judul_tugas'     => 'Integrasi Payment Gateway',
                'deskripsi_tugas' => 'Integrasi Midtrans untuk berbagai metode pembayaran',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-12-10', 'tenggat_waktu' => '2026-01-05',
                'tanggal_selesai' => '2026-01-05',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 130 ---
            [
                'id_projek'       => 19, 'id_tim' => 72,
                'judul_tugas'     => 'Modul Admin Dashboard E-Commerce',
                'deskripsi_tugas' => 'Dashboard admin: kelola produk, order, dan laporan',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-05', 'tenggat_waktu' => '2026-01-20',
                'tanggal_selesai' => '2026-01-20',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 131 ---
            [
                'id_projek'       => 19, 'id_tim' => 75,
                'judul_tugas'     => 'Testing End-to-End E-Commerce',
                'deskripsi_tugas' => 'Uji seluruh alur belanja dari browse hingga pembayaran',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-20', 'tenggat_waktu' => '2026-02-01',
                'tanggal_selesai' => '2026-02-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 20 — Mobile App Booking PT Teknologi Hebat
            // Tanggal: 2026-01-15 s/d 2026-04-15 | Status: IN_PROGRESS
            // Tim: 76(Mobile), 77(Mobile), 78(UI/UX), 79(QA), 80(Customer Support)
            // Jan-Mar: done/approved. Apr-Mei 2026: in_progress, review, revisi
            // =========================================================

            // --- Tugas 132 ---
            [
                'id_projek'       => 20, 'id_tim' => 78,
                'judul_tugas'     => 'Desain UI Aplikasi Booking',
                'deskripsi_tugas' => 'Desain UI: halaman booking, jadwal, konfirmasi',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-15', 'tenggat_waktu' => '2026-01-28',
                'tanggal_selesai' => '2026-01-27',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 133 ---
            [
                'id_projek'       => 20, 'id_tim' => 76,
                'judul_tugas'     => 'Setup Project iOS & Android',
                'deskripsi_tugas' => 'Setup Flutter project untuk iOS dan Android',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-15', 'tenggat_waktu' => '2026-01-22',
                'tanggal_selesai' => '2026-01-22',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 134 ---
            [
                'id_projek'       => 20, 'id_tim' => 77,
                'judul_tugas'     => 'Modul Login & Profil User',
                'deskripsi_tugas' => 'Implementasi auth dan manajemen profil pelanggan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-01-22', 'tenggat_waktu' => '2026-02-05',
                'tanggal_selesai' => '2026-02-07', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 135 ---
            [
                'id_projek'       => 20, 'id_tim' => 76,
                'judul_tugas'     => 'Modul Booking & Penjadwalan',
                'deskripsi_tugas' => 'Fitur pilih layanan, tanggal, jam, dan konfirmasi booking',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-07', 'tenggat_waktu' => '2026-03-01',
                'tanggal_selesai' => '2026-03-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 136 ---
            [
                'id_projek'       => 20, 'id_tim' => 77,
                'judul_tugas'     => 'Modul Pembayaran & Riwayat',
                'deskripsi_tugas' => 'Integrasi payment dan riwayat booking pelanggan',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-01', 'tenggat_waktu' => '2026-03-20',
                'tanggal_selesai' => '2026-03-20',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 137 ---
            [
                'id_projek'       => 20, 'id_tim' => 79,
                'judul_tugas'     => 'Testing Aplikasi Booking',
                'deskripsi_tugas' => 'Uji semua fitur di iOS dan Android device',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-03-20', 'tenggat_waktu' => '2026-04-05',
                'tanggal_selesai' => '2026-04-04',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 138 ---
            [
                'id_projek'       => 20, 'id_tim' => 80,
                'judul_tugas'     => 'Dokumentasi & Panduan Pengguna Aplikasi',
                'deskripsi_tugas' => 'Buat panduan pengguna dan FAQ aplikasi booking',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'revisi',
                'tanggal_mulai'   => '2026-04-05', 'tenggat_waktu' => '2026-04-12',
                'tanggal_selesai' => '2026-04-11',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 139 ---
            [
                'id_projek'       => 20, 'id_tim' => 76,
                'judul_tugas'     => 'Bug Fix & App Store Submission',
                'deskripsi_tugas' => 'Perbaiki bug akhir dan persiapan submit ke Play Store',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-04-12', 'tenggat_waktu' => '2026-04-15',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 21 — Website Pemerintahan PT Berkah Digital
            // Tanggal: 2025-08-01 s/d 2025-11-01 | Status: AKTIF
            // Tim: 81(WebDev), 82(Fullstack), 83(Fullstack), 84(UI/UX), 85(QA), 86(QA)
            // Semua sudah lewat, approved semua
            // =========================================================

            // --- Tugas 140 ---
            [
                'id_projek'       => 21, 'id_tim' => 84,
                'judul_tugas'     => 'Desain UI Portal Pemerintahan',
                'deskripsi_tugas' => 'Desain UI portal informasi dan layanan pemerintahan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-08-01', 'tenggat_waktu' => '2025-08-15',
                'tanggal_selesai' => '2025-08-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 141 ---
            [
                'id_projek'       => 21, 'id_tim' => 82,
                'judul_tugas'     => 'Setup Backend & Database Portal',
                'deskripsi_tugas' => 'Arsitektur backend dan database sistem informasi pemda',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-08-01', 'tenggat_waktu' => '2025-08-20',
                'tanggal_selesai' => '2025-08-22', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 142 ---
            [
                'id_projek'       => 21, 'id_tim' => 81,
                'judul_tugas'     => 'Modul Informasi & Berita Daerah',
                'deskripsi_tugas' => 'Sistem manajemen berita dan informasi pemerintahan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-08-22', 'tenggat_waktu' => '2025-09-05',
                'tanggal_selesai' => '2025-09-05',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 143 ---
            [
                'id_projek'       => 21, 'id_tim' => 83,
                'judul_tugas'     => 'Modul Layanan Publik Online',
                'deskripsi_tugas' => 'Fitur pengajuan layanan publik dan tracking status',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-09-05', 'tenggat_waktu' => '2025-09-25',
                'tanggal_selesai' => '2025-09-25',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 144 ---
            [
                'id_projek'       => 21, 'id_tim' => 82,
                'judul_tugas'     => 'Modul Data Statistik & Laporan',
                'deskripsi_tugas' => 'Dashboard statistik dan laporan kinerja pemerintah',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-09-25', 'tenggat_waktu' => '2025-10-15',
                'tanggal_selesai' => '2025-10-17', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 145 ---
            [
                'id_projek'       => 21, 'id_tim' => 85,
                'judul_tugas'     => 'Security Audit & Penetration Test',
                'deskripsi_tugas' => 'Uji keamanan portal dari serangan dan celah keamanan',
                'level'           => 'susah', 'weight' => 5,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-10-17', 'tenggat_waktu' => '2025-10-28',
                'tanggal_selesai' => '2025-10-28',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 146 ---
            [
                'id_projek'       => 21, 'id_tim' => 86,
                'judul_tugas'     => 'UAT & Serah Terima Sistem',
                'deskripsi_tugas' => 'User acceptance test dan serah terima ke klien',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-10-28', 'tenggat_waktu' => '2025-11-01',
                'tanggal_selesai' => '2025-11-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 22 — SEO dan Maintenance PT Prima Teknologi
            // Tanggal: 2026-02-10 s/d 2026-05-10 | Status: AKTIF
            // Tim: 87(Fullstack), 88(SEO), 89(SEO), 90(Content Writer)
            // Feb-Apr: done/approved. Mei 2026: in_progress, review, revisi
            // =========================================================

            // --- Tugas 147 ---
            [
                'id_projek'       => 22, 'id_tim' => 88,
                'judul_tugas'     => 'Audit SEO & Technical Website',
                'deskripsi_tugas' => 'Audit menyeluruh SEO dan kondisi teknikal website',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-10', 'tenggat_waktu' => '2026-02-20',
                'tanggal_selesai' => '2026-02-19',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 148 ---
            [
                'id_projek'       => 22, 'id_tim' => 87,
                'judul_tugas'     => 'Identifikasi & Fix Bug Sistem',
                'deskripsi_tugas' => 'Temukan dan perbaiki bug pada sistem yang ada',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-10', 'tenggat_waktu' => '2026-02-24',
                'tanggal_selesai' => '2026-02-26', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 149 ---
            [
                'id_projek'       => 22, 'id_tim' => 89,
                'judul_tugas'     => 'Optimasi On-Page SEO',
                'deskripsi_tugas' => 'Perbaiki meta tag, heading, dan struktur konten',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-02-24', 'tenggat_waktu' => '2026-03-10',
                'tanggal_selesai' => '2026-03-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 150 ---
            [
                'id_projek'       => 22, 'id_tim' => 90,
                'judul_tugas'     => 'Penulisan Konten SEO',
                'deskripsi_tugas' => 'Tulis ulang konten halaman utama sesuai keyword',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-10', 'tenggat_waktu' => '2026-03-25',
                'tanggal_selesai' => '2026-03-25',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 151 ---
            [
                'id_projek'       => 22, 'id_tim' => 87,
                'judul_tugas'     => 'Update & Optimasi Performa Sistem',
                'deskripsi_tugas' => 'Update dependensi dan optimasi kecepatan sistem',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-25', 'tenggat_waktu' => '2026-04-10',
                'tanggal_selesai' => '2026-04-10',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 152 ---
            [
                'id_projek'       => 22, 'id_tim' => 88,
                'judul_tugas'     => 'Link Building & Monitoring',
                'deskripsi_tugas' => 'Bangun backlink dan pantau ranking keyword',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-04-10', 'tenggat_waktu' => '2026-05-01',
                'tanggal_selesai' => '2026-04-30',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 153 ---
            [
                'id_projek'       => 22, 'id_tim' => 89,
                'judul_tugas'     => 'Laporan SEO & Maintenance Final',
                'deskripsi_tugas' => 'Buat laporan akhir SEO dan maintenance seluruh proyek',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-10',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 23 — Sistem Custom Inventory
            // Tanggal: 2026-03-20 s/d 2026-06-20 | Status: IN_PROGRESS
            // Tim: 91(WebDev), 92(Fullstack), 93(Fullstack), 94(UI/UX), 95(QA)
            // Mar-Apr: done/approved. Mei 2026: in_progress, review, revisi
            // =========================================================

            // --- Tugas 154 ---
            [
                'id_projek'       => 23, 'id_tim' => 94,
                'judul_tugas'     => 'Desain UI Sistem Inventory',
                'deskripsi_tugas' => 'Desain UI dashboard dan seluruh modul inventory',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-20', 'tenggat_waktu' => '2026-03-30',
                'tanggal_selesai' => '2026-03-29',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 155 ---
            [
                'id_projek'       => 23, 'id_tim' => 92,
                'judul_tugas'     => 'Setup Backend & Database Inventory',
                'deskripsi_tugas' => 'Desain database dan setup API backend sistem inventory',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-03-20', 'tenggat_waktu' => '2026-03-31',
                'tanggal_selesai' => '2026-04-02', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 156 ---
            [
                'id_projek'       => 23, 'id_tim' => 91,
                'judul_tugas'     => 'Modul Manajemen Barang & Kategori',
                'deskripsi_tugas' => 'CRUD barang, kategori, satuan, dan kode SKU',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-04-02', 'tenggat_waktu' => '2026-04-15',
                'tanggal_selesai' => '2026-04-15',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 157 ---
            [
                'id_projek'       => 23, 'id_tim' => 93,
                'judul_tugas'     => 'Modul Stok Masuk & Keluar',
                'deskripsi_tugas' => 'Fitur transaksi stok masuk, keluar, dan retur',
                'level'           => 'susah', 'weight' => 4,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2026-04-15', 'tenggat_waktu' => '2026-05-01',
                'tanggal_selesai' => '2026-05-01',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 158 ---
            [
                'id_projek'       => 23, 'id_tim' => 92,
                'judul_tugas'     => 'Modul Laporan & Ekspor Data',
                'deskripsi_tugas' => 'Laporan stok dan ekspor ke Excel/PDF',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'review',
                'tanggal_mulai'   => '2026-05-01', 'tenggat_waktu' => '2026-05-15',
                'tanggal_selesai' => '2026-05-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 159 ---
            [
                'id_projek'       => 23, 'id_tim' => 95,
                'judul_tugas'     => 'Testing Modul Inventory',
                'deskripsi_tugas' => 'Uji semua modul: manajemen barang, stok, laporan',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'In Progress', 'status_akhir' => null,
                'tanggal_mulai'   => '2026-05-10', 'tenggat_waktu' => '2026-05-25',
                'tanggal_selesai' => null,
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 160 ---
            [
                'id_projek'       => 23, 'id_tim' => 91,
                'judul_tugas'     => 'Modul Notifikasi Stok Minimum',
                'deskripsi_tugas' => 'Alert otomatis ketika stok barang di bawah minimum',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'revisi',
                'tanggal_mulai'   => '2026-05-05', 'tenggat_waktu' => '2026-05-15',
                'tanggal_selesai' => '2026-05-14',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],

            // =========================================================
            // PROJEK 24 — Deployment dan Hosting Infinity Solution
            // Tanggal: 2025-12-01 s/d 2025-12-20 | Status: SELESAI
            // Tim: 96(WebDev), 97(Fullstack), 98(Fullstack)
            // =========================================================

            // --- Tugas 161 ---
            [
                'id_projek'       => 24, 'id_tim' => 97,
                'judul_tugas'     => 'Konfigurasi VPS & OS',
                'deskripsi_tugas' => 'Setup VPS, instalasi Ubuntu dan konfigurasi dasar',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-12-01', 'tenggat_waktu' => '2025-12-05',
                'tanggal_selesai' => '2025-12-04',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 162 ---
            [
                'id_projek'       => 24, 'id_tim' => 98,
                'judul_tugas'     => 'Setup Web Server & Database Server',
                'deskripsi_tugas' => 'Instalasi dan konfigurasi Nginx, MySQL, PHP-FPM',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-12-05', 'tenggat_waktu' => '2025-12-10',
                'tanggal_selesai' => '2025-12-11', // telat
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 163 ---
            [
                'id_projek'       => 24, 'id_tim' => 96,
                'judul_tugas'     => 'Deploy Aplikasi & Konfigurasi Domain',
                'deskripsi_tugas' => 'Deploy aplikasi ke server dan konfigurasi DNS domain',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-12-11', 'tenggat_waktu' => '2025-12-15',
                'tanggal_selesai' => '2025-12-15',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 164 ---
            [
                'id_projek'       => 24, 'id_tim' => 97,
                'judul_tugas'     => 'Setup SSL & Security Hardening',
                'deskripsi_tugas' => 'Instalasi SSL Let\'s Encrypt dan hardening keamanan server',
                'level'           => 'medium', 'weight' => 3,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-12-15', 'tenggat_waktu' => '2025-12-18',
                'tanggal_selesai' => '2025-12-18',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
            // --- Tugas 165 ---
            [
                'id_projek'       => 24, 'id_tim' => 98,
                'judul_tugas'     => 'Verifikasi & Testing Pasca Deployment',
                'deskripsi_tugas' => 'Uji semua fitur aplikasi di server production',
                'level'           => 'mudah', 'weight' => 2,
                'status_progress' => 'done', 'status_akhir' => 'approved',
                'tanggal_mulai'   => '2025-12-18', 'tenggat_waktu' => '2025-12-20',
                'tanggal_selesai' => '2025-12-20',
                'dibuat_pada' => $now, 'diubah_pada' => $now,
            ],
        ]);

        DB::statement('ALTER TABLE tugas AUTO_INCREMENT = 166;');
    }
}