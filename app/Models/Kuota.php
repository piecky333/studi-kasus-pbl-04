<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuota extends Model
{
    use HasFactory;

    protected $table = 'kuota';
    protected $primaryKey = 'id_kuota';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pembimbing',
        'id_mahasiswa',
        'status',
        'tanggal_daftar',
        'tanggal_selesai',
    ];

    // Relasi ke PembimbingLapangan
    public function pembimbingLapangan()
    {
        return $this->belongsTo(PembimbingLapangan::class, 'id_pembimbing');
    }

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
}
