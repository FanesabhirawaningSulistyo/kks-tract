<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $users = [
            // -------------------------------------------------------
            // ADMIN
            // -------------------------------------------------------
            [
                'id_user'        => 1,
                'nama'           => 'Administrator',
                'email'          => 'admin@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'admin',
                'id_job_role'    => 1,
                'no_hp'          => '081234567890',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // PM (Project Manager)
            // -------------------------------------------------------
            [
                'id_user'        => 2,
                'nama'           => 'Fanesa PM',
                'email'          => 'pm@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'PM',
                'id_job_role'    => 2,
                'no_hp'          => '081234567891',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Web Developer
            // -------------------------------------------------------
            [
                'id_user'        => 3,
                'nama'           => 'Ahmad Fauzi',
                'email'          => 'webdeveloper1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 3,
                'no_hp'          => '081234567892',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id_user'        => 4,
                'nama'           => 'Dimas Saputra',
                'email'          => 'webdeveloper2@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 3,
                'no_hp'          => '081234567893',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id_user'        => 5,
                'nama'           => 'Rizky Pratama',
                'email'          => 'webdeveloper3@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 3,
                'no_hp'          => '081234567894',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Fullstack Developer
            // -------------------------------------------------------
            [
                'id_user'        => 6,
                'nama'           => 'Nanda Wijaya',
                'email'          => 'fullstack1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 4,
                'no_hp'          => '081234567895',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id_user'        => 7,
                'nama'           => 'Fajar Ramadhan',
                'email'          => 'fullstack2@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 4,
                'no_hp'          => '081234567896',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Mobile Developer
            // -------------------------------------------------------
            [
                'id_user'        => 8,
                'nama'           => 'Andika Putra',
                'email'          => 'mobiledeveloper1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 5,
                'no_hp'          => '081234567897',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id_user'        => 9,
                'nama'           => 'Reza Maulana',
                'email'          => 'mobiledeveloper2@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 5,
                'no_hp'          => '081234567898',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // UI/UX Designer
            // -------------------------------------------------------
            [
                'id_user'        => 10,
                'nama'           => 'Salsa Aulia',
                'email'          => 'uiux1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 6,
                'no_hp'          => '081234567899',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id_user'        => 11,
                'nama'           => 'Citra Maharani',
                'email'          => 'uiux2@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 6,
                'no_hp'          => '081234567900',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // QA Tester
            // -------------------------------------------------------
            [
                'id_user'        => 12,
                'nama'           => 'Bagas Firmansyah',
                'email'          => 'qatester1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 7,
                'no_hp'          => '081234567901',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id_user'        => 13,
                'nama'           => 'Yogi Kurniawan',
                'email'          => 'qatester2@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 7,
                'no_hp'          => '081234567902',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // SEO Specialist
            // -------------------------------------------------------
            [
                'id_user'        => 14,
                'nama'           => 'Intan Permata',
                'email'          => 'seo1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 8,
                'no_hp'          => '081234567903',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'id_user'        => 15,
                'nama'           => 'Dwi Cahyo',
                'email'          => 'seo2@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 8,
                'no_hp'          => '081234567904',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Digital Marketing
            // -------------------------------------------------------
            [
                'id_user'        => 16,
                'nama'           => 'Nadia Putri',
                'email'          => 'digitalmarketing1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 9,
                'no_hp'          => '081234567905',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Social Media Specialist
            // -------------------------------------------------------
            [
                'id_user'        => 17,
                'nama'           => 'Vina Oktaviani',
                'email'          => 'socialmedia1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 10,
                'no_hp'          => '081234567906',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Content Writer
            // -------------------------------------------------------
            [
                'id_user'        => 18,
                'nama'           => 'Rara Amelia',
                'email'          => 'contentwriter1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 11,
                'no_hp'          => '081234567907',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Graphic Designer
            // -------------------------------------------------------
            [
                'id_user'        => 19,
                'nama'           => 'Kevin Christian',
                'email'          => 'graphicdesigner1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 12,
                'no_hp'          => '081234567908',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Video Editor
            // -------------------------------------------------------
            [
                'id_user'        => 20,
                'nama'           => 'Farhan Akbar',
                'email'          => 'videoeditor1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 13,
                'no_hp'          => '081234567909',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Content Creator
            // -------------------------------------------------------
            [
                'id_user'        => 21,
                'nama'           => 'Tiara Anindya',
                'email'          => 'contentcreator1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 14,
                'no_hp'          => '081234567910',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            // -------------------------------------------------------
            // Customer Support
            // -------------------------------------------------------
            [
                'id_user'        => 22,
                'nama'           => 'Aldi Saputra',
                'email'          => 'customersupport1@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'karyawan',
                'id_job_role'    => 15,
                'no_hp'          => '081234567911',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],

            [   
                'id_user'        => 23,
                'nama'           => 'Riska Kurnia',
                'email'          => 'riskakurnia@gmail.com',
                'password'       => Hash::make('password123'),
                'role'           => 'PM',
                'id_job_role'    => 2,
                'no_hp'          => '089787666565',
                'foto'           => null,
                'status'         => true,
                'remember_token' => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ];

        DB::table('users')->insert($users);

        // Reset auto increment setelah insert manual
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 23;');
    }
}
