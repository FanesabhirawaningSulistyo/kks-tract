<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\JobRole;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $jobRoles = JobRole::whereNotIn('nama_job_role', [
            'Administrator',
            'Project Manager'
        ])->get();

        if ($jobRoles->isEmpty()) {
            $this->command->error('❌ Job roles tidak ditemukan!');
            return;
        }

        $users = [];

        foreach ($jobRoles as $role) {

            // ✅ Random jumlah karyawan (1 - 2)
            $jumlah = rand(1, 2);

            for ($i = 1; $i <= $jumlah; $i++) {
                $users[] = [
                    'nama' => $role->nama_job_role . ' ' . $i,
                    'email' => strtolower(str_replace(' ', '', $role->nama_job_role)) . $i . rand(10, 99) . '@kks.com',
                    'password' => Hash::make('password123'),
                    'role' => $role->nama_job_role == 'Klien' ? 'klien' : 'karyawan',
                    'id_job_role' => $role->id_job_role,
                    'no_hp' => '0812' . rand(10000000, 99999999),
                    'foto' => null,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }

        $this->command->info("✅ Seeder karyawan: " . count($users) . " user dibuat");
    }
}
