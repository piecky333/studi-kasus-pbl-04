<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\laporan\Tanggapan;
use App\Models\admin\Prestasi;

class admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = [
        'id_user',
        'nama_admin',
        'jabatan',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_admin');
    }

    public function prestasi()
    {
        return $this->hasMany(Prestasi::class, 'id_admin');
    }
}
