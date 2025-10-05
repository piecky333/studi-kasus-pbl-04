<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mahasiswa_berprestasi extends Model
{
    protected $fillable = [
 'id_mhs_prestasi',
 'id_mahasiswa',
 'nama',
 'nim',
 'nim',
 'tahun',
 'tingkat',
 'nama_lomba',
 'jenis_prestasi',
 'status_validasi',
 ];

}
