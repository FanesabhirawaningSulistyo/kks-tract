<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranProjek extends Model
{
    protected $table = 'pembayaran_projek';

    protected $primaryKey = 'id_pembayaran';

    public $timestamps = false;

    protected $fillable = [
        'kode_pembayaran',
        'id_projek',
        'id_petugas',
        'id_metode_pembayaran',
        'jumlah_bayar',
        'tanggal_bayar',
        'bukti_bayar',
        'status',
        'dibuat_pada',
        'diperbarui_pada'
    ];

    /*
    RELASI
    */

    public function projek()
    {
        return $this->belongsTo(Projek::class, 'id_projek');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }

    public function metode()
    {
        return $this->belongsTo(MetodePembayaran::class, 'id_metode_pembayaran');
    }
}
