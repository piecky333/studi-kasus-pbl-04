<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasHashid;

class Tanggapan extends Model
{
    use HasFactory, HasHashid;

    protected $table = 'tanggapan';
    protected $primaryKey = 'id_tanggapan';
    public $timestamps = true;

    protected $fillable = [
        'id_pengaduan',
        'id_admin',
        'id_user',
        'isi_tanggapan',
        'tanggal_tanggapan',
    ];

    protected $casts = [
        'tanggal_tanggapan' => 'date',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
