<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'keuangan';
    protected $primaryKey = 'id_iuran';
    protected $fillable = ['id_anggota', 'id_divisi', 'jumlah_iuran', 'tanggal_bayar', 'deadline', 'metode_pembayaran', 'status_pembayaran'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }
}

