<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilAkhir extends Model
{
    protected $table = 'hasil_akhir';
    protected $primaryKey = 'id_hasil';
    public $timestamps = false;

    protected $fillable = [
        'id_alternatif',
        'skor_akhir',
        'rangking',
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif');
    }
}
