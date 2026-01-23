<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\DataMahasiswa;
use App\Models\Admin\Pengurus;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestasi extends Model
{
    use HasFactory;
    protected $table = 'prestasi';
    protected $primaryKey = 'id_prestasi';
    protected $fillable = [
        'id_mahasiswa',
        'id_admin',
        'nama_kegiatan',
        'jenis_prestasi',
        'tingkat_prestasi',
        'juara',
        'tahun',
        'status_validasi',
        'deskripsi',
        'bukti_path'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(DataMahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class, 'prestasi');
    }
    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin\Admin::class, 'id_admin', 'id_admin');
    }
}
