<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'semester',
    ];

    public function prestasi()
    {
        return $this->hasMany(\App\Models\admin\Prestasi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function sanksi()
    {
        return $this->hasMany(\App\Models\admin\Sanksi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function keuangan()
    {
        return $this->hasMany(\App\Models\admin\Keuangan::class, 'id_anggota', 'id_mahasiswa');
    }
}
