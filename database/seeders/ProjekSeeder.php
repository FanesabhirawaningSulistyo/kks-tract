<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjekSeeder extends Seeder
{
    private $projectTemplates = [
        [
            'nama_projek'     => 'Redesign Website',
            'kategori'        => 'Web Development',
            'deskripsi'       => 'Redesign total website perusahaan dengan tampilan modern dan responsif.',
            'status'          => 'aktif',
            'nominal_projek'  => 25000000.00,
            'sisa_tanggungan' => 10000000.00,
            'tanggal_mulai'   => '2026-01-10',
            'tanggal_selesai' => '2026-04-10',
        ],
        [
            'nama_projek'     => 'Portal Internal Karyawan',
            'kategori'        => 'Web Development',
            'deskripsi'       => 'Pembuatan portal internal untuk manajemen karyawan.',
            'status'          => 'in_progress',
            'nominal_projek'  => 35000000.00,
            'sisa_tanggungan' => 17500000.00,
            'tanggal_mulai'   => '2026-02-01',
            'tanggal_selesai' => '2026-06-01',
        ],
        [
            'nama_projek'     => 'Aplikasi Kasir Android',
            'kategori'        => 'Mobile Development',
            'deskripsi'       => 'Pengembangan aplikasi kasir berbasis Android.',
            'status'          => 'pending',
            'nominal_projek'  => 40000000.00,
            'sisa_tanggungan' => 40000000.00,
            'tanggal_mulai'   => '2026-03-01',
            'tanggal_selesai' => '2026-07-01',
        ],
    ];

    public function run(): void
    {
        $now = now();
        $insertedProjek = 0;
        $insertedTim    = 0;

        // Ambil kategori
        $kategori = DB::table('kategori_projek')
            ->pluck('id_kategori_projek', 'nama_kategori');

        if ($kategori->isEmpty()) {
            $this->command->error('Kategori projek tidak ditemukan.');
            return;
        }

        // Ambil perusahaan
        $perusahaanList = DB::table('perusahaan')
            ->join('users', 'perusahaan.id_user_perusahaan', '=', 'users.id_user')
            ->select(
                'perusahaan.id_perusahaan',
                'users.nama as nama_perusahaan'
            )
            ->get();

        if ($perusahaanList->isEmpty()) {
            $this->command->error('Perusahaan tidak ditemukan.');
            return;
        }

        // Ambil PM aktif
        $pmUsers = DB::table('users')
            ->where('role', 'pm')
            ->where('status', true)
            ->pluck('id_user')
            ->toArray();

        if (empty($pmUsers)) {
            $this->command->error('Tidak ada PM aktif.');
            return;
        }

        // Ambil karyawan aktif
        $karyawanUsers = DB::table('users')
            ->where('role', 'karyawan')
            ->where('status', true)
            ->pluck('id_user')
            ->toArray();

        foreach ($perusahaanList as $index => $perusahaan) {

            $template = $this->projectTemplates[$index % count($this->projectTemplates)];
            $namaProjek = $template['nama_projek'] . ' - ' . $perusahaan->nama_perusahaan;

            // Round-robin PM
            $pmId = $pmUsers[$index % count($pmUsers)];

            $idProjek = DB::table('projek')->insertGetId([
                'id_perusahaan'      => $perusahaan->id_perusahaan,
                'dibuat_oleh'        => $pmId,
                'nama_projek'        => $namaProjek,
                'id_kategori_projek' => $kategori[$template['kategori']] ?? null,
                'deskripsi'          => $template['deskripsi'],
                'status'             => $template['status'],
                'nominal_projek'     => $template['nominal_projek'],
                'sisa_tanggungan'    => $template['sisa_tanggungan'],
                'dokumen_perjanjian' => null,
                'tanggal_mulai'      => $template['tanggal_mulai'],
                'tanggal_selesai'    => $template['tanggal_selesai'],
                'dibuat_pada'        => $now,
                'diperbarui_pada'    => $now,
            ]);

            $insertedProjek++;

            // Assign 2 karyawan ke tim
            if (!empty($karyawanUsers)) {

                $offset = $index % count($karyawanUsers);
                $timMembers = array_slice($karyawanUsers, $offset, 2);

                if (count($timMembers) < 2 && count($karyawanUsers) >= 2) {
                    $timMembers = array_merge(
                        $timMembers,
                        array_slice($karyawanUsers, 0, 2 - count($timMembers))
                    );
                }

                foreach (array_unique($timMembers) as $userId) {
                    $inserted = DB::table('projek_tim')->insertOrIgnore([
                        'id_projek'       => $idProjek,
                        'id_user'         => $userId,
                        'dibuat_pada'     => $now,
                        'diperbarui_pada' => $now,
                    ]);
                    $insertedTim += $inserted;
                }
            }
        }

        $this->command->info("✓ ProjekSeeder selesai");
        $this->command->info("  - Total projek : {$insertedProjek}");
        $this->command->info("  - Total tim    : {$insertedTim}");
    }
}
