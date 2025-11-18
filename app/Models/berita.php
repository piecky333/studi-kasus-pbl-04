<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class berita extends Model
{
    use HasFactory;

    protected $table = 'berita';
    protected $primaryKey = 'id_berita';
    protected $fillable = [
        'id_user',
        'judul_berita',
        'isi_berita',
        'gambar_berita',
        'kategori',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }

    public function komentar()
    {
        return $this->hasMany(komentar::class, 'id_berita', 'id_berita');
    }
}
