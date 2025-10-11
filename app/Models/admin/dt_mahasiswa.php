<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dt_mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'dt_mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $fillable = ['id_admin', 'nama', 'nim', 'semester', 'alamat', 'no_hp'];

    public function admin()
    {
        return $this->belongsTo(\App\Models\admin\admin::class, 'id_admin');
    }

    public function prestasi()
    {
        return $this->hasMany(\App\Models\admin\Prestasi::class, 'id_mahasiswa');
    }

    public function sanksi()
    {
        return $this->hasMany(\App\Models\admin\mahasiswa_bermasalah::class, 'id_mahasiswa');
    }

    public function anggota()
    {
        return $this->hasOne(\App\Models\anggota::class, 'id_mhs');
    }
}
