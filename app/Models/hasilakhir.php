<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel hasil_akhir.
 * Menyimpan skor akhir (Nilai V) dan rangking dari proses SAW.
 */
class hasilakhir extends Model
{
    use HasFactory;

    protected $table = 'hasil_akhir';
    protected $primaryKey = 'id_hasil';
    public $timestamps = false; 

    protected $fillable = [
        'id_alternatif',
        'skor_akhir',
        'rangking',
    ];

    // Relasi Many-to-One: Hasil Akhir merujuk ke Alternatif
    public function alternatif()
    {
        return $this->belongsTo(alternatif::class, 'id_alternatif');
    }
}