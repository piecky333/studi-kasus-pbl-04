<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class keuangan extends Model
{
    protected $fillable = [
 'id_iuran',
 'id_anggota',
 'id_divisi',
 'jumlah_iuran',
 'tanggal_bayar',
 'deadline',
 'metode_pembayaran',
 ];

}
