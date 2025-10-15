<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'mahasiswa_berprestasi';
    protected $primaryKey = 'id_mhsprestasi';
    protected $fillable = ['id_mahasiswa', 'tahun', 'tingkat', 'nama_prestasi', 'peringkat', 'status_validasi'];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\admin\dt_mahasiswa::class, 'id_mahasiswa');
    }

    public function anggota()
    {
        return $this->hasMany(\App\Models\anggota::class, 'id_mhsprestasi');
    }
}
