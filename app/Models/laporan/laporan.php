<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';
    protected $fillable = ['tanggal_laporan', 'jenis_kasus', 'deskripsi', 'status', 'id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function terlapor()
    {
        return $this->hasMany(Terlapor::class, 'id_laporan');
    }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_laporan');
    }
}
