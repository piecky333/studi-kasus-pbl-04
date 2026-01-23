<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $fillable = [
        'id_admin',
        'id_user',
        'nim',
        'nama',
        'email',
        'semester',
        'ipk',
    ];

    public function prestasi()
    {
        return $this->hasMany(\App\Models\Admin\Prestasi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function sanksi()
    {
        return $this->hasMany(\App\Models\Admin\Sanksi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function pengaduan()
    {
        return $this->hasMany(\App\Models\Laporan\Pengaduan::class, 'id_user', 'id_user');
    }

    public function berita()
    {
        return $this->hasMany(\App\Models\Berita::class, 'id_user', 'id_user');
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin\Admin::class, 'id_admin', 'id_admin');
    }
}