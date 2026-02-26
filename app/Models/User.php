<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table      = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'id_job_role',
        'no_hp',
        'foto',
        'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'status'            => 'boolean',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'id_user';
    }

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class, 'id_job_role', 'id_job_role');
    }

    public function perusahaan()
    {
        return $this->hasOne(Perusahaan::class, 'id_user_perusahaan', 'id_user');
    }

    /* ── Role checks ── */
    // ✅ FIX: 'PM' uppercase sesuai enum di migration
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isPM(): bool
    {
        return $this->role === 'PM';
    }
    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }
    public function isKlien(): bool
    {
        return $this->role === 'klien';
    }

    /* ── Scopes ── */
    public function scopeAdmin($q)
    {
        return $q->where('role', 'admin');
    }
    public function scopePM($q)
    {
        return $q->where('role', 'PM');
    }       // ✅ uppercase
    public function scopeKaryawan($q)
    {
        return $q->where('role', 'karyawan');
    }
    public function scopeKlien($q)
    {
        return $q->where('role', 'klien');
    }
    public function scopeAktif($q)
    {
        return $q->where('status', true);
    }
}
