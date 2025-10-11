<?php

namespace App\Models\laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tanggapan extends Model
{
    use HasFactory;

    protected $table = 'tanggapan';
    protected $primaryKey = 'id_tanggapan';
    protected $fillable = [
        'id_laporan',
        'id_admin',
        'isi_tanggapan',
        'tanggal'
    ];

    public function laporan()
    {
        return $this->belongsTo(laporan::class, 'id_laporan', 'id_laporan');
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\admin\admin::class, 'id_admin', 'id_admin');
    }
}
