<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class terlapor extends Model
{
    protected $fillable = [
 'id_terlapor',
 'nama_terlapor',
 'nim_terlapor',
 'keterangan',
 'id_mahasiswa',
 ];

}
