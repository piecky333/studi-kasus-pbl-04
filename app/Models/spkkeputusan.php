<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkKeputusan extends Model
{
    use HasFactory;

    protected $table = 'spkkeputusan';  

    // PRIMARY KEY
    protected $primaryKey = 'id_keputusan'; 
    public $incrementing = true; 

    protected $fillable = [
        'nama_keputusan', 
        'tanggal_dibuat', // Jika ini diisi dari controller, masukkan.
        'status',         // Jika ini diisi dari controller, masukkan.
    ];

    // Kolom yang harus di-cast ke tipe data tertentu (opsional)
    protected $casts = [
        'tanggal_dibuat' => 'datetime',
    ];
    
    public function kriteria()
    {
        return $this->hasMany(Kriteria::class, 'id_keputusan', 'id_keputusan');
    }
    
    public function alternatif()
    {
        return $this->hasMany(Alternatif::class, 'id_keputusan', 'id_keputusan');
    }

}