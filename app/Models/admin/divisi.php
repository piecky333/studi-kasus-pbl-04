<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';
    protected $fillable = [
        'nama_divisi',
        'isi_divisi',
        'foto_divisi'
    ];

    public function pengurus()
    {
        return $this->hasMany(pengurus::class, 'id_divisi', 'id_divisi');
    }

    public function jabatan()
    {
        return $this->hasMany(\App\Models\jabatan::class, 'id_divisi', 'id_divisi');
    }

    public function keuangan()
    {
        return $this->hasMany(keuangan::class, 'id_divisi', 'id_divisi');
    }
}
