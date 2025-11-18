<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel subkriteria.
 * Menyimpan detail skala penilaian bertingkat (misalnya, 
 * 'Sangat Baik' = 5) yang terkait dengan Kriteria tertentu.
 */
class subkriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_kriteria'; // Sesuaikan dengan nama tabel Anda di database
    protected $primaryKey = 'id_subkriteria';
    public $timestamps = false; 

    protected $fillable = [
        'id_kriteria', // Foreign Key ke tabel Kriteria
        'deskripsi',
        'nilai_skala', // Nilai numerik yang akan digunakan untuk normalisasi
    ];

    /**
     * Relasi Many-to-One: Sub Kriteria dimiliki oleh satu Kriteria.
     */
    public function kriteria()
    {
        // Sesuaikan dengan nama Model Kriteria Anda
        return $this->belongsTo(kriteria::class, 'id_kriteria'); 
    }
}