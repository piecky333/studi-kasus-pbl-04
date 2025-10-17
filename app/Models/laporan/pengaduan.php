<?php

namespace App\Models\laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\laporan\Tanggapan;
use App\Models\laporan\Terlapor;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'tanggal_pengaduan',
        'jenis_kasus',
        'deskripsi',
        'status_validasi',
    ];

    /* ===========================
       🔗 Relasi antar tabel
    =========================== */

    // 1 Pengaduan dibuat oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // 1 Pengaduan bisa punya banyak Tanggapan
    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_pengaduan');
    }

    // 1 Pengaduan bisa punya banyak Terlapor
    public function terlapor()
    {
        return $this->hasMany(Terlapor::class, 'id_pengaduan');
    }
}
