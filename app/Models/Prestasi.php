<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHashid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestasi extends Model
{
    use HasFactory, HasHashid;

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
        'bukti_path',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(DataMahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}


