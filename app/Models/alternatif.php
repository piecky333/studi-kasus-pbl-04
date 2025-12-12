<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    // Relasi Many-to-One: Alternatif terhubung ke satu mahasiswa
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(\App\Models\admin\Datamahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    // Relasi Many-to-One: Alternatif dimiliki oleh satu keputusan
    public function keputusan(): BelongsTo
    {
        // Asumsi model keputusan bernama 'spkkeputusan'
        return $this->belongsTo(spkkeputusan::class, 'id_keputusan');
    }

    // Relasi One-to-Many: Satu alternatif memiliki banyak penilaian
    public function penilaian(): HasMany
    {
        return $this->hasMany(penilaian::class, 'id_alternatif');
    }

    // Relasi One-to-One: Satu alternatif memiliki satu hasil akhir
    public function hasilAkhir(): HasOne
    {
        return $this->hasOne(hasilakhir::class, 'id_alternatif');
    }
}