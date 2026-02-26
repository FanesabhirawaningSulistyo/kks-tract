<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjekTim extends Model
{
    protected $table = 'projek_tim';
    protected $primaryKey = 'id_tim';
    public $incrementing = true;
    protected $keyType = 'int';

    // Custom timestamp
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_projek',
        'id_user'
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

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Tugas (1 anggota tim bisa punya banyak tugas)
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_tim', 'id_tim');
    }
}
