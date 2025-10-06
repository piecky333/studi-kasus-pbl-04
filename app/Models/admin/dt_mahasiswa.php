<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dt_Mahasiswa extends Model
{
    protected $table = 'dt_mahasiswa';
    protected $primaryKey = 'id_dtmahasiswa';
    protected $fillable = ['nim', 'nama', 'email', 'semester', 'alamat', 'no_hp'];

    public function prestasi()
    {
        return $this->hasMany(mahasiswa_berprestasi::class, 'id_dtmahasiswa');
    }

    public function bermasalah()
    {
        return $this->hasMany(mahasiswa_bermasalah::class, 'id_dtmahasiswa');
    }

    public function anggota()
    {
        return $this->hasOne(Anggota::class, 'id_mhs');
    }
}
