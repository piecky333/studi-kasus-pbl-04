<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasHashid;

class Komentar extends Model
{
    use HasFactory, HasHashid;

    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';

    protected $fillable = [
        'id_berita',
        'id_user',
        'nama_komentator',
        'isi',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Komentar::class, 'parent_id');
    }

    public function berita()
    {
        return $this->belongsTo(Berita::class, 'id_berita', 'id_berita');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function replies()
    {
        return $this->hasMany(Komentar::class, 'parent_id');
    }
}
