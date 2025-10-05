<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mahasiswa_bermasalah extends Model
{
    protected $fillable = [
 'id_mhs_bermasalah',
 'id_mahasiswa',
 'nama',
 'jenis_masalah',
 'tanggal_lapor',
 'status_validasi',
 'id_laporan',
 ];

}
