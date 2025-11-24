<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Prestasi;
use App\Models\admin\Sanksi;  

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'nim',
        'nama',
        'email',
        'semester',
        'alamat',
        'no_hp',
        'ipk', 
    ];

    // Relasi ke User
    public function user()
    {
        // Asumsi Model User berada di App\Models\User.php
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Prestasi (Untuk SPK Mahasiswa Berprestasi)
    public function prestasi()
    {
        // Perbaikan: Casing Class harus Prestasi::class
        return $this->hasMany(Prestasi::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    
    // Relasi ke Sanksi (Untuk SPK Mahasiswa Bermasalah)
    public function sanksi()
    {
        // Perbaikan: Casing Class harus Sanksi::class
        return $this->hasMany(Sanksi::class, 'id_mahasiswa', 'id_mahasiswa');
    }
    
    // Relasi ke Keuangan (Jika digunakan untuk C4 Mahasiswa Bermasalah)
    // public function keuangan()
    // {
    //     return $this->hasMany(Keuangan::class, 'id_mahasiswa', 'id_mahasiswa');
    // }
}