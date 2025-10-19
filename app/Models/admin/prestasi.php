<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\mahasiswa;
use App\Models\admin\pengurus;

class Prestasi extends Model
{
    protected $table = 'Prestasi';
    protected $primaryKey = 'id_prestasi';
    protected $fillable = ['id_prestasi', 'tahun', 'tingkat', 'nama_kegiatan', 'jenis_prestasi', 'status_validasi'];

    public function mahasiswa()
    {
        return $this->belongsTo(mahasiswa::class, 'id_mahasiswa');
    }

    public function pengurus()
    {
        return $this->hasMany(pengurus::class, 'prestasi');
    }
}
