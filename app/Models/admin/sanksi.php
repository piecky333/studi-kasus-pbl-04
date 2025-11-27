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
        'id_mahasiswa',
        'tanggal_sanksi',
        'jenis_sanksi',
        'jenis_hukuman',
        'keterangan'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Datamahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
