<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class anggota extends Model
{
    protected $fillable = [
 'id_anggota',
 'id_user',
 'id_mahasiswa',
 'id_divisi',
 'nama',
 'jabatan',
 ];

}
