<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerusahaanSeeder extends Seeder
{
    // Data perwakilan dan alamat perusahaan
    private $perusahaanDetail = [
        [
            'perwakilan' => ['nama' => 'Ahmad Wijaya', 'email' => 'ahmad.wijaya@gmail.com', 'telepon' => '081234567801'],
            'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat'
        ],
        [
            'perwakilan' => ['nama' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@yahoo.com', 'telepon' => '081234567802'],
            'alamat' => 'Jl. Gatot Subroto Kav. 52, Jakarta Selatan'
        ],
        [
            'perwakilan' => ['nama' => 'Bambang Susanto', 'email' => 'bambang.susanto@outlook.com', 'telepon' => '081234567803'],
            'alamat' => 'Jl. TB Simatupang No. 88, Jakarta Selatan'
        ],
        [
            'perwakilan' => ['nama' => 'Dewi Lestari', 'email' => 'dewi.lestari@company.co.id', 'telepon' => '081234567804'],
            'alamat' => 'Jl. Rasuna Said Kav. 10, Jakarta Selatan'
        ],
        [
            'perwakilan' => ['nama' => 'Eko Prasetyo', 'email' => 'eko.prasetyo@business.id', 'telepon' => '081234567805'],
            'alamat' => 'Jl. Jend. Ahmad Yani No. 45, Jakarta Timur'
        ],
        [
            'perwakilan' => ['nama' => 'Fitri Handayani', 'email' => 'fitri.handayani@gmail.com', 'telepon' => '081234567806'],
            'alamat' => 'Jl. Boulevard Raya Blok LC6, Kelapa Gading'
        ],
        [
            'perwakilan' => ['nama' => 'Gunawan Setiawan', 'email' => 'gunawan.setiawan@yahoo.com', 'telepon' => '081234567807'],
            'alamat' => 'Jl. HR Rasuna Said Kav. C-22, Kuningan'
        ],
        [
            'perwakilan' => ['nama' => 'Heni Kusuma', 'email' => 'heni.kusuma@outlook.com', 'telepon' => '081234567808'],
            'alamat' => 'Jl. Jend. Sudirman Kav. 25, Jakarta Pusat'
        ],
        [
            'perwakilan' => ['nama' => 'Indra Wijaya', 'email' => 'indra.wijaya@company.co.id', 'telepon' => '081234567809'],
            'alamat' => 'Jl. MT Haryono Kav. 8, Cawang'
        ],
        [
            'perwakilan' => ['nama' => 'Julia Rahmawati', 'email' => 'julia.rahmawati@business.id', 'telepon' => '081234567810'],
            'alamat' => 'Jl. Pluit Raya No. 1, Jakarta Utara'
        ]
    ];

    public function run(): void
    {
        // Ambil 10 user dengan role klien (yang berisi data perusahaan)
        $perusahaanUsers = DB::table('users')
            ->where('role', 'klien')
            ->orderBy('id_user', 'asc')
            ->limit(10)
            ->get();

        if ($perusahaanUsers->count() < 10) {
            $this->command->error('❌ Error: Tidak ada cukup user klien. Pastikan UserSeeder sudah dijalankan terlebih dahulu.');
            $this->command->info('Jumlah user klien saat ini: ' . $perusahaanUsers->count());
            return;
        }

        $insertedCount = 0;
        $skippedCount = 0;

        // Buat 10 perusahaan yang berelasi dengan 10 user klien
        foreach ($perusahaanUsers as $index => $userPerusahaan) {
            $detailIndex = $index % count($this->perusahaanDetail);
            $detail = $this->perusahaanDetail[$detailIndex];

            // Cek apakah email perwakilan sudah ada
            $existingPerusahaan = DB::table('perusahaan')
                ->where('email_perwakilan', $detail['perwakilan']['email'])
                ->first();

            if ($existingPerusahaan) {
                $this->command->warn("⚠ Email perwakilan {$detail['perwakilan']['email']} sudah ada, melewati...");
                $skippedCount++;
                continue;
            }

            DB::table('perusahaan')->insert([
                // Relasi ke user yang berisi data perusahaan
                'id_user_perusahaan' => $userPerusahaan->id_user,

                // Data perwakilan perusahaan (PIC)
                'nama_perwakilan' => $detail['perwakilan']['nama'],
                'email_perwakilan' => $detail['perwakilan']['email'],
                'telepon_perwakilan' => $detail['perwakilan']['telepon'],

                // Data perusahaan lainnya
                'logo_perusahaan' => null,
                'alamat_perusahaan' => $detail['alamat'],

                'dibuat_pada' => now(),
                'diperbarui_pada' => now(),
            ]);

            $insertedCount++;
        }

        // Tampilkan ringkasan hasil
        $this->command->info("✓ Perusahaan seeder completed!");
        $this->command->info("  - Berhasil ditambahkan: {$insertedCount} perusahaan");
        if ($skippedCount > 0) {
            $this->command->warn("  - Dilewati (email duplikat): {$skippedCount} perusahaan");
        }

        // Tampilkan preview data (hanya 5 perusahaan pertama)
        $previewData = DB::table('perusahaan')
            ->join('users', 'perusahaan.id_user_perusahaan', '=', 'users.id_user')
            ->select(
                'perusahaan.id_perusahaan',
                'users.nama as nama_perusahaan',
                'users.email as email_perusahaan',
                'users.no_hp as telepon_perusahaan',
                'perusahaan.nama_perwakilan',
                'perusahaan.email_perwakilan',
                'perusahaan.alamat_perusahaan'
            )
            ->limit(5)
            ->get();

        if ($previewData->isNotEmpty()) {
            $tableData = [];
            foreach ($previewData as $row) {
                $tableData[] = [
                    $row->id_perusahaan,
                    $row->nama_perusahaan,
                    $row->email_perusahaan,
                    $row->nama_perwakilan,
                    $row->email_perwakilan,
                    substr($row->alamat_perusahaan, 0, 30) . '...'
                ];
            }

            $this->command->table(
                ['ID', 'Nama Perusahaan', 'Email Perusahaan', 'Perwakilan', 'Email Perwakilan', 'Alamat'],
                $tableData
            );

            $this->command->info("\n📝 Penjelasan Struktur Data:");
            $this->command->info("- Tabel USERS berisi: Nama Perusahaan, Email Perusahaan, Telepon Perusahaan");
            $this->command->info("- Tabel PERUSAHAAN berisi: Data Perwakilan (PIC) dan Alamat Perusahaan");
            $this->command->info("- id_user_perusahaan = relasi ke users.id_user (data perusahaan)");
        }
    }
}
