<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sanksi extends Model
{
    protected $fillable = [
 'id_sanksi',
 'id_mhs_bermasalah',
 'jenis_sanksi',
 'tanggal_sanksi',
 'status',
 'id_admin',
 ];

}
