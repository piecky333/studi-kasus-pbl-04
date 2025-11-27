<?php

namespace App\Models; // PERBAIKAN: Namespace harus App\Models, bukan App

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\laporan\pengaduan; 
use App\Models\admin\admin;
use App\Models\komentar;
use App\Models\berita;
use App\Models\Mahasiswa; 

// PERBAIKAN: Hapus 'MustVerifyEmail'
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Ini sudah benar sesuai migrasimu
    protected $table = 'user'; 
    protected $primaryKey = 'id_user'; 

    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama', // Ini sudah benar (sesuai migrasimu)
        'username', 
        'email', 
        'password', 
        'role', 
        'google_id', 
        'avatar',
        'no_telpon', // TAMBAHAN
        'profile_photo_path', // TAMBAHAN
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Tambahkan ini (wajib ada)
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Hapus 'email_verified_at' karena tidak ada di migrasi
        'password' => 'hashed',
    ];

    /* ==================================================
     Relasi antar tabel
     ================================================== */

     // PERBAIKAN: Sesuaikan foreign key dan local key ke 'id_user'
    public function admin()
    {
        return $this->hasOne(admin::class, 'id_user', 'id_user');
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_user', 'id_user');
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_user', 'id_user');
    }

    public function komentar()
    {
        return $this->hasMany(komentar::class, 'id_user', 'id_user');
    }

    public function berita()
    {
        return $this->hasMany(berita::class, 'id_user', 'id_user');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        // fallback avatar kalo tidak ada foto
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}