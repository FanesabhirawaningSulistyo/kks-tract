<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasFoto extends Model
{
    use HasFactory;

    protected $table = 'tugas_foto';
    protected $primaryKey = 'id_tugas_foto';

    // Non-incrementing default false (karena pakai bigIncrements tetap true)
    public $incrementing = true;

    protected $keyType = 'int';

    // Custom timestamp
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_tugas',
        'nama_file',
        'tipe',
    ];

    /**
     * Relasi ke tabel tugas
     */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }
}
