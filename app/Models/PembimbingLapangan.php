<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembimbingLapangan extends Model
{
    use HasFactory;

    protected $table = 'pembimbing_lapangan';
    protected $primaryKey = 'id_pembimbing';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'nama_perusahaan',
        'alamat_perusahaan',
        'no_telepon',
        'email_perusahaan',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Kuota
    public function kuota()
    {
        return $this->hasMany(Kuota::class, 'id_pembimbing');
    }
}
