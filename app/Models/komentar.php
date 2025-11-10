<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class komentar extends Model
{
    use HasFactory;

    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';
    
    // PERBARUI FILLABLE
    protected $fillable = [
        'id_berita',
        'id_user',
        'nama_komentator', 
        'isi',
        'parent_id',
    ];

    public function parent()
    {
        // Satu balasan HANYA memiliki SATU induk
        return $this->belongsTo(komentar::class, 'parent_id');
    }

    public function berita()
    {
        return $this->belongsTo(berita::class, 'id_berita', 'id_berita');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }

    public function replies()
    {
        // Satu komentar bisa memiliki BANYAK balasan
        return $this->hasMany(komentar::class, 'parent_id');
    }
}