<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\mahasiswa;
use App\Models\admin\pengurus;

class prestasi extends Model
{
    protected $table = 'Prestasi';
    protected $primaryKey = 'id_prestasi';
    protected $fillable = [
        'id_mahasiswa',
        'id_admin',
        'nama_kegiatan',
        'tingkat_prestasi',
        'tahun',
        'status_validasi',
        'deskripsi'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function pengurus()
    {
        return $this->hasMany(pengurus::class, 'prestasi');
    }
    public function admin()
    {
        return $this->belongsTo(\App\Models\admin\Admin::class, 'id_admin', 'id_admin');
    }
}
