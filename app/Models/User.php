<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\laporan\Pengaduan;
use App\Models\admin\admin;
use App\Models\komentar;
use App\Models\berita;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /* ==================================================
       🔗 Relasi antar tabel
    ================================================== */

    // Relasi berdasarkan role
    public function admin()
    {
        return $this->hasOne(admin::class, 'id_user');
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user');
    }

    // 1 User punya banyak Pengaduan
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_user', 'id');
    }

    // 1 User punya banyak Komentar
    public function komentar()
    {
        return $this->hasMany(komentar::class, 'id_user');
    }

    // 1 User bisa membuat banyak Berita
    public function berita()
    {
        return $this->hasMany(berita::class, 'id_user');
    }
}
