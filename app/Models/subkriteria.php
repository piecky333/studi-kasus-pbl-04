<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel subkriteria.
 * Menyimpan detail skala penilaian bertingkat (misalnya, 
 * 'Sangat Baik' = 5) yang terkait dengan Kriteria tertentu.
 */
class Subkriteria extends Model 
{
    use HasFactory;

    // KOREKSI: Menggunakan huruf kecil semua agar sesuai dengan konvensi database MySQL
    protected $table = 'subkriteria'; 
    protected $primaryKey = 'id_subkriteria';
    public $timestamps = false; 

    protected $fillable = [
        'id_kriteria', // Foreign Key ke tabel Kriteria
        'deskripsi',
        'nilai_skala', 
    ];

    /**
     * Relasi Many-to-One: Sub Kriteria dimiliki oleh satu Kriteria.
     */
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }
}