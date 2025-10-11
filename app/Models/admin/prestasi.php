<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasi';
    protected $primaryKey = 'id_prestasi';
    protected $fillable = ['id_dtmahasiswa', 'nama_prestasi', 'tingkat', 'peringkat', 'tahun'];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\admin\dt_mahasiswa::class, 'id_dtmahasiswa');
    }
}
