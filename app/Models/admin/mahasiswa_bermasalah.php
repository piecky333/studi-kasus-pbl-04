<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa_bermasalah extends Model
{
    protected $table = 'mhs_bermasalah';
    protected $primaryKey = 'id_mhsbermasalah';
    protected $fillable = ['id_dtmahasiswa', 'nama', 'tanggal_lapor', 'jenis_masalah', 'status_validasi'];

    public function mahasiswa()
    {
        return $this->belongsTo(dt_mahasiswa::class, 'id_dtmahasiswa');
    }

    public function sanksi()
    {
        return $this->hasMany(sanksi::class, 'id_mhsbermasalah');
    }
}

