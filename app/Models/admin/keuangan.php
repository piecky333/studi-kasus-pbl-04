<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangan';
    protected $primaryKey = 'id_keuangan';
    protected $fillable = [
        'id_pengurus',
        'id_divisi',
        'jumlah_iuran',
        'tanggal_bayar',
        'deadline',
        'metode_pembayaran',
        'status_pembayaran',
    ];

    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'id_pengurus');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }
}
