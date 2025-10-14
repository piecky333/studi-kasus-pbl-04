<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'semester'
    ];

    public function prestasi()
    {
        return $this->hasMany(prestasi::class, 'id_mahasiswa', 'id_dtmahasiswa');
    }

    public function sanksi()
    {
        return $this->hasMany(sanksi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function keuangan()
    {
        return $this->hasMany(keuangan::class, 'id_anggota', 'id_mahasiswa');
    }
}
