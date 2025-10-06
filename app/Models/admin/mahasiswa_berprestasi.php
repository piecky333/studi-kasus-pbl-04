<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa_berprestasi extends Model
{
    protected $table = 'mhs_prestasi';
    protected $primaryKey = 'id_mhsprestasi';
    protected $fillable = ['nim', 'nama', 'nama_kegiatan', 'tahun', 'tingkat', 'id_dtmahasiswa'];

    public function mahasiswa()
    {
        return $this->belongsTo(dt_mahasiswa::class, 'id_dtmahasiswa');
    }

    public function anggota()
    {
        return $this->hasMany(anggota::class, 'id_mhsprestasi');
    }
}
