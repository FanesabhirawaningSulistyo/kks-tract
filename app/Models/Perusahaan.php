<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table      = 'perusahaan';
    protected $primaryKey = 'id_perusahaan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_user_perusahaan',
        'nama_perusahaan',      // ← disimpan di sini juga untuk sinkronisasi
        'email_perusahaan',     // ← disimpan di sini juga untuk sinkronisasi
        'telepon_perusahaan',   // ← disimpan di sini juga untuk sinkronisasi
        'nama_perwakilan',
        'email_perwakilan',
        'telepon_perwakilan',
        'logo_perusahaan',
        'alamat_perusahaan',
    ];

    protected $casts = [
        'dibuat_pada'    => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Saat perusahaan dibuat → buat/update user terkait
        static::created(function (Perusahaan $perusahaan) {
            $perusahaan->syncToUser();
        });

        // Saat perusahaan diupdate → update user terkait
        static::updated(function (Perusahaan $perusahaan) {
            $perusahaan->syncToUser();
        });
    }

    /**
     * Sinkronisasi nama, email, telepon perusahaan ke tabel users
     */
    public function syncToUser(): void
    {
        if (!$this->id_user_perusahaan) return;

        $updateData = [];

        if ($this->isDirty('nama_perusahaan') || $this->nama_perusahaan) {
            $updateData['nama'] = $this->nama_perusahaan;
        }
        if ($this->isDirty('email_perusahaan') || $this->email_perusahaan) {
            $updateData['email'] = $this->email_perusahaan;
        }
        if ($this->isDirty('telepon_perusahaan') || $this->telepon_perusahaan) {
            $updateData['no_hp'] = $this->telepon_perusahaan;
        }

        if (!empty($updateData)) {
            User::where('id_user', $this->id_user_perusahaan)->update($updateData);
        }
    }

    // =========================================================
    // RELATIONS
    // =========================================================

    /**
     * Relasi ke User (akun login perusahaan)
     */
    public function userPerusahaan()
    {
        return $this->belongsTo(User::class, 'id_user_perusahaan', 'id_user');
    }

    /**
     * Relasi ke Projek
     */
    public function projek()
    {
        return $this->hasMany(Projek::class, 'id_perusahaan', 'id_perusahaan');
    }

    // =========================================================
    // ACCESSORS — Ambil data perusahaan dari tabel users
    // =========================================================

    /**
     * Nama perusahaan: prioritas kolom lokal, fallback ke users
     */
    public function getNamaPerusahaanAttribute(): ?string
    {
        return $this->attributes['nama_perusahaan']
            ?? ($this->userPerusahaan?->nama);
    }

    /**
     * Email perusahaan: prioritas kolom lokal, fallback ke users
     */
    public function getEmailPerusahaanAttribute(): ?string
    {
        return $this->attributes['email_perusahaan']
            ?? ($this->userPerusahaan?->email);
    }

    /**
     * Telepon perusahaan: prioritas kolom lokal, fallback ke users
     */
    public function getTeleponPerusahaanAttribute(): ?string
    {
        return $this->attributes['telepon_perusahaan']
            ?? ($this->userPerusahaan?->no_hp);
    }

    // =========================================================
    // SCOPES
    // =========================================================

    public function scopeDenganUser($query, $idUser)
    {
        return $query->where('id_user_perusahaan', $idUser);
    }
}
