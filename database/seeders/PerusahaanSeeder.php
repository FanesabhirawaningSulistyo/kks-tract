<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerusahaanSeeder extends Seeder
{
    private $perusahaanData = [
        [
            'perusahaan' => [
                'nama'    => 'PT Abadi Jaya',
                'email'   => 'info@abadi-jaya.co.id',
                'telepon' => '021-5234567',
            ],
            'perwakilan' => [
                'nama'    => 'Ahmad Wijaya',
                'email'   => 'ahmad.wijaya@gmail.com',
                'telepon' => '081234567801',
            ],
            'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
        ],
        [
            'perusahaan' => [
                'nama'    => 'PT Makmur Sentosa',
                'email'   => 'contact@makmursentosa.com',
                'telepon' => '021-5678901',
            ],
            'perwakilan' => [
                'nama'    => 'Siti Nurhaliza',
                'email'   => 'siti.nurhaliza@yahoo.com',
                'telepon' => '081234567802',
            ],
            'alamat' => 'Jl. Gatot Subroto Kav. 52, Jakarta Selatan',
        ],
        [
            'perusahaan' => [
                'nama'    => 'PT Sejahtera Bersama',
                'email'   => 'info@sejahterabersama.co.id',
                'telepon' => '021-8765432',
            ],
            'perwakilan' => [
                'nama'    => 'Bambang Susanto',
                'email'   => 'bambang.susanto@outlook.com',
                'telepon' => '081234567803',
            ],
            'alamat' => 'Jl. TB Simatupang No. 88, Jakarta Selatan',
        ],
        [
            'perusahaan' => [
                'nama'    => 'PT Surya Mandiri',
                'email'   => 'corporate@suryamandiri.com',
                'telepon' => '021-7890123',
            ],
            'perwakilan' => [
                'nama'    => 'Dewi Lestari',
                'email'   => 'dewi.lestari@company.co.id',
                'telepon' => '081234567804',
            ],
            'alamat' => 'Jl. Rasuna Said Kav. 10, Jakarta Selatan',
        ],
        [
            'perusahaan' => [
                'nama'    => 'PT Bintang Emas',
                'email'   => 'hello@bintangemas.co.id',
                'telepon' => '021-6543210',
            ],
            'perwakilan' => [
                'nama'    => 'Eko Prasetyo',
                'email'   => 'eko.prasetyo@business.id',
                'telepon' => '081234567805',
            ],
            'alamat' => 'Jl. Jend. Ahmad Yani No. 45, Jakarta Timur',
        ],
        [
            'perusahaan' => [
                'nama'    => 'PT Cahaya Utama',
                'email'   => 'admin@cahayautama.com',
                'telepon' => '021-4321098',
            ],
            'perwakilan' => [
                'nama'    => 'Fitri Handayani',
                'email'   => 'fitri.handayani@gmail.com',
                'telepon' => '081234567806',
            ],
            'alamat' => 'Jl. Boulevard Raya Blok LC6, Kelapa Gading',
        ],
        [
            'perusahaan' => [
                'nama'    => 'PT Maju Jaya',
                'email'   => 'info@majujaya.co.id',
                'telepon' => '021-3210987',
            ],
            'perwakilan' => [
                'nama'    => 'Gunawan Setiawan',
                'email'   => 'gunawan.setiawan@yahoo.com',
                'telepon' => '081234567807',
            ],
            'alamat' => 'Jl. HR Rasuna Said Kav. C-22, Kuningan',
        ],
        [
            'perusahaan' => [
                'nama'    => 'PT Global Tech',
                'email'   => 'contact@globaltech.co.id',
                'telepon' => '021-2109876',
            ],
            'perwakilan' => [
                'nama'    => 'Heni Kusuma',
                'email'   => 'heni.kusuma@outlook.com',
                'telepon' => '081234567808',
            ],
            'alamat' => 'Jl. Jend. Sudirman Kav. 25, Jakarta Pusat',
        ],
        [
            'perusahaan' => [
                'nama'    => 'PT Nusantara Indah',
                'email'   => 'info@nusantaraindah.com',
                'telepon' => '021-1098765',
            ],
            'perwakilan' => [
                'nama'    => 'Indra Wijaya',
                'email'   => 'indra.wijaya@company.co.id',
                'telepon' => '081234567809',
            ],
            'alamat' => 'Jl. MT Haryono Kav. 8, Cawang',
        ],
        [
            'perusahaan' => [
                'nama'    => 'PT Karya Bangsa',
                'email'   => 'corporate@karyabangsa.co.id',
                'telepon' => '021-9876543',
            ],
            'perwakilan' => [
                'nama'    => 'Julia Rahmawati',
                'email'   => 'julia.rahmawati@business.id',
                'telepon' => '081234567810',
            ],
            'alamat' => 'Jl. Pluit Raya No. 1, Jakarta Utara',
        ],
    ];

    public function run(): void
    {
        $jobRoles = DB::table('job_roles')->pluck('id_job_role', 'nama_job_role');

        $insertedCount = 0;
        $skippedCount  = 0;

        foreach ($this->perusahaanData as $data) {
            // Cek duplikat email perusahaan di tabel users
            if (DB::table('users')->where('email', $data['perusahaan']['email'])->exists()) {
                $this->command->warn("⚠ Email perusahaan {$data['perusahaan']['email']} sudah ada, melewati...");
                $skippedCount++;
                continue;
            }

            // Cek duplikat email perwakilan di tabel perusahaan
            if (DB::table('perusahaan')->where('email_perwakilan', $data['perwakilan']['email'])->exists()) {
                $this->command->warn("⚠ Email perwakilan {$data['perwakilan']['email']} sudah ada, melewati...");
                $skippedCount++;
                continue;
            }

            // 1. Buat akun user untuk PERUSAHAAN (nama, email, telepon perusahaan)
            $userId = DB::table('users')->insertGetId([
                'nama'       => $data['perusahaan']['nama'],    // Nama Perusahaan
                'email'      => $data['perusahaan']['email'],   // Email Perusahaan
                'password'   => Hash::make('password123'),
                'role'       => 'klien',
                'id_job_role' => $jobRoles['Klien'] ?? null,
                'no_hp'      => $data['perusahaan']['telepon'], // Telepon Perusahaan
                'foto'       => null,
                'status'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Buat data perusahaan dengan data PERWAKILAN (PIC)
            DB::table('perusahaan')->insert([
                'id_user_perusahaan' => $userId,
                'nama_perwakilan'    => $data['perwakilan']['nama'],
                'email_perwakilan'   => $data['perwakilan']['email'],
                'telepon_perwakilan' => $data['perwakilan']['telepon'],
                'logo_perusahaan'    => null,
                'alamat_perusahaan'  => $data['alamat'],
                'dibuat_pada'        => now(),
                'diperbarui_pada'    => now(),
            ]);

            $insertedCount++;
        }

        $this->command->info("✓ Perusahaan seeder completed!");
        $this->command->info("  - Berhasil ditambahkan: {$insertedCount} perusahaan");
        if ($skippedCount > 0) {
            $this->command->warn("  - Dilewati (duplikat): {$skippedCount}");
        }

        // Preview
        $preview = DB::table('perusahaan')
            ->join('users', 'perusahaan.id_user_perusahaan', '=', 'users.id_user')
            ->select(
                'perusahaan.id_perusahaan',
                'users.nama as nama_perusahaan',
                'users.email as email_perusahaan',
                'users.no_hp as telepon_perusahaan',
                'perusahaan.nama_perwakilan',
                'perusahaan.email_perwakilan'
            )
            ->limit(5)
            ->get();

        if ($preview->isNotEmpty()) {
            $tableData = $preview->map(fn($r) => [
                $r->id_perusahaan,
                $r->nama_perusahaan,
                $r->email_perusahaan,
                $r->telepon_perusahaan,
                $r->nama_perwakilan,
                $r->email_perwakilan,
            ])->toArray();

            $this->command->table(
                ['ID', 'Nama Perusahaan', 'Email Perusahaan', 'Telepon', 'Perwakilan', 'Email Perwakilan'],
                $tableData
            );
        }
    }
}
