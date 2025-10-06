<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tanggapan extends Model
{
    protected $table = 'tanggapan';
    protected $primaryKey = 'id_tanggapan';
    protected $fillable = ['id_admin', 'id_laporan', 'isi_tanggapan', 'tanggal'];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'id_laporan');
    }
}
