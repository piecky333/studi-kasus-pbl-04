<?php

namespace App\Http\Controllers\Spk;

use App\Http\Controllers\Controller;
use App\Models\spkkeputusan;
use Illuminate\Http\Request;

/**
 * Class KeputusanDetailController
 * 
 * Abstract Base Controller yang ditujukan untuk semua controller yang menangani
 * detail dari sebuah Keputusan SPK (Level 2).
 * 
 * Tanggung Jawab Utama:
 * 1. Memastikan parameter {idKeputusan} ada di URL.
 * 2. Memuat model Keputusan SPK yang sesuai dari database.
 * 3. Menyediakan properti $this->keputusan dan $this->idKeputusan untuk kelas turunannya.
 * 
 * Dengan mewarisi controller ini, tidak perlu mengulang logika pencarian Keputusan
 * di setiap controller anak (DRY Principle).
 * 
 * @package App\Http\Controllers\Spk
 */
abstract class KeputusanDetailController extends Controller
{
    /**
     * @var spkkeputusan
     */
    protected $keputusan;

    /**
     * @var int
     */
    protected $idKeputusan;

    /**
     * Constructor.
     * 
     * Secara otomatis dijalankan saat controller diinstansiasi.
     * Mengambil ID dari route parameter dan mencoba memuat model Keputusan.
     * Jika tidak ditemukan, akan otomatis melempar 404 (findOrFail).
     * 
     * @param Request $request
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function __construct(Request $request)
    {
        // Parameter {idKeputusan} harus ada di URL (sesuai routes/web.php)
        $this->idKeputusan = $request->route('idKeputusan');
        
        // Memuat model Keputusan, atau 404 jika tidak ditemukan
        $this->keputusan =spkkeputusan::findOrFail($this->idKeputusan);
    }
    
    // Tidak perlu ada method 'index' di sini, karena setiap child akan memiliki index sendiri.
}