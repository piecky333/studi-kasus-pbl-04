<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\admin\Datamahasiswa;
use App\Models\admin\pengurus;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class prestasi extends Model
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
        return $this->belongsTo(Datamahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
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
