<?php

namespace App\Models\laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';
    protected $fillable = [
        'id_user',
        'tanggal_laporan',
        'jenis_kasus',
        'deskripsi',
        'status_validasi'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }

    public function tanggapan()
    {
        return $this->hasMany(tanggapan::class, 'id_laporan', 'id_laporan');
    }

    public function terlapor()
    {
        return $this->hasMany(terlapor::class, 'id_laporan', 'id_laporan');
    }
}
