<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $primaryKey = 'id_perusahaan';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_user_perusahaan',    // FK ke users (berisi data perusahaan)
        'nama_perwakilan',       // Nama PIC
        'email_perwakilan',      // Email PIC
        'telepon_perwakilan',    // Telepon PIC
        'logo_perusahaan',
        'alamat_perusahaan',
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
    ];

    /**
     * Relasi ke User (data perusahaan: nama, email, telepon perusahaan)
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

    /**
     * Accessor untuk mendapatkan nama perusahaan (dari tabel users)
     */
    public function getNamaPerusahaanAttribute()
    {
        return $this->userPerusahaan ? $this->userPerusahaan->nama : null;
    }

    /**
     * Accessor untuk mendapatkan email perusahaan (dari tabel users)
     */
    public function getEmailPerusahaanAttribute()
    {
        return $this->userPerusahaan ? $this->userPerusahaan->email : null;
    }

    /**
     * Accessor untuk mendapatkan telepon perusahaan (dari tabel users)
     */
    public function getTeleponPerusahaanAttribute()
    {
        return $this->userPerusahaan ? $this->userPerusahaan->no_hp : null;
    }

    /**
     * Scope untuk mencari perusahaan berdasarkan user
     */
    public function scopeDenganUser($query, $idUser)
    {
        return $query->where('id_user_perusahaan', $idUser);
    }
}
