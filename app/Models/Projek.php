<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projek extends Model
{
    use HasFactory;

    protected $table = 'projek';
    protected $primaryKey = 'id_projek';
    public $timestamps = false; // karena kamu pakai 'dibuat_pada' & 'diperbarui_pada' bukan default laravel

    protected $fillable = [
        'id_perusahaan',
        'nama_projek',
        'kategori',
        'deskripsi',
        'tanggal_pesan',
        'status',
        'nominal_projek',
        'sisa_tanggungan',
        'dokumen_perjanjian',
        'tanggal_mulai',
        'tanggal_selesai',
        'dibuat_oleh',
        'dibuat_pada',
        'diperbarui_pada',
    ];

    // Relasi ke perusahaan
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    // Relasi ke user pembuat
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id_user');
    }

    // Relasi ke tugas
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_projek', 'id_projek');
    }
}
