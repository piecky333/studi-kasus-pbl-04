<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
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
    }

    public function tanggapan()
    {
        return $this->hasMany(\App\Models\laporan\tanggapan::class, 'id_admin', 'id_admin');
    }
}
