<?php

namespace App\Models\Mahasiswa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSertifikat extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_sertifikat';
    protected $primaryKey = 'id_pengajuan';
    
    protected $fillable = [
        'id_user',
        'nama_kegiatan',
        'jenis_kegiatan',
        'tingkat_kegiatan',
        'tanggal_kegiatan',
        'file_sertifikat',
        'deskripsi',
        'status', // pending, verified, rejected
        'keterangan_admin'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }
}
