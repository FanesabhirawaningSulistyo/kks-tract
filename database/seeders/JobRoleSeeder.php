<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('job_roles')->insert([
            [
                'nama_job_role' => 'Admin',
                'deskripsi' => 'Mengelola sistem, data, dan hak akses pengguna',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'PM',
                'deskripsi' => 'Mengelola proyek, tim, timeline, dan komunikasi klien',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Web Developer',
                'deskripsi' => 'Bertanggung jawab mengembangkan, memelihara, dan mengoptimalkan aplikasi berbasis web.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Fullstack Developer',
                'deskripsi' => 'Mengembangkan aplikasi pada sisi frontend dan backend serta mengelola integrasi sistem.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Mobile Developer',
                'deskripsi' => 'Membangun dan memelihara aplikasi mobile untuk platform Android maupun iOS.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'UI/UX Designer',
                'deskripsi' => 'Merancang antarmuka dan pengalaman pengguna yang menarik, intuitif, dan mudah digunakan.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'QA Tester',
                'deskripsi' => 'Melakukan pengujian aplikasi untuk memastikan kualitas, stabilitas, dan kesesuaian fungsi sistem.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'SEO Specialist',
                'deskripsi' => 'Mengoptimalkan website agar mendapatkan peringkat yang baik pada mesin pencari.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Digital Marketing Specialist',
                'deskripsi' => 'Merencanakan dan menjalankan strategi pemasaran digital untuk meningkatkan jangkauan dan konversi.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Social Media Specialist',
                'deskripsi' => 'Mengelola akun media sosial, membuat strategi konten, dan meningkatkan interaksi audiens.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Content Writer',
                'deskripsi' => 'Menulis dan menyusun konten informatif, edukatif, dan promosi sesuai kebutuhan bisnis.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Graphic Designer',
                'deskripsi' => 'Membuat desain visual untuk kebutuhan branding, promosi, dan komunikasi perusahaan.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Video Editor',
                'deskripsi' => 'Mengedit dan menyusun materi video menjadi konten yang menarik dan berkualitas.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Content Creator',
                'deskripsi' => 'Menciptakan berbagai jenis konten kreatif untuk mendukung pemasaran dan branding perusahaan.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Customer Support',
                'deskripsi' => 'Memberikan layanan dan bantuan kepada pelanggan serta menangani pertanyaan maupun keluhan.',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
            [
                'nama_job_role' => 'Klien',
                'deskripsi' => 'Pihak klien yang memantau progres dan memberikan feedback',
                'status' => true,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ],
        ]);
    }
}
