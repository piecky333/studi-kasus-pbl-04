<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = ['id_user', 'nama_admin', 'jabatan_admin'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }

    public function tanggapan()
    {
        return $this->hasMany(\App\Models\laporan\tanggapan::class, 'id_admin');
    }

    public function prestasi()
    {
        return $this->hasMany(\App\Models\admin\Prestasi::class, 'id_admin');
    }

    public function sanksi()
    {
        return $this->hasMany(\App\Models\admin\sanksi::class, 'id_admin');
    }

    public function dt_mahasiswa()
    {
        return $this->hasMany(\App\Models\admin\dt_mahasiswa::class, 'id_admin');
    }
}
