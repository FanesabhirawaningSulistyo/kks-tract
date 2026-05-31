<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class PerusahaanSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $perusahaan = [
            // -------------------------------------------------------
            // Perusahaan 1
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Digital Nusantara',
                'email_perusahaan'   => 'digitalnusantara@gmail.com',
                'telepon_perusahaan' => '0211234567',
                'nama_perwakilan'    => 'Budi Santoso',
                'email_perwakilan'   => 'budisantoso@gmail.com',
                'telepon_perwakilan' => '081200000001',
                'alamat_perusahaan'  => 'Jakarta',
            ],
            // -------------------------------------------------------
            // Perusahaan 2
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'CV Kreasi Media',
                'email_perusahaan'   => 'kreasimedia@gmail.com',
                'telepon_perusahaan' => '0222234567',
                'nama_perwakilan'    => 'Sinta Maharani',
                'email_perwakilan'   => 'sintamaharani@gmail.com',
                'telepon_perwakilan' => '081200000002',
                'alamat_perusahaan'  => 'Bandung',
            ],
            // -------------------------------------------------------
            // Perusahaan 3
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Solusi Teknologi',
                'email_perusahaan'   => 'solusiteknologi@gmail.com',
                'telepon_perusahaan' => '0313234567',
                'nama_perwakilan'    => 'Ahmad Ramadhan',
                'email_perwakilan'   => 'ahmadramadhan@gmail.com',
                'telepon_perwakilan' => '081200000003',
                'alamat_perusahaan'  => 'Surabaya',
            ],
            // -------------------------------------------------------
            // Perusahaan 4
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Inovasi Kreatif',
                'email_perusahaan'   => 'inovasikreatif@gmail.com',
                'telepon_perusahaan' => '0274234567',
                'nama_perwakilan'    => 'Dinda Permata',
                'email_perwakilan'   => 'dindapermata@gmail.com',
                'telepon_perwakilan' => '081200000004',
                'alamat_perusahaan'  => 'Yogyakarta',
            ],
            // -------------------------------------------------------
            // Perusahaan 5
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Maju Bersama',
                'email_perusahaan'   => 'majubersama@gmail.com',
                'telepon_perusahaan' => '0243234567',
                'nama_perwakilan'    => 'Fajar Nugroho',
                'email_perwakilan'   => 'fajarnugroho@gmail.com',
                'telepon_perwakilan' => '081200000005',
                'alamat_perusahaan'  => 'Semarang',
            ],
            // -------------------------------------------------------
            // Perusahaan 6
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Global Media',
                'email_perusahaan'   => 'globalmedia@gmail.com',
                'telepon_perusahaan' => '0616234567',
                'nama_perwakilan'    => 'Rizky Hidayat',
                'email_perwakilan'   => 'rizkyhidayat@gmail.com',
                'telepon_perwakilan' => '081200000006',
                'alamat_perusahaan'  => 'Medan',
            ],
            // -------------------------------------------------------
            // Perusahaan 7
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Nusantara Tech',
                'email_perusahaan'   => 'nusantaratech@gmail.com',
                'telepon_perusahaan' => '0411234567',
                'nama_perwakilan'    => 'Kevin Saputra',
                'email_perwakilan'   => 'kevinsaputra@gmail.com',
                'telepon_perwakilan' => '081200000007',
                'alamat_perusahaan'  => 'Makassar',
            ],
            // -------------------------------------------------------
            // Perusahaan 8
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Cipta Karya Digital',
                'email_perusahaan'   => 'ciptakaryadigital@gmail.com',
                'telepon_perusahaan' => '0711234567',
                'nama_perwakilan'    => 'Rina Amelia',
                'email_perwakilan'   => 'rinaamelia@gmail.com',
                'telepon_perwakilan' => '081200000008',
                'alamat_perusahaan'  => 'Palembang',
            ],
            // -------------------------------------------------------
            // Perusahaan 9
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Mitra Informatika',
                'email_perusahaan'   => 'mitrainformatika@gmail.com',
                'telepon_perusahaan' => '0341234567',
                'nama_perwakilan'    => 'Aldi Prasetyo',
                'email_perwakilan'   => 'aldiprasetyo@gmail.com',
                'telepon_perwakilan' => '081200000009',
                'alamat_perusahaan'  => 'Malang',
            ],
            // -------------------------------------------------------
            // Perusahaan 10
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Vision Creative',
                'email_perusahaan'   => 'visioncreative@gmail.com',
                'telepon_perusahaan' => '0361234567',
                'nama_perwakilan'    => 'Vina Oktavia',
                'email_perwakilan'   => 'vinaoktavia@gmail.com',
                'telepon_perwakilan' => '081200000010',
                'alamat_perusahaan'  => 'Denpasar',
            ],
            // -------------------------------------------------------
            // Perusahaan 11
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Data Solution',
                'email_perusahaan'   => 'datasolution@gmail.com',
                'telepon_perusahaan' => '0542234567',
                'nama_perwakilan'    => 'Yogi Saputro',
                'email_perwakilan'   => 'yogisaputro@gmail.com',
                'telepon_perwakilan' => '081200000011',
                'alamat_perusahaan'  => 'Balikpapan',
            ],
            // -------------------------------------------------------
            // Perusahaan 12
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Smart Digital',
                'email_perusahaan'   => 'smartdigital@gmail.com',
                'telepon_perusahaan' => '0761234567',
                'nama_perwakilan'    => 'Farhan Akbar',
                'email_perwakilan'   => 'farhanakbar@gmail.com',
                'telepon_perwakilan' => '081200000012',
                'alamat_perusahaan'  => 'Pekanbaru',
            ],
            // -------------------------------------------------------
            // Perusahaan 13
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Creative Studio',
                'email_perusahaan'   => 'creativestudio@gmail.com',
                'telepon_perusahaan' => '0561234567',
                'nama_perwakilan'    => 'Salsa Aulia',
                'email_perwakilan'   => 'salsaaulia@gmail.com',
                'telepon_perwakilan' => '081200000013',
                'alamat_perusahaan'  => 'Pontianak',
            ],
            // -------------------------------------------------------
            // Perusahaan 14
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Media Inovatif',
                'email_perusahaan'   => 'mediainovatif@gmail.com',
                'telepon_perusahaan' => '0541234567',
                'nama_perwakilan'    => 'Citra Maharani',
                'email_perwakilan'   => 'citramaharani@gmail.com',
                'telepon_perwakilan' => '081200000014',
                'alamat_perusahaan'  => 'Samarinda',
            ],
            // -------------------------------------------------------
            // Perusahaan 15
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Future Technology',
                'email_perusahaan'   => 'futuretechnology@gmail.com',
                'telepon_perusahaan' => '0431234567',
                'nama_perwakilan'    => 'Reza Maulana',
                'email_perwakilan'   => 'rezamaulana@gmail.com',
                'telepon_perwakilan' => '081200000015',
                'alamat_perusahaan'  => 'Manado',
            ],
            // -------------------------------------------------------
            // Perusahaan 16
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Teknologi Hebat',
                'email_perusahaan'   => 'teknologihebat@gmail.com',
                'telepon_perusahaan' => '0751234567',
                'nama_perwakilan'    => 'Nadia Putri',
                'email_perwakilan'   => 'nadiaputri@gmail.com',
                'telepon_perwakilan' => '081200000016',
                'alamat_perusahaan'  => 'Padang',
            ],
            // -------------------------------------------------------
            // Perusahaan 17
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Berkah Digital',
                'email_perusahaan'   => 'berkahdigital@gmail.com',
                'telepon_perusahaan' => '0511234567',
                'nama_perwakilan'    => 'Bagas Firmansyah',
                'email_perwakilan'   => 'bagasfirmansyah@gmail.com',
                'telepon_perwakilan' => '081200000017',
                'alamat_perusahaan'  => 'Banjarmasin',
            ],
            // -------------------------------------------------------
            // Perusahaan 18
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Prima Teknologi',
                'email_perusahaan'   => 'primateknologi@gmail.com',
                'telepon_perusahaan' => '0741234567',
                'nama_perwakilan'    => 'Tiara Anindya',
                'email_perwakilan'   => 'tiaraanindya@gmail.com',
                'telepon_perwakilan' => '081200000018',
                'alamat_perusahaan'  => 'Jambi',
            ],
            // -------------------------------------------------------
            // Perusahaan 19
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Kreatif Nusantara',
                'email_perusahaan'   => 'kreatifnusantara@gmail.com',
                'telepon_perusahaan' => '0380234567',
                'nama_perwakilan'    => 'Dimas Saputra',
                'email_perwakilan'   => 'dimassaputra@gmail.com',
                'telepon_perwakilan' => '081200000019',
                'alamat_perusahaan'  => 'Kupang',
            ],
            // -------------------------------------------------------
            // Perusahaan 20
            // -------------------------------------------------------
            [
                'nama_perusahaan'    => 'PT Infinity Solution',
                'email_perusahaan'   => 'infinitysolution@gmail.com',
                'telepon_perusahaan' => '0911234567',
                'nama_perwakilan'    => 'Andika Putra',
                'email_perwakilan'   => 'andikaputra@gmail.com',
                'telepon_perwakilan' => '081200000020',
                'alamat_perusahaan'  => 'Ambon',
            ],
        ];

        // id_job_role untuk role 'klien' = 16 (sesuai JobRoleSeeder)
        $jobRoleKlien = 16;

        // id_user dimulai dari 24 (lanjutan dari UserSeeder)
        $startUserId = 24;

        foreach ($perusahaan as $index => $item) {
            $userId = $startUserId + $index;

            // -------------------------------------------------------
            // 1. Insert user dengan nama & email perusahaan, role klien
            // -------------------------------------------------------
            DB::table('users')->insert([
                'id_user'        => $userId,
                'nama'           => $item['nama_perusahaan'],
                'email'          => $item['email_perusahaan'],
                'password'       => Hash::make('password123'),
                'role'           => 'klien',
                'id_job_role'    => $jobRoleKlien,
                'no_hp'          => $item['telepon_perusahaan'],
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            // -------------------------------------------------------
            // 2. Insert perusahaan dengan id_user_perusahaan yang baru dibuat
            // -------------------------------------------------------
            DB::table('perusahaan')->insert([
                'id_user_perusahaan'  => $userId,
                'nama_perusahaan'     => $item['nama_perusahaan'],
                'email_perusahaan'    => $item['email_perusahaan'],
                'telepon_perusahaan'  => $item['telepon_perusahaan'],
                'nama_perwakilan'     => $item['nama_perwakilan'],
                'email_perwakilan'    => $item['email_perwakilan'],
                'telepon_perwakilan'  => $item['telepon_perwakilan'],
                'logo_perusahaan'     => null,
                'alamat_perusahaan'   => $item['alamat_perusahaan'],
                'dibuat_pada'         => $now,
                'diperbarui_pada'     => $now,
            ]);
        }

        // Reset auto increment kedua tabel
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 43;');
        DB::statement('ALTER TABLE perusahaan AUTO_INCREMENT = 21;');
    }
}
