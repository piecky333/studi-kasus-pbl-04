<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\laporan\laporan;
use App\Models\komentar;
use App\Models\berita;
use App\Models\admin\admin;

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
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /* ==================================================
       🔗 Relasi antar tabel (sesuai ERD)
    ================================================== */

<<<<<<< HEAD
    // 1 User bisa menjadi Admin
    public function admin()
    {
        return $this->hasOne(admin::class, 'id_user');
    }

=======
    // Relasi berdasarkan role
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user');
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user');
    }
    
>>>>>>> 0dc1741 (Menambahkan PrestasiController dan memperbarui model serta view terkait)
    // 1 User punya banyak laporan
    public function laporan()
    {
        return $this->hasMany(laporan::class, 'id_user');
    }

    // 1 User punya banyak komentar
    public function komentar()
    {
        return $this->hasMany(komentar::class, 'id_user');
    }

    // 1 User bisa membuat banyak berita
    public function berita()
    {
        return $this->hasMany(berita::class, 'id_user');
    }
}

