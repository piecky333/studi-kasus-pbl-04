<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $fillable = ['username', 'password', 'nama', 'email'];

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_admin');
    }

    public function bermasalah()
    {
        return $this->hasMany(Mahasiswa_bermasalah::class, 'id_admin');
    }

    public function sanksi()
    {
        return $this->hasMany(Sanksi::class, 'id_admin');
    }
}
