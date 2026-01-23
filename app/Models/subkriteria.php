<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini

/**
 * Model untuk tabel subkriteria.
 * Menyimpan detail skala penilaian bertingkat (misalnya, 
 * 'Sangat Baik' = 5) yang terkait dengan Kriteria tertentu.
 */
class SubKriteria extends Model 
{
    use HasFactory;

    // KOREKSI: Menggunakan huruf kecil semua agar sesuai dengan konvensi database MySQL
    protected $table = 'subkriteria'; 
    protected $primaryKey = 'id_subkriteria';
    public $timestamps = false; 

    protected $fillable = [
        'id_kriteria', // Foreign Key ke tabel Kriteria
        'nama_subkriteria',
        'nilai', 
        'id_keputusan', // Tambahkan foreign key dari Migration
    ];

    /**
     * Relasi Many-to-One: Sub Kriteria dimiliki oleh satu Kriteria.
     */
    public function kriteria(): BelongsTo // Tambahkan tipe hint BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }
    
    /**
     * Relasi Many-to-One: Sub Kriteria dimiliki oleh satu Keputusan.
     */
    public function keputusan(): BelongsTo // Tambahkan relasi ke Keputusan
    {
        return $this->belongsTo(SpkKeputusan::class, 'id_keputusan');
    }
}