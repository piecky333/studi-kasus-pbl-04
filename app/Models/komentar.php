<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class komentar extends Model
{
    protected $fillable = [
 'id_komentar',
 'id_berita',
 'id_user',
 'isi',
 'tanggal',
 ];

}
