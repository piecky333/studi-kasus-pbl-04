<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tanggapan;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = [
        'id_user',
        'nama_admin',
        'jabatan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_admin');
    }

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'id_admin');
    }

    public function sanksi()
    {
        return $this->hasMany(Sanksi::class, 'id_admin');
    }

    public function mahasiswa()
    {
        return $this->hasMany(DataMahasiswa::class, 'id_admin');
    }
}
