<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjekTimSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('projek_tim')->insert([

            // =======================================================
            // PROJEK 1 — Website Company Profile PT Digital Nusantara
            // Kategori: Web Development
            // =======================================================
            // Ahmad Fauzi       (id: 3)  — Web Developer
            // Dimas Saputra     (id: 4)  — Web Developer
            // Salsa Aulia       (id: 10) — UI/UX Designer
            // Bagas Firmansyah  (id: 12) — QA Tester
            ['id_projek' => 1, 'id_user' => 3,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 1, 'id_user' => 4,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 1, 'id_user' => 10, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 1, 'id_user' => 12, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 2 — SEO Website Corporate PT Digital Nusantara
            // Kategori: SEO Optimization
            // =======================================================
            // Intan Permata     (id: 14) — SEO Specialist
            // Dwi Cahyo         (id: 15) — SEO Specialist
            // Rara Amelia       (id: 18) — Content Writer
            ['id_projek' => 2, 'id_user' => 14, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 2, 'id_user' => 15, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 2, 'id_user' => 18, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 3 — Landing Page CV Kreasi Media
            // Kategori: Web Development
            // =======================================================
            // Rizky Pratama     (id: 5)  — Web Developer
            // Citra Maharani    (id: 11) — UI/UX Designer
            // Yogi Kurniawan    (id: 13) — QA Tester
            ['id_projek' => 3, 'id_user' => 5,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 3, 'id_user' => 11, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 3, 'id_user' => 13, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 4 — Social Media Management Kreasi Media
            // Kategori: Social Media Management
            // =======================================================
            // Vina Oktaviani    (id: 17) — Social Media Specialist
            // Rara Amelia       (id: 18) — Content Writer
            // Kevin Christian   (id: 19) — Graphic Designer
            // Tiara Anindya     (id: 21) — Content Creator
            ['id_projek' => 4, 'id_user' => 17, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 4, 'id_user' => 18, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 4, 'id_user' => 19, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 4, 'id_user' => 21, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 5 — Sistem POS PT Solusi Teknologi
            // Kategori: Web Development (Sistem Custom)
            // =======================================================
            // Ahmad Fauzi       (id: 3)  — Web Developer
            // Nanda Wijaya      (id: 6)  — Fullstack Developer
            // Fajar Ramadhan    (id: 7)  — Fullstack Developer
            // Salsa Aulia       (id: 10) — UI/UX Designer
            // Bagas Firmansyah  (id: 12) — QA Tester
            ['id_projek' => 5, 'id_user' => 3,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 5, 'id_user' => 6,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 5, 'id_user' => 7,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 5, 'id_user' => 10, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 5, 'id_user' => 12, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 6 — Mobile App Customer Service
            // Kategori: Mobile App Development
            // =======================================================
            // Andika Putra      (id: 8)  — Mobile Developer
            // Reza Maulana      (id: 9)  — Mobile Developer
            // Citra Maharani    (id: 11) — UI/UX Designer
            // Yogi Kurniawan    (id: 13) — QA Tester
            ['id_projek' => 6, 'id_user' => 8,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 6, 'id_user' => 9,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 6, 'id_user' => 11, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 6, 'id_user' => 13, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 7 — Website Sekolah PT Inovasi Kreatif
            // Kategori: Web Development
            // =======================================================
            // Dimas Saputra     (id: 4)  — Web Developer
            // Rizky Pratama     (id: 5)  — Web Developer
            // Salsa Aulia       (id: 10) — UI/UX Designer
            // Yogi Kurniawan    (id: 13) — QA Tester
            ['id_projek' => 7, 'id_user' => 4,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 7, 'id_user' => 5,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 7, 'id_user' => 10, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 7, 'id_user' => 13, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 8 — Digital Marketing PT Maju Bersama
            // Kategori: Digital Marketing
            // =======================================================
            // Nadia Putri       (id: 16) — Digital Marketing Specialist
            // Vina Oktaviani    (id: 17) — Social Media Specialist
            // Kevin Christian   (id: 19) — Graphic Designer
            // Farhan Akbar      (id: 20) — Video Editor
            ['id_projek' => 8, 'id_user' => 16, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 8, 'id_user' => 17, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 8, 'id_user' => 19, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 8, 'id_user' => 20, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 9 — Website UMKM PT Global Media
            // Kategori: Web Development
            // =======================================================
            // Ahmad Fauzi       (id: 3)  — Web Developer
            // Citra Maharani    (id: 11) — UI/UX Designer
            // Bagas Firmansyah  (id: 12) — QA Tester
            ['id_projek' => 9, 'id_user' => 3,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 9, 'id_user' => 11, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 9, 'id_user' => 12, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 10 — Hosting dan Deployment Website UMKM
            // Kategori: Hosting & Deployment
            // =======================================================
            // Nanda Wijaya      (id: 6)  — Fullstack Developer
            // Fajar Ramadhan    (id: 7)  — Fullstack Developer
            ['id_projek' => 10, 'id_user' => 6,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 10, 'id_user' => 7,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 11 — Aplikasi Absensi Mobile Nusantara Tech
            // Kategori: Mobile App Development
            // =======================================================
            // Andika Putra      (id: 8)  — Mobile Developer
            // Reza Maulana      (id: 9)  — Mobile Developer
            // Salsa Aulia       (id: 10) — UI/UX Designer
            // Bagas Firmansyah  (id: 12) — QA Tester
            ['id_projek' => 11, 'id_user' => 8,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 11, 'id_user' => 9,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 11, 'id_user' => 10, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 11, 'id_user' => 12, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 12 — SEO Website PT Cipta Karya Digital
            // Kategori: SEO Optimization
            // =======================================================
            // Intan Permata     (id: 14) — SEO Specialist
            // Dwi Cahyo         (id: 15) — SEO Specialist
            // Rara Amelia       (id: 18) — Content Writer
            ['id_projek' => 12, 'id_user' => 14, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 12, 'id_user' => 15, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 12, 'id_user' => 18, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 13 — Company Profile PT Mitra Informatika
            // Kategori: Web Development
            // =======================================================
            // Rizky Pratama     (id: 5)  — Web Developer
            // Nanda Wijaya      (id: 6)  — Fullstack Developer
            // Citra Maharani    (id: 11) — UI/UX Designer
            // Yogi Kurniawan    (id: 13) — QA Tester
            ['id_projek' => 13, 'id_user' => 5,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 13, 'id_user' => 6,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 13, 'id_user' => 11, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 13, 'id_user' => 13, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 14 — Social Media Management Vision Creative
            // Kategori: Social Media Management
            // =======================================================
            // Vina Oktaviani    (id: 17) — Social Media Specialist
            // Rara Amelia       (id: 18) — Content Writer
            // Kevin Christian   (id: 19) — Graphic Designer
            // Farhan Akbar      (id: 20) — Video Editor
            // Tiara Anindya     (id: 21) — Content Creator
            ['id_projek' => 14, 'id_user' => 17, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 14, 'id_user' => 18, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 14, 'id_user' => 19, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 14, 'id_user' => 20, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 14, 'id_user' => 21, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 15 — Maintenance Sistem Internal
            // Kategori: System Maintenance
            // =======================================================
            // Nanda Wijaya      (id: 6)  — Fullstack Developer
            // Fajar Ramadhan    (id: 7)  — Fullstack Developer
            // Bagas Firmansyah  (id: 12) — QA Tester
            // Aldi Saputra      (id: 22) — Customer Support
            ['id_projek' => 15, 'id_user' => 6,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 15, 'id_user' => 7,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 15, 'id_user' => 12, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 15, 'id_user' => 22, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 16 — Landing Page PT Smart Digital
            // Kategori: Web Development
            // =======================================================
            // Ahmad Fauzi       (id: 3)  — Web Developer
            // Salsa Aulia       (id: 10) — UI/UX Designer
            // Yogi Kurniawan    (id: 13) — QA Tester
            ['id_projek' => 16, 'id_user' => 3,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 16, 'id_user' => 10, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 16, 'id_user' => 13, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 17 — Branding dan Website Creative Studio
            // Kategori: Web Development + Branding
            // =======================================================
            // Dimas Saputra     (id: 4)  — Web Developer
            // Nanda Wijaya      (id: 6)  — Fullstack Developer
            // Salsa Aulia       (id: 10) — UI/UX Designer
            // Citra Maharani    (id: 11) — UI/UX Designer
            // Kevin Christian   (id: 19) — Graphic Designer
            // Bagas Firmansyah  (id: 12) — QA Tester
            ['id_projek' => 17, 'id_user' => 4,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 17, 'id_user' => 6,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 17, 'id_user' => 10, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 17, 'id_user' => 11, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 17, 'id_user' => 12, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 17, 'id_user' => 19, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 18 — Google Ads Campaign Media Inovatif
            // Kategori: Digital Marketing
            // =======================================================
            // Nadia Putri       (id: 16) — Digital Marketing Specialist
            // Vina Oktaviani    (id: 17) — Social Media Specialist
            // Rara Amelia       (id: 18) — Content Writer
            // Kevin Christian   (id: 19) — Graphic Designer
            ['id_projek' => 18, 'id_user' => 16, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 18, 'id_user' => 17, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 18, 'id_user' => 18, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 18, 'id_user' => 19, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 19 — E-Commerce PT Future Technology
            // Kategori: Web Development (E-Commerce)
            // =======================================================
            // Ahmad Fauzi       (id: 3)  — Web Developer
            // Dimas Saputra     (id: 4)  — Web Developer
            // Nanda Wijaya      (id: 6)  — Fullstack Developer
            // Fajar Ramadhan    (id: 7)  — Fullstack Developer
            // Salsa Aulia       (id: 10) — UI/UX Designer
            // Bagas Firmansyah  (id: 12) — QA Tester
            ['id_projek' => 19, 'id_user' => 3,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 19, 'id_user' => 4,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 19, 'id_user' => 6,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 19, 'id_user' => 7,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 19, 'id_user' => 10, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 19, 'id_user' => 12, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 20 — Mobile App Booking PT Teknologi Hebat
            // Kategori: Mobile App Development
            // =======================================================
            // Andika Putra      (id: 8)  — Mobile Developer
            // Reza Maulana      (id: 9)  — Mobile Developer
            // Citra Maharani    (id: 11) — UI/UX Designer
            // Yogi Kurniawan    (id: 13) — QA Tester
            // Aldi Saputra      (id: 22) — Customer Support
            ['id_projek' => 20, 'id_user' => 8,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 20, 'id_user' => 9,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 20, 'id_user' => 11, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 20, 'id_user' => 13, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 20, 'id_user' => 22, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 21 — Website Pemerintahan PT Berkah Digital
            // Kategori: Web Development (Sistem Pemerintahan)
            // =======================================================
            // Rizky Pratama     (id: 5)  — Web Developer
            // Nanda Wijaya      (id: 6)  — Fullstack Developer
            // Fajar Ramadhan    (id: 7)  — Fullstack Developer
            // Salsa Aulia       (id: 10) — UI/UX Designer
            // Bagas Firmansyah  (id: 12) — QA Tester
            // Yogi Kurniawan    (id: 13) — QA Tester
            ['id_projek' => 21, 'id_user' => 5,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 21, 'id_user' => 6,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 21, 'id_user' => 7,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 21, 'id_user' => 10, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 21, 'id_user' => 12, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 21, 'id_user' => 13, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 22 — SEO dan Maintenance PT Prima Teknologi
            // Kategori: SEO Optimization + System Maintenance
            // =======================================================
            // Intan Permata     (id: 14) — SEO Specialist
            // Dwi Cahyo         (id: 15) — SEO Specialist
            // Rara Amelia       (id: 18) — Content Writer
            // Fajar Ramadhan    (id: 7)  — Fullstack Developer (maintenance)
            ['id_projek' => 22, 'id_user' => 7,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 22, 'id_user' => 14, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 22, 'id_user' => 15, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 22, 'id_user' => 18, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 23 — Sistem Custom Inventory
            // Kategori: Web Development (Sistem Custom)
            // =======================================================
            // Ahmad Fauzi       (id: 3)  — Web Developer
            // Nanda Wijaya      (id: 6)  — Fullstack Developer
            // Fajar Ramadhan    (id: 7)  — Fullstack Developer
            // Citra Maharani    (id: 11) — UI/UX Designer
            // Yogi Kurniawan    (id: 13) — QA Tester
            ['id_projek' => 23, 'id_user' => 3,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 23, 'id_user' => 6,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 23, 'id_user' => 7,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 23, 'id_user' => 11, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 23, 'id_user' => 13, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],

            // =======================================================
            // PROJEK 24 — Deployment dan Hosting Infinity Solution
            // Kategori: Hosting & Deployment
            // =======================================================
            // Nanda Wijaya      (id: 6)  — Fullstack Developer
            // Fajar Ramadhan    (id: 7)  — Fullstack Developer
            // Rizky Pratama     (id: 5)  — Web Developer
            ['id_projek' => 24, 'id_user' => 5,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 24, 'id_user' => 6,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id_projek' => 24, 'id_user' => 7,  'dibuat_pada' => $now, 'diperbarui_pada' => $now],
        ]);
    }
}
