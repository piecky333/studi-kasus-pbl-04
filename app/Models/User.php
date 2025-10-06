<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\laporan\laporan;
use App\Models\mahasiswa\Komentar;
use App\Models\mahasiswa\Berita;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang digunakan model ini.
     */
    protected $table = 'user'; // karena nama tabel kamu 'user', bukan 'users'

    /**
     * Primary key dari tabel.
     */
    protected $primaryKey = 'id_user'; // disesuaikan dengan migration kamu

    /**
     * Apakah primary key auto-increment.
     */
    public $incrementing = true;

    /**
     * Jenis data primary key.
     */
    protected $keyType = 'int';

    /**
     * Kolom yang bisa diisi (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * Kolom yang disembunyikan dari output JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Casting kolom tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /* ==================================================
       🔗 Relasi antar tabel (sesuai ERD kamu)
    ================================================== */

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
