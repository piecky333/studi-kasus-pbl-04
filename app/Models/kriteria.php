<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel kriteria.
 * Menyimpan detail kriteria, tipe (Benefit/Cost), bobot, dan cara penilaian.
 */
class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $timestamps = false;

    protected $fillable = [
        'id_keputusan',
        'nama_kriteria',
        'kode_kriteria',
        'jenis_kriteria', // Menyimpan 'Benefit' atau 'Cost' (Type)
        'bobot_kriteria', // Menyimpan bobot hasil AHP/manual (Bobot)
        'cara_penilaian', // Kolom tambahan untuk 'Cara Penilaian' (mis: Input Langsung, Pilihan Sub Kriteria)
    ];

    /**
     * Relasi Many-to-One: Kriteria dimiliki oleh satu keputusan.
     * Asumsi: Model keputusan Anda adalah Spkkeputusan.
     */
    public function keputusan()
    {
        return $this->belongsTo(spkkeputusan::class, 'id_keputusan');
    }

    /**
     * Relasi One-to-Many: Satu kriteria memiliki banyak penilaian.
     * Digunakan untuk mencatat nilai alternatif untuk kriteria ini.
     */
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_kriteria');
    }

    /**
     * Relasi One-to-Many: Satu kriteria memiliki banyak sub kriteria.
     * Digunakan ketika 'Cara Penilaian' adalah 'Pilihan Sub Kriteria'.
     * Asumsi: Model sub kriteria Anda adalah Subkriteria.
     */
    public function subkriteria()
    {
        return $this->hasMany(SubKriteria::class, 'id_kriteria', 'id_kriteria');
    }
}