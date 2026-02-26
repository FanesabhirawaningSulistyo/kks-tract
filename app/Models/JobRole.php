<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRole extends Model
{
    use HasFactory;

    protected $table = 'job_roles';
    protected $primaryKey = 'id_job_role';

    // NONAKTIFKAN TIMESTAMPS DEFAULT
    public $timestamps = false;

    protected $fillable = [
        'nama_job_role',
        'deskripsi',
        'status',
        'dibuat_pada',
        'diperbarui_pada'
    ];

    // Cast tanggal
    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'status' => 'boolean'
    ];

    /**
     * Relasi: satu job role dimiliki banyak user
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_job_role', 'id_job_role');
    }
}
