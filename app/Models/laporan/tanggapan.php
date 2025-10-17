<?php

namespace App\Models\laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\admin\admin;
use App\Models\laporan\Pengaduan;

class Tanggapan extends Model
{
    use HasFactory;

    protected $table = 'tanggapan';
    protected $primaryKey = 'id_tanggapan';
    protected $fillable = [
        'id_pengaduan',
        'id_admin',
        'isi_tanggapan',
        'tanggal_tanggapan',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan');
    }

    public function admin()
    {
        return $this->belongsTo(admin::class, 'id_admin');
    }
}
