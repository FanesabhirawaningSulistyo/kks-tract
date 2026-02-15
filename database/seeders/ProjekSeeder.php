<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjekSeeder extends Seeder
{
    private $namaProyek = [
        'Website Company Profile',
        'Aplikasi Mobile E-Commerce',
        'Sistem Manajemen Inventory',
        'Website Portal Berita',
        'Aplikasi Pembelajaran Online',
        'Sistem Reservasi Hotel',
        'Aplikasi Delivery Food',
        'Website Marketplace',
        'Sistem HRD & Payroll',
        'Aplikasi Manajemen Proyek',
        'Website Landing Page',
        'Sistem CRM Perusahaan',
        'Aplikasi Mobile Banking',
        'Website E-Learning Platform',
        'Sistem Point of Sale',
        'Aplikasi Tracking Logistik',
        'Website Booking Event',
        'Sistem Manajemen Klinik',
        'Aplikasi Social Media',
        'Website Portfolio Company',
        'Sistem Warehouse Management',
        'Aplikasi Fitness Tracker',
        'Website Corporate Dashboard',
        'Sistem Accounting Software',
        'Aplikasi Real Estate Listing'
    ];

    private $kategori = [
        'Website',
        'Mobile App',
        'Desktop App',
        'System',
        'API Integration',
        'Web Application'
    ];

    private $deskripsi = [
        'Pengembangan sistem informasi terintegrasi dengan fitur lengkap dan user-friendly',
        'Pembuatan aplikasi modern dengan teknologi terkini dan performa optimal',
        'Solusi digital untuk meningkatkan efisiensi operasional bisnis',
        'Platform berbasis web dengan design responsif dan fitur interaktif',
        'Sistem manajemen yang dapat diandalkan dengan keamanan tingkat tinggi',
        'Aplikasi mobile native dengan performa cepat dan UX yang baik',
        'Website company profile yang profesional dan informatif',
        'E-commerce solution dengan payment gateway terintegrasi',
        'Custom software development sesuai kebutuhan bisnis klien',
        'API development untuk integrasi sistem eksternal',
        'Dashboard monitoring real-time dengan visualisasi data',
        'Mobile application dengan fitur GPS tracking dan notification',
        'Web portal dengan multi-user access dan role management',
        'Sistem inventory dengan barcode scanner integration',
        'Booking system dengan calendar dan reminder otomatis',
        'CRM system untuk meningkatkan customer relationship',
        'HRD software untuk pengelolaan karyawan dan payroll',
        'Point of Sale system dengan inventory management',
        'Logistic tracking system dengan real-time monitoring',
        'Learning management system dengan video streaming',
        'Social media platform dengan fitur lengkap',
        'Healthcare management system terintegrasi',
        'Event booking platform dengan payment gateway',
        'Warehouse management dengan barcode system',
        'Financial accounting system dengan reporting'
    ];

    public function run(): void
    {
        // Ambil admin untuk dijadikan pembuat projek
        $adminIds = DB::table('users')
            ->where('role', 'admin')
            ->pluck('id_user')
            ->toArray();

        if (empty($adminIds)) {
            echo "❌ Error: Tidak ada user admin. Pastikan UserSeeder sudah dijalankan.\n";
            return;
        }

        // Ambil semua perusahaan yang ada (harusnya 10)
        $perusahaanIds = DB::table('perusahaan')
            ->pluck('id_perusahaan')
            ->toArray();

        if (empty($perusahaanIds)) {
            echo "❌ Error: Tidak ada perusahaan. Pastikan PerusahaanSeeder sudah dijalankan.\n";
            return;
        }

        $statusList = ['pending', 'disetujui', 'berjalan', 'selesai', 'batal'];

        // Buat 25 projek dengan data yang lebih realistis
        for ($i = 0; $i < 25; $i++) {
            // Tentukan tanggal mulai secara acak
            $bulanLalu = rand(1, 6); // 1-6 bulan yang lalu
            $tanggalPesan = Carbon::now()->subMonths($bulanLalu)->subDays(rand(0, 28));
            $tanggalMulai = (clone $tanggalPesan)->addDays(rand(7, 30)); // Mulai 1-4 minggu setelah pesan

            // Durasi projek 2-6 bulan
            $durasiProjek = rand(2, 6);
            $tanggalSelesai = (clone $tanggalMulai)->addMonths($durasiProjek);

            // Tentukan status berdasarkan tanggal
            $now = Carbon::now();
            if ($tanggalSelesai->isPast()) {
                // Projek sudah lewat deadline
                $status = $this->randomWeighted([
                    'selesai' => 70, // 70% selesai
                    'berjalan' => 20, // 20% masih berjalan (terlambat)
                    'batal' => 10     // 10% dibatalkan
                ]);
            } elseif ($tanggalMulai->isPast() && $tanggalSelesai->isFuture()) {
                // Projek sedang berjalan
                $status = $this->randomWeighted([
                    'berjalan' => 85,
                    'batal' => 10,
                    'selesai' => 5 // Selesai lebih cepat
                ]);
            } elseif ($tanggalPesan->isPast() && $tanggalMulai->isFuture()) {
                // Sudah dipesan tapi belum mulai
                $status = $this->randomWeighted([
                    'disetujui' => 70,
                    'pending' => 20,
                    'batal' => 10
                ]);
            } else {
                // Belum dipesan
                $status = $this->randomWeighted([
                    'pending' => 60,
                    'disetujui' => 30,
                    'batal' => 10
                ]);
            }

            // Nominal projek (10 juta - 500 juta)
            $nominalProjek = rand(10000000, 500000000);

            // Sisa tanggungan tergantung status
            $sisaTanggungan = 0;
            if ($status === 'pending') {
                $sisaTanggungan = $nominalProjek; // Belum bayar sama sekali
            } elseif ($status === 'disetujui') {
                $sisaTanggungan = $nominalProjek * 0.9; // Sudah DP 10%
            } elseif ($status === 'berjalan') {
                $sisaTanggungan = $nominalProjek * (rand(30, 70) / 100); // Sudah bayar 30-70%
            } elseif ($status === 'selesai') {
                $sisaTanggungan = rand(0, 1) ? 0 : ($nominalProjek * 0.1); // 50% chance lunas, 50% sisa 10%
            } elseif ($status === 'batal') {
                $sisaTanggungan = 0; // Dibatalkan, tidak ada tanggungan
            }

            DB::table('projek')->insert([
                'id_perusahaan' => $perusahaanIds[array_rand($perusahaanIds)],
                'nama_projek' => $this->namaProyek[$i],
                'kategori' => $this->kategori[array_rand($this->kategori)],
                'deskripsi' => $this->deskripsi[$i],
                'tanggal_pesan' => $tanggalPesan->format('Y-m-d'),
                'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                'status' => $status,
                'nominal_projek' => $nominalProjek,
                'sisa_tanggungan' => $sisaTanggungan,
                'dokumen_perjanjian' => null, // Bisa diisi path dokumen jika ada
                'dibuat_oleh' => $adminIds[array_rand($adminIds)],
                'dibuat_pada' => $tanggalPesan, // Dibuat saat tanggal pesan
                'diperbarui_pada' => now(),
            ]);
        }

        echo "✓ Projek seeder completed: " . DB::table('projek')->count() . " projek created\n";
        echo "✓ Projek tersebar di " . count($perusahaanIds) . " perusahaan\n";

        // Tampilkan statistik status
        $stats = DB::table('projek')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        echo "\n📊 Status Projek:\n";
        foreach ($stats as $stat) {
            echo "   • {$stat->status}: {$stat->total} projek\n";
        }
    }

    /**
     * Random dengan bobot/probabilitas
     */
    private function randomWeighted(array $weights): string
    {
        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $value => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $value;
            }
        }

        return array_key_first($weights);
    }
}
