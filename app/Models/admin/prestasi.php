<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';
    protected $primaryKey = 'id_prestasi';
    protected $fillable = [
        'id_dtmahasiswa',
        'nama_mahasiswa',
        'nama_kegiatan',
        'tingkat_prestasi',
        'tahun',
        'status_validasi',
        'id_admin'
    ];

    public function dt_mahasiswa()
    {
        return $this->belongsTo(dt_mahasiswa::class, 'id_dtmahasiswa', 'id_dtmahasiswa');
    }

    public function admin()
    {
        return $this->belongsTo(admin::class, 'id_admin', 'id_admin');
    }
}
