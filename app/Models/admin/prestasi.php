<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasi';
    protected $primaryKey = 'id_prestasi';
    protected $fillable = [
        'id_mahasiswa',
        'id_admin',
        'nama_kegiatan',
        'tingkat_prestasi',
        'tahun',
        'status_validasi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(mahasiswa::class, 'id_mahasiswa');
    }

    public function anggota()
    {
        return $this->belongsTo(admin::class, 'id_admin');
    }
}
