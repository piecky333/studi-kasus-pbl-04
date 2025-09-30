<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class berita extends Model
{
 protected $fillable = [
 'id_berita',
 'id_user',
 'judul_berita',
 'isi_berita',
 'gambar_berita',
 'tanggal',

 ];
}
