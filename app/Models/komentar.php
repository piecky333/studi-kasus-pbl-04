<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class komentar extends Model
{
    use HasFactory;

    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';
    protected $fillable = [
        'id_berita',
        'id_user',
        'isi'
    ];

    public function berita()
    {
        return $this->belongsTo(berita::class, 'id_berita', 'id_berita');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }
}
