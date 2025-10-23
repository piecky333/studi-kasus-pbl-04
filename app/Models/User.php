<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\laporan\pengaduan; 
use App\Models\admin\admin;
use App\Models\komentar;
use App\Models\berita;
use App\Models\Mahasiswa; 

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    
    protected $primaryKey = 'id_user';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama', 'username', 'email', 'password', 'role', 'google_id', 'avatar',
    ];

    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    /* ==================================================
    Relasi antar tabel
    ================================================== */

    public function admin()
    {
        // KOREKSI: Tambahkan parameter ke-3 (Local Key)
        return $this->hasOne(admin::class, 'id_user', 'id_user');
    }

    /**
     * === INI KUNCI #2 (PENYEBAB ERROR ANDA) ===
     * Relasi ini HARUS punya 3 parameter
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user', 'id_user');
    }

    public function pengaduan()
    {
        return $this->hasMany(pengaduan::class, 'id_user', 'id_user');
    }

    public function komentar()
    {
        return $this->hasMany(komentar::class, 'id_user', 'id_user');
    }

    public function berita()
    {
        // KOREKSI: Tambahkan parameter ke-3 (Local Key)
        return $this->hasMany(berita::class, 'id_user', 'id_user');
    }

    public function user()
    {
        // Pastikan parameter ke-3 ada
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
}
