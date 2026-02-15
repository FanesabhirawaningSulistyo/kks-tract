<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    public $timestamps = false; // karena pakai dibuat_pada & diubah_pada

    protected $fillable = [
        'id_projek',
        'judul_tugas',
        'deskripsi_tugas',
        'level',
        'weight',
        'penanggung_jawab',
        'status',
        'tenggat_waktu',
        'dibuat_pada',
        'diubah_pada',
    ];

    // Relasi ke projek
    public function projek()
    {
        return $this->belongsTo(Projek::class, 'id_projek', 'id_projek');
    }

    // Relasi ke user (penanggung jawab)
    public function penanggungJawab()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab', 'id_user');
    }
}
