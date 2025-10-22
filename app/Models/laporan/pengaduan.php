<?php

namespace App\Models\laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\laporan\Tanggapan;
use App\Models\laporan\Terlapor;
use App\Models\Mahasiswa;

class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'judul',
        'tanggal_pengaduan',
        'jenis_kasus',
        'deskripsi',
        'status',
    ];

    /* ===========================
        🔗 Relasi antar tabel
    =========================== */

    /**
     * Mendapatkan user yang memiliki pengaduan ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Mendapatkan semua tanggapan untuk pengaduan ini.
     */
    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_pengaduan');
    }


    public function mahasiswa()
    {
        return $this->hasOneThrough(
            Mahasiswa::class,
            User::class,
            'id', 
            'id_user', 
            'id_user', 
            'id' 
        );
    }

    /**
     * Mendapatkan semua terlapor untuk pengaduan ini.
     */
    public function terlapor()
    {
        return $this->hasMany(Terlapor::class, 'id_pengaduan');
    }
}
