<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'Prestasi';
    protected $primaryKey = 'id_prestasi';
    protected $fillable = ['id_prestasi', 'tahun', 'tingkat', 'nama_kegiatan', 'jenis_prestasi', 'status_validasi'];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\admin\dt_mahasiswa::class, 'id_mahasiswa');
    }

    public function anggota()
    {
        return $this->hasMany(\App\Models\anggota::class, 'id_mhsprestasi');
    }
}
