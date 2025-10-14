<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\admin\admin;
use App\Models\admin\mahasiswa;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';
    protected $primaryKey = 'id_prestasi';
    protected $fillable = [
        'id_dtmahasiswa',
        'id_admin',
        'nama_kegiatan',
        'tingkat_prestasi',
        'tahun',
        'status_validasi',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(mahasiswa::class, 'id_dtmahasiswa');
    }

    public function admin()
    {
        return $this->belongsTo(admin::class, 'id_admin');
    }
}
