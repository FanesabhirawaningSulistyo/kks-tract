<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table      = 'tugas';
    protected $primaryKey = 'id_tugas';
    public    $incrementing = true;
    protected $keyType    = 'int';

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
        'tenggat_waktu',
        'tanggal_mulai',
        'tanggal_selesai',  // ← Diisi otomatis saat done
    ];

    protected $casts = [
        'tenggat_waktu'   => 'date',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'dibuat_pada'     => 'datetime',
        'diubah_pada'     => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function projek()
    {
        return $this->belongsTo(Projek::class, 'id_projek', 'id_projek');
    }

    public function tim()
    {
        return $this->belongsTo(ProjekTim::class, 'id_tim', 'id_tim');
    }

    public function foto()
    {
        return $this->hasMany(TugasFoto::class, 'id_tugas', 'id_tugas');
    }
}
