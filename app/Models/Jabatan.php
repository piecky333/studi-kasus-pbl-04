<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';
    protected $fillable = [
        'id_divisi',
        'posisi_jabatan',
        'nama'
    ];

    public function divisi()
    {
        return $this->belongsTo(\App\Models\admin\divisi::class, 'id_divisi', 'id_divisi');
    }
}
