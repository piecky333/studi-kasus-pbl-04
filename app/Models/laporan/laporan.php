<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class laporan extends Model
{
    protected $fillable = [
 'id_laporan',
 'id_pelapor',
 'id_terlapor',
 'judul_laporan',
 'isi_laporan',
 'tanggal_lapor',
 'bukti_laporan',
 'status_laporan',
 'id_admin',
  ];

}
