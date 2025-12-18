<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\laporan\Tanggapan;
use App\Models\admin\Prestasi;
use App\Models\admin\Sanksi;
use App\Models\admin\Datamahasiswa;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = [
        'id_user',
        'nama_admin',
        'jabatan_admin', 
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }

    // Relasi ke Tanggapan
    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_admin');
    }

    // Relasi ke Prestasi
    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'id_admin');
    }

    // Relasi ke Sanksi
    public function sanksi()
    {
        return $this->hasMany(Sanksi::class, 'id_admin');
    }

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->hasMany(Datamahasiswa::class, 'id_admin');
    }
}
