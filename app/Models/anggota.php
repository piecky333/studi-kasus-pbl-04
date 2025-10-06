<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';
    protected $fillable = ['id_mhs', 'id_divisi', 'jabatan', 'id_mhsprestasi', 'id_berita', 'id_sanksi', 'id_komentar'];

    public function mahasiswa()
    {
        return $this->belongsTo(dt_mahasiswa::class, 'id_mhs');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function berita()
    {
        return $this->belongsTo(Berita::class, 'id_berita');
    }

    public function sanksi()
    {
        return $this->belongsTo(Sanksi::class, 'id_sanksi');
    }

    public function komentar()
    {
        return $this->belongsTo(Komentar::class, 'id_komentar');
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'id_anggota');
    }
}
