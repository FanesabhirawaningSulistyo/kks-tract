<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    protected $table      = 'metode_pembayaran';
    protected $primaryKey = 'id_metode_pembayaran';
    public    $timestamps = false;

    protected $fillable = [
        'nama_metode',
        'deskripsi',
        'status',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    // Relasi ke pembayaran_projek
    public function pembayaranProjek()
    {
        return $this->hasMany(PembayaranProjek::class, 'id_metode_pembayaran', 'id_metode_pembayaran');
    }
}
