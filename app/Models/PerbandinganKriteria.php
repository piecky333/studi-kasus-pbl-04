<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerbandinganKriteria extends Model
{
    protected $table = 'perbandingan_kriteria';
    protected $primaryKey = 'id_perbandingan';
    public $timestamps = false;

    protected $fillable = [
        'id_keputusan',
        'id_kriteria_1',
        'id_kriteria_2',
        'nilai_perbandingan',
    ];

    public function kriteria1()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria_1');
    }

    public function kriteria2()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria_2');
    }
}
