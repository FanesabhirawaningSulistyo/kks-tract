<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diubah_pada';

    protected $fillable = [
        'id_projek',
        'id_tim',
        'judul_tugas',
        'deskripsi_tugas',
        'level',
        'weight',
        'status_progress',
        'status_akhir',
        'tenggat_waktu'
    ];

    protected $casts = [
        'tenggat_waktu' => 'date',
        'dibuat_pada'   => 'datetime',
        'diubah_pada'   => 'datetime'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    // Relasi ke Projek
    public function projek()
    {
        return $this->belongsTo(Projek::class, 'id_projek', 'id_projek');
    }

    // Relasi ke ProjekTim
    public function tim()
    {
        return $this->belongsTo(ProjekTim::class, 'id_tim', 'id_tim');
    }
}
