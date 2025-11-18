<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel kriteria.
 * Menyimpan detail kriteria dan bobot.
 */
class kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $timestamps = false; 

    protected $fillable = [
        'id_keputusan',
        'nama_kriteria',
        'kode_kriteria',
        'jenis_kriteria',
        'bobot_kriteria',
    ];

    /**
     * Relasi Many-to-One: Kriteria dimiliki oleh satu keputusan.
     */
    public function keputusan()
    {
        return $this->belongsTo(spkkeputusan::class, 'id_keputusan');
    }

    /**
     * Relasi One-to-Many: Satu kriteria memiliki banyak penilaian.
     */
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_kriteria');
    }

    /**
     * Relasi One-to-Many: Satu kriteria memiliki banyak sub kriteria.
     */
    public function subKriteria()
    {
        return $this->hasMany(subkriteria::class, 'id_kriteria');
    }
}