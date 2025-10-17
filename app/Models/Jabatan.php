<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';
    protected $fillable = [
        'id_divisi',
        'nama_jabatan',
    ];

    public function divisi()
    {
        return $this->belongsTo(\App\Models\admin\Divisi::class, 'id_divisi', 'id_divisi');
    }
}
