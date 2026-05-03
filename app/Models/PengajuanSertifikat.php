<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasHashid;

class PengajuanSertifikat extends Model
{
    use HasFactory, HasHashid;

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
        'status',
        'keterangan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
