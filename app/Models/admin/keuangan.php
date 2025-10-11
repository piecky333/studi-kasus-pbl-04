<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangan';
    protected $primaryKey = 'id_iuran';
    protected $fillable = [
        'id_divisi',
        'id_pengurus', 
        'jumlah_iuran',
        'tanggal_bayar',
        'deadline',
        'metode_pembayaran',
        'status_pembayaran'
    ];

    // relasi ke divisi
    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'id_divisi', 'id_divisi');
    }

    // relasi ke pengurus (pengganti anggota)
    public function pengurus()
    {
        return $this->belongsTo(pengurus::class, 'id_pengurus', 'id_pengurus');
    }
}
