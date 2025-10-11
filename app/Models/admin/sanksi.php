<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sanksi extends Model
{
    use HasFactory;

    protected $table = 'sanksi';
    protected $primaryKey = 'id_sanksi';
    protected $fillable = [
        'id_dtmahasiswa',
        'tanggal_sanksi',
        'jenis_sanksi'
    ];

    public function dt_mahasiswa()
    {
        return $this->belongsTo(dt_mahasiswa::class, 'id_dtmahasiswa', 'id_dtmahasiswa');
    }
}
