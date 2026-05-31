<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriProjekSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('kategori_projek')->insert([
            // -------------------------------------------------------
            // 1. Web Development
            // -------------------------------------------------------
            [
                'nama_kategori' => 'Web Development',
                'deskripsi'        => 'Pembuatan dan pengembangan website',
                'status'                => true,
                'dibuat_pada'           => $now,
                'diperbarui_pada'       => $now,
            ],
            // -------------------------------------------------------
            // 2. Mobile App Development
            // -------------------------------------------------------
            [
                'nama_kategori' => 'Mobile App Development',
                'deskripsi'        => 'Aplikasi Android dan iOS',
                'status'                => true,
                'dibuat_pada'           => $now,
                'diperbarui_pada'       => $now,
            ],
            // -------------------------------------------------------
            // 3. SEO Optimization
            // -------------------------------------------------------
            [
                'nama_kategori' => 'SEO Optimization',
                'deskripsi'        => 'Optimasi Google Search, riset keyword, optimasi website',
                'status'                => true,
                'dibuat_pada'           => $now,
                'diperbarui_pada'       => $now,
            ],
            // -------------------------------------------------------
            // 4. Digital Marketing
            // -------------------------------------------------------
            [
                'nama_kategori' => 'Digital Marketing',
                'deskripsi'        => 'Google Ads, Meta Ads, campaign digital',
                'status'                => true,
                'dibuat_pada'           => $now,
                'diperbarui_pada'       => $now,
            ],
            // -------------------------------------------------------
            // 5. Social Media Management
            // -------------------------------------------------------
            [
                'nama_kategori' => 'Social Media Management',
                'deskripsi'        => 'Kelola Instagram, TikTok, Facebook bisnis',
                'status'                => true,
                'dibuat_pada'           => $now,
                'diperbarui_pada'       => $now,
            ],
            // -------------------------------------------------------
            // 6. System Maintenance
            // -------------------------------------------------------
            [
                'nama_kategori' => 'System Maintenance',
                'deskripsi'        => 'Maintenance website dan aplikasi',
                'status'                => true,
                'dibuat_pada'           => $now,
                'diperbarui_pada'       => $now,
            ],
            // -------------------------------------------------------
            // 7. Hosting & Deployment
            // -------------------------------------------------------
            [
                'nama_kategori' => 'Hosting & Deployment',
                'deskripsi'        => 'Hosting website, deploy aplikasi, konfigurasi server',
                'status'                => true,
                'dibuat_pada'           => $now,
                'diperbarui_pada'       => $now,
            ],
        ]);
    }
}
