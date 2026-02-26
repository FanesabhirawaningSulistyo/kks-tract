<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriProjekSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori_projek')->insert([
            [
                'nama_kategori' => 'Web Development',
                'deskripsi' => 'Pembuatan dan pengembangan website',
                'status' => true,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now()
            ],
            [
                'nama_kategori' => 'Mobile Development',
                'deskripsi' => 'Pembuatan aplikasi Android dan iOS',
                'status' => true,   
                'dibuat_pada' => now(),
                'diperbarui_pada' => now()      
            ],
            [
                'nama_kategori' => 'SEO',
                'deskripsi' => 'Optimasi mesin pencari',
                'status' => true,                               
                'dibuat_pada' => now(),
                'diperbarui_pada' => now()
            ],
            [
                'nama_kategori' => 'Social Media Management',
                'deskripsi' => 'Pengelolaan media sosial',
                'status' => true,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now()
            ],
            [
                'nama_kategori' => 'UI/UX Design',
                'deskripsi' => 'Desain tampilan dan pengalaman pengguna',
                'status' => true,
                'dibuat_pada' => now(),
                'diperbarui_pada' => now()
            ],
        ]);
    }
}
