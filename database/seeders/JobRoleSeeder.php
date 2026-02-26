<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('job_roles')->insert([
            [
                'nama_job_role' => 'Administrator',
                'deskripsi' => 'Mengelola sistem, data, dan hak akses pengguna',
                'status' => true,
                'dibuat_pada' => Carbon::now(),
                'diperbarui_pada' => Carbon::now(),
            ],
            [
                'nama_job_role' => 'Project Manager',
                'deskripsi' => 'Mengelola proyek, tim, timeline, dan komunikasi klien',
                'status' => true,
                'dibuat_pada' => Carbon::now(),
                'diperbarui_pada' => Carbon::now(),
            ],
            [
                'nama_job_role' => 'Klien',
                'deskripsi' => 'Pihak klien yang memantau progres dan memberikan feedback',
                'status' => true,
                'dibuat_pada' => Carbon::now(), 
                'diperbarui_pada' => Carbon::now(),
            ],
            [
                'nama_job_role' => 'Web Developer',
                'deskripsi' => 'Mengembangkan dan memelihara aplikasi berbasis web',
                'status' => true,
                'dibuat_pada' => Carbon::now(),
                'diperbarui_pada' => Carbon::now(),
            ],
            [
                'nama_job_role' => 'Full Stack Developer',
                'deskripsi' => 'Menangani pengembangan backend dan frontend',
                'status' => true,
                'dibuat_pada' => Carbon::now(),
                'diperbarui_pada' => Carbon::now(),
            ],
            [
                'nama_job_role' => 'SEO Specialist',
                'deskripsi' => 'Optimasi website agar mudah ditemukan di mesin pencari',
                'status' => true,
                'dibuat_pada' => Carbon::now(), 
                'diperbarui_pada' => Carbon::now(),
            ],
            [
                'nama_job_role' => 'UI/UX Designer',
                'deskripsi' => 'Merancang tampilan dan pengalaman pengguna aplikasi',
                'status' => true,
                'dibuat_pada' => Carbon::now(),
                'diperbarui_pada' => Carbon::now(),
            ],
            [
                'nama_job_role' => 'Digital Marketing',
                'deskripsi' => 'Mengelola strategi pemasaran digital dan kampanye online',
                'status' => true,
                'dibuat_pada' => Carbon::now(),
                'diperbarui_pada' => Carbon::now(),
            ],
            
        ]);
    }
}
