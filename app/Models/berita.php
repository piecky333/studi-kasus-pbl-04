<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id_berita';
    protected $fillable = ['judul_berita', 'isi_berita', 'gambar_berita', 'tanggal', 'status', 'id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class, 'id_berita');
    }
}

