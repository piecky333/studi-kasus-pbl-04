<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengurus extends Model
{
    use HasFactory;

    protected $table = 'pengurus';
    protected $primaryKey = 'id_pengurus';
    protected $fillable = [
        'id_divisi',
        'id_user',
        'id_jabatan'
    ];

    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'id_divisi', 'id_divisi');
    }

    public function jabatan()
    {
        return $this->belongsTo(\App\Models\Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }

       public function keuangan()
    {
        return $this->hasMany(keuangan::class, 'id_pengurus', 'id_pengurus');
    }
}
