<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasHashid;

class Kriteria extends Model
{
    use HasFactory, HasHashid;

    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $timestamps = false;

    protected $fillable = [
        'id_keputusan',
        'nama_kriteria',
        'kode_kriteria',
        'jenis_kriteria',
        'bobot_kriteria',
        'sumber_data',
        'atribut_sumber',
    ];

    public function keputusan()
    {
        return $this->belongsTo(SpkKeputusan::class, 'id_keputusan');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_kriteria');
    }

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, 'id_kriteria', 'id_kriteria');
    }
}

