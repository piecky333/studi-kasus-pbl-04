<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';
    protected $fillable = ['nama_divisi', 'isi_divisi', 'foto_divisi'];

    public function anggota()
    {
        return $this->hasMany(Anggota::class, 'id_divisi');
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'id_divisi');
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'id_divisi');
    }
}

