<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Pengaduan;
use App\Models\Admin;
use App\Models\Komentar;
use App\Models\Berita;

use App\Traits\HasHashid;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasHashid;

    const ROLE_ADMIN = 'admin';
    const ROLE_PENGURUS = 'pengurus';
    const ROLE_USER = 'user';
    const ROLE_MAHASISWA = 'mahasiswa';

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
        'no_telpon',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user', 'id_user');
    }

    public function mahasiswa()
    {
        return $this->hasOne(\App\Models\DataMahasiswa::class, 'id_user', 'id_user');
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_user', 'id_user');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_user', 'id_user');
    }

    public function berita()
    {
        return $this->hasMany(Berita::class, 'id_user', 'id_user');
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }

        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http')) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&color=7F9CF5&background=EBF4FF';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }
}