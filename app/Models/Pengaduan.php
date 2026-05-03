<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasHashid;

class Pengaduan extends Model
{
    use HasFactory, HasHashid;

    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'judul',
        'tanggal_pengaduan',
        'jenis_kasus',
        'deskripsi',
        'status',
        'gambar_bukti',
        'no_telpon_dihubungi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class, 'id_pengaduan');
    }

    public function mahasiswa()
    {
        return $this->hasOne(DataMahasiswa::class, 'id_user', 'id_user');
    }

    public function terlapor()
    {
        return $this->hasMany(Terlapor::class, 'id_pengaduan');
    }
}
