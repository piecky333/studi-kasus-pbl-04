<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel penilaian.
 * Ini adalah Matriks Keputusan (Xij), menyimpan nilai mentah setiap alternatif terhadap setiap kriteria.
 */
class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';
    protected $primaryKey = 'id_penilaian';
    public $timestamps = false; 

    protected $fillable = [
        'id_kriteria',
        'id_alternatif',
        'nilai',
    ];

    // Relasi Many-to-One: Penilaian merujuk ke Kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }

    // Relasi Many-to-One: Penilaian merujuk ke Alternatif
    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif');
    }
}