<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHashid;

class Divisi extends Model
{
    use HasFactory, HasHashid;

    protected $table = 'divisi';
    protected $primaryKey = 'id_divisi';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'nama_divisi',
        'isi_divisi',
        'foto_divisi',
    ];

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class, 'id_divisi', 'id_divisi');
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatan::class, 'id_divisi', 'id_divisi');
    }
}


