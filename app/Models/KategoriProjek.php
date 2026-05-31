<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProjek extends Model
{
    protected $table      = 'kategori_projek';
    protected $primaryKey = 'id_kategori_projek'; //  sesuai migration

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status',
    ];

    public $timestamps = false;

    protected $casts = [
        'dibuat_pada'     => 'datetime',
        'diperbarui_pada' => 'datetime',
        'status'          => 'boolean',
    ];

    // Relasi ke tabel projek via FK id_kategori_projek
    public function projek()
    {
        return $this->hasMany(Projek::class, 'id_kategori_projek', 'id_kategori_projek');
    }
}
