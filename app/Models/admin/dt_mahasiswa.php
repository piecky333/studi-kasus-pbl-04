<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dt_mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'dt_mahasiswa';
    protected $primaryKey = 'id_dtmahasiswa';
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'semester'
    ];

    public function prestasi()
    {
        return $this->hasMany(prestasi::class, 'id_dtmahasiswa', 'id_dtmahasiswa');
    }

    public function sanksi()
    {
        return $this->hasMany(sanksi::class, 'id_dtmahasiswa', 'id_dtmahasiswa');
    }

    public function keuangan()
    {
        return $this->hasMany(keuangan::class, 'id_anggota', 'id_dtmahasiswa');
    }
}
