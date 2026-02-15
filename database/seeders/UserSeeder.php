<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil mapping: nama_job_role => id_job_role
        $jobRoles = DB::table('job_roles')
            ->pluck('id_job_role', 'nama_job_role');

        // Data perwakilan perusahaan (bukan user klien lagi)
        $perwakilanData = [
            ['nama' => 'Ahmad Wijaya', 'email' => 'ahmad.wijaya@gmail.com', 'no_hp' => '081234567801'],
            ['nama' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@yahoo.com', 'no_hp' => '081234567802'],
            ['nama' => 'Bambang Susanto', 'email' => 'bambang.susanto@outlook.com', 'no_hp' => '081234567803'],
            ['nama' => 'Dewi Lestari', 'email' => 'dewi.lestari@company.co.id', 'no_hp' => '081234567804'],
            ['nama' => 'Eko Prasetyo', 'email' => 'eko.prasetyo@business.id', 'no_hp' => '081234567805'],
            ['nama' => 'Fitri Handayani', 'email' => 'fitri.handayani@gmail.com', 'no_hp' => '081234567806'],
            ['nama' => 'Gunawan Setiawan', 'email' => 'gunawan.setiawan@yahoo.com', 'no_hp' => '081234567807'],
            ['nama' => 'Heni Kusuma', 'email' => 'heni.kusuma@outlook.com', 'no_hp' => '081234567808'],
            ['nama' => 'Indra Wijaya', 'email' => 'indra.wijaya@company.co.id', 'no_hp' => '081234567809'],
            ['nama' => 'Julia Rahmawati', 'email' => 'julia.rahmawati@business.id', 'no_hp' => '081234567810'],
        ];

        // Data perusahaan untuk user klien
        $perusahaanUserData = [
            ['nama' => 'PT Abadi Jaya', 'email' => 'info@abadi-jaya.co.id', 'telepon' => '021-5234567'],
            ['nama' => 'PT Makmur Sentosa', 'email' => 'contact@makmursentosa.com', 'telepon' => '021-5678901'],
            ['nama' => 'PT Sejahtera Bersama', 'email' => 'info@sejahterabersama.co.id', 'telepon' => '021-8765432'],
            ['nama' => 'PT Surya Mandiri', 'email' => 'corporate@suryamandiri.com', 'telepon' => '021-7890123'],
            ['nama' => 'PT Bintang Emas', 'email' => 'hello@bintangemas.co.id', 'telepon' => '021-6543210'],
            ['nama' => 'PT Cahaya Utama', 'email' => 'admin@cahayautama.com', 'telepon' => '021-4321098'],
            ['nama' => 'PT Maju Jaya', 'email' => 'info@majujaya.co.id', 'telepon' => '021-3210987'],
            ['nama' => 'PT Global Tech', 'email' => 'contact@globaltech.co.id', 'telepon' => '021-2109876'],
            ['nama' => 'PT Nusantara Indah', 'email' => 'info@nusantaraindah.com', 'telepon' => '021-1098765'],
            ['nama' => 'PT Karya Bangsa', 'email' => 'corporate@karyabangsa.co.id', 'telepon' => '021-9876543'],
        ];

        DB::table('users')->insert([
            // ===== ADMIN =====
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
            // ===== PROJECT MANAGER =====
            [
                'nama' => 'Andi Prasetyo',
                'email' => 'pm@kks.com',
                'password' => Hash::make('password123'),
                'role' => 'pm',
                'id_job_role' => $jobRoles['Project Manager'] ?? null,
                'no_hp' => '081233344455',
                'foto' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ===== KARYAWAN =====
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
        ]);

        // ===== 10 USER KLIEN (Data Perusahaan) =====
        // User ini akan berisi: nama perusahaan, email perusahaan, telepon perusahaan
        foreach ($perusahaanUserData as $perusahaan) {
            DB::table('users')->insert([
                'nama' => $perusahaan['nama'], // NAMA PERUSAHAAN
                'email' => $perusahaan['email'], // EMAIL PERUSAHAAN
                'password' => Hash::make('password123'),
                'role' => 'klien',
                'id_job_role' => $jobRoles['Klien'] ?? null,
                'no_hp' => $perusahaan['telepon'], // TELEPON PERUSAHAAN
                'foto' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✓ User seeder completed: " . DB::table('users')->count() . " users created\n";

        // Validasi user klien
        $klienCount = DB::table('users')
            ->where('role', 'klien')
            ->count();
        echo "✓ User klien (perusahaan): {$klienCount} users\n";
    }
}
