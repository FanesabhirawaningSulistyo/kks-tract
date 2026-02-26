<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(JobRoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PerusahaanSeeder::class);
        $this->call(KategoriProjekSeeder::class);
        $this->call(ProjekSeeder::class);
        $this->call(ProjekTimSeeder::class);
        $this->call(TugasSeeder::class);
    }
}
