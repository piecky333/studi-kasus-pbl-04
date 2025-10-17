<?php

namespace App\Models\laporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\laporan\Pengaduan;

class Terlapor extends Model
{
    use HasFactory;

    protected $table = 'terlapor';
    protected $primaryKey = 'id_terlapor';
    protected $fillable = [
        'id_pengaduan',
        'nama_terlapor',
        'no_hp_terlapor',
        'status_terlapor',
        'keterangan',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan');
    }
}
