<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dt_mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'dt_mahasiswa';
    protected $primaryKey = 'id_dtmahasiswa';
    protected $fillable = ['nim', 'nama', 'email', 'semester'];

    public function admin()
    {
        return $this->belongsTo(\App\Models\admin\admin::class, 'id_admin');
    }

    public function prestasi()
    {
        return $this->hasMany(\App\Models\admin\Prestasi::class, 'id_dtmahasiswa');
    }

    public function sanksi()
    {
        return $this->hasMany(\App\Models\admin\sanksi::class, 'id_dtmahasiswa');
    }
}
