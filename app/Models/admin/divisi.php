<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nama_divisi',
        'isi_divisi',
        'foto_divisi'
    ];

    // RELASI
    public function pengurus()
    {
        return $this->hasMany(\App\Models\Admin\Pengurus::class, 'id_divisi', 'id_divisi');
    }

    public function jabatan()
    {
        return $this->hasMany(\App\Models\Jabatan::class, 'id_divisi', 'id_divisi');
    }

    public function keuangan()
    {
        return $this->hasMany(\App\Models\Admin\Keuangan::class, 'id_divisi', 'id_divisi');
    }
}
