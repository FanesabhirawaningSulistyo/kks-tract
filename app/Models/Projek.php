<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projek extends Model
{
    use HasFactory;

    protected $table      = 'projek';
    protected $primaryKey = 'id_projek';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_perusahaan',
        'dibuat_oleh',
        'nama_projek',
        'id_kategori_projek',
        'deskripsi',
        'status',
        'nominal_projek',
        'sisa_tanggungan',
        'dokumen_perjanjian',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'nominal_projek'  => 'decimal:2',
        'sisa_tanggungan' => 'decimal:2',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'dibuat_pada'     => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    // =========================================================
    // RELATIONS
    // =========================================================

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function userPerusahaan()
    {
        return $this->hasOneThrough(
            User::class,
            Perusahaan::class,
            'id_perusahaan',
            'id_user',
            'id_perusahaan',
            'id_user_perusahaan'
        );
    }

    public function kategoriProjek()
    {
        return $this->belongsTo(KategoriProjek::class, 'id_kategori_projek', 'id_kategori_projek');
    }

    /**
     * Projek punya banyak Tugas
     */
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_projek', 'id_projek');
    }

    /**
     * Anggota tim projek via tabel projek_tim (model ProjekTim)
     * Digunakan oleh TaskController: $projek->tim
     */
    public function tim()
    {
        return $this->hasMany(ProjekTim::class, 'id_projek', 'id_projek');
    }

    /**
     * Anggota tim projek (pivot langsung ke users)
     * Digunakan jika butuh akses User tanpa melalui ProjekTim
     */
    public function timKerja()
    {
        return $this->belongsToMany(
            User::class,
            'projek_tim',
            'id_projek',
            'id_user'
        )->withTimestamps('dibuat_pada', 'diperbarui_pada');
    }

    /**
     * User yang membuat projek (PM)
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id_user');
    }

    // =========================================================
    // ACCESSORS
    // =========================================================

    public function getNamaPerusahaanAttribute(): ?string
    {
        return $this->perusahaan?->userPerusahaan?->nama;
    }

    public function getEmailPerusahaanAttribute(): ?string
    {
        return $this->perusahaan?->userPerusahaan?->email;
    }

    public function getPersentaseLunasAttribute(): float
    {
        if ($this->nominal_projek <= 0) return 0;
        $terbayar = $this->nominal_projek - $this->sisa_tanggungan;
        return round(($terbayar / $this->nominal_projek) * 100, 2);
    }

    public function getTerbayarAttribute(): float
    {
        return $this->nominal_projek - $this->sisa_tanggungan;
    }

    /**
     * Persentase progress project berdasarkan weight tugas yang approved
     * Rumus: SUM(weight tugas approved) / SUM(total weight semua tugas) * 100%
     */
    public function getProgressProjekAttribute(): float
    {
        $tugasCollection = $this->relationLoaded('tugas')
            ? $this->tugas
            : $this->tugas()->get();

        $totalWeight = $tugasCollection->sum('weight');
        if ($totalWeight <= 0) return 0;

        $approvedWeight = $tugasCollection
            ->where('status_akhir', 'approved')
            ->sum('weight');

        return round(($approvedWeight / $totalWeight) * 100, 2);
    }

    // =========================================================
    // SCOPES
    // =========================================================

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeByPerusahaan($query, int $idPerusahaan)
    {
        return $query->where('id_perusahaan', $idPerusahaan);
    }
}
