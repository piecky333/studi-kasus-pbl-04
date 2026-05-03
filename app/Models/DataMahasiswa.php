<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHashid;

class DataMahasiswa extends Model
{
    use HasFactory, HasHashid;

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
        return $this->hasMany(Prestasi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function sanksi()
    {
        return $this->hasMany(Sanksi::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_user', 'id_user');
    }

    public function berita()
    {
        return $this->hasMany(Berita::class, 'id_user', 'id_user');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}


