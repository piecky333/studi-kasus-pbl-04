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
        'posisi_jabatan'
    ];

    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'id_divisi', 'id_divisi');
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
