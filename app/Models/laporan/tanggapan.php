<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tanggapan extends Model
{
    protected $fillable = [
 'id_taanggapan',
 'id_laporan',
 'id_admin',
 'tanggapan',
 'tanggal_tanggapan',
 ];

}
