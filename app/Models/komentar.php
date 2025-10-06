<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';
    protected $fillable = ['id_berita', 'id_user', 'isi'];

    public function berita()
    {
        return $this->belongsTo(Berita::class, 'id_berita');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

