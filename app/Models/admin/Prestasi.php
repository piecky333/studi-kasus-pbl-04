<?php

namespace App\Models\admin; 

use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\admin\Admin; 

class Prestasi extends Model
{
    // Casing tabel disesuaikan
    protected $table = 'prestasi'; 
    protected $primaryKey = 'id_prestasi';
    
    protected $fillable = [
        // Tambahkan Foreign Key dan Peringkat/Juara
        'id_mahasiswa',     
        'id_admin',         
        'tingkat', 
        'peringkat', // Kolom Juara/Peringkat
        'tahun', 
        'nama_kegiatan', 
        'jenis_prestasi', 
        'status_validasi',
    ];

    // Relasi ke Mahasiswa (belongsTo)
    public function mahasiswa()
    {
        // Panggil Class Mahasiswa dari root namespace
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    // Relasi ke Admin (belongsTo)
    public function admin()
    {
        // Panggil Class Admin dari namespace yang sama
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    // Catatan: Relasi ke Pengurus Anda hilangkan karena membingungkan, 
    // tapi jika diperlukan, pastikan Model Pengurus juga memiliki namespace yang benar.
}