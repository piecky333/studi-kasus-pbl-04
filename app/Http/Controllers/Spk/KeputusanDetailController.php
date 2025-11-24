<?php

namespace App\Http\Controllers\Spk;

use App\Http\Controllers\Controller;
use App\Models\spkkeputusan;
use Illuminate\Http\Request;

/**
 * Abstract Base Controller untuk semua halaman detail Keputusan SPK.
 * Controller ini memastikan model Keputusan sudah dimuat sebelum method child dijalankan.
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
     * Constructor akan mencari objek Keputusan berdasarkan ID di URL.
     * * @param int $idKeputusan
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