<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\JobRole;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan job roles sudah ada
        $jobRoles = JobRole::pluck('id_job_role', 'nama_job_role');

        if ($jobRoles->isEmpty()) {
            $this->command->error('❌ Job roles not found! Run JobRoleSeeder first.');
            return;
        }

        $users = [
            // ADMIN
            [
                'nama' => 'Admin Sistem',
                'email' => 'admin@kks.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'id_job_role' => $jobRoles['Administrator'] ?? null,
                'no_hp' => '081298765432',
                'foto' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // PROJECT MANAGER
            [
                'nama' => 'Andi Prasetyo',
                'email' => 'pm@kks.com',
                'password' => Hash::make('password123'),
                'role' => 'pm', // Pakai pm (huruf besar)
                'id_job_role' => $jobRoles['Project Manager'] ?? null,
                'no_hp' => '081233344455',
                'foto' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti nurul',
                'email' => 'nurul@kks.com',
                'password' => Hash::make('password123'),
                'role' => 'pm', // Pakai pm (huruf besar)
                'id_job_role' => $jobRoles['Project Manager'] ?? null,
                'no_hp' => '081233344455',
                'foto' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // KARYAWAN
            [
                'nama' => 'Rizki Ramadhan',
                'email' => 'rizki@kks.com',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
                'id_job_role' => $jobRoles['Web Developer'] ?? null,
                'no_hp' => '081212121212',
                'foto' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Aminah',
                'email' => 'siti@kks.com',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
                'id_job_role' => $jobRoles['UI/UX Designer'] ?? null,
                'no_hp' => '081311122233',
                'foto' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Doni Saputra',
                'email' => 'doni@kks.com',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
                'id_job_role' => $jobRoles['SEO Specialist'] ?? null,
                'no_hp' => '081355577788',
                'foto' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }

        $totalUsers = DB::table('users')->count();
        $this->command->info("✓ UserSeeder completed: {$totalUsers} total users");

        // Tampilkan statistik
        $stats = DB::table('users')
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        foreach ($stats as $stat) {
            $this->command->line("  - {$stat->role}: {$stat->total} users");
        }
    }
}
