<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel alternatif (Mahasiswa).
 * Mencatat daftar Mahasiswa yang dievaluasi dalam sebuah keputusan.
 */
class alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatif';
    protected $primaryKey = 'id_alternatif';
    public $timestamps = false; 

    protected $fillable = [
        'id_keputusan',
        'id_mahasiswa', // Foreign Key ke tabel Mahasiswa
        'nama_alternatif',
        'keterangan',
    ];

    // Relasi Many-to-One: Alternatif dimiliki oleh satu keputusan
    public function keputusan()
    {
        return $this->belongsTo(spkkeputusan::class, 'id_keputusan');
    }

    // Relasi One-to-Many: Satu alternatif memiliki banyak penilaian
    public function penilaian()
    {
        return $this->hasMany(penilaian::class, 'id_alternatif');
    }

    // Relasi One-to-One: Satu alternatif memiliki satu hasil akhir
    public function hasilAkhir()
    {
        return $this->hasOne(hasilakhir::class, 'id_alternatif');
    }
}