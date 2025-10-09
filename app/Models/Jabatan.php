<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    /**
     * Sesuaikan properti fillable dengan kolom baru di database
     */
    protected $fillable = [
        'nama_anggota',
        'jabatan_struktural',
        'divisi',
    ];
}

