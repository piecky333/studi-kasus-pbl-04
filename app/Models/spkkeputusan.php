<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasHashid;

class SpkKeputusan extends Model
{
    use HasFactory, HasHashid;

    protected $table = 'spkkeputusan';
    protected $primaryKey = 'id_keputusan';
    public $incrementing = true;

    protected $fillable = [
        'nama_keputusan',
        'tanggal_dibuat',
        'status',
    ];

    protected $casts = [
        'tanggal_dibuat' => 'datetime',
    ];

    public function kriteria()
    {
        return $this->hasMany(Kriteria::class, 'id_keputusan', 'id_keputusan');
    }

    public function alternatif()
    {
        return $this->hasMany(Alternatif::class, 'id_keputusan', 'id_keputusan');
    }
}
