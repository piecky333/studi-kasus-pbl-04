<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dt_mahasiswa extends Model
{
    protected $fillable = [
 'id_mahasiswa',
 'id_admin',
 'nama',
 'nama',
 'nim',
 'semester',
 'alamat',
 'no_hp',
 'email',
 'status_validasi',
 ];

}
