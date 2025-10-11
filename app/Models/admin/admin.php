<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
<<<<<<< HEAD
    protected $fillable = [
        'id_user',
        'nama',
        'email',
        'username',
        'password'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }

    public function prestasi()
    {
        return $this->hasMany(prestasi::class, 'id_admin', 'id_admin');
=======
    protected $fillable = ['id_user', 'nama', 'email'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
>>>>>>> 0dc1741 (Menambahkan PrestasiController dan memperbarui model serta view terkait)
    }

    public function tanggapan()
    {
<<<<<<< HEAD
        return $this->hasMany(\App\Models\laporan\tanggapan::class, 'id_admin', 'id_admin');
=======
        return $this->hasMany(Tanggapan::class, 'id_admin');
    }

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'id_admin');
    }

    public function sanksi()
    {
        return $this->hasMany(Sanksi::class, 'id_admin');
>>>>>>> 0dc1741 (Menambahkan PrestasiController dan memperbarui model serta view terkait)
    }
}
