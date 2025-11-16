<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel spk_keputusan (Induk/Header dari setiap proses SPK).
 * Berfungsi sebagai arsip dan konteks keputusan yang sedang berjalan.
 */
class spkkeputusan extends Model
{
    use HasFactory;

    // Nama tabel sesuai skema
    protected $table = 'spk_keputusan';

    // Atur primary key
    protected $primaryKey = 'id_keputusan';
    
    // Matikan timestamps default Laravel jika tidak diperlukan
    public $timestamps = false; 

    // Kolom yang boleh diisi (Fillable)
    protected $fillable = [
        'nama_keputusan',
        'metode_yang_digunakan',
        'tanggal_dibuat',
        'status',
    ];

    // Relasi One-to-Many: Satu keputusan memiliki banyak kriteria
    public function kriteria()
    {
        return $this->hasMany(kriteria::class, 'id_keputusan');
    }

    // Relasi One-to-Many: Satu keputusan memiliki banyak alternatif
    public function alternatif()
    {
        return $this->hasMany(alternatif::class, 'id_keputusan');
    }
}