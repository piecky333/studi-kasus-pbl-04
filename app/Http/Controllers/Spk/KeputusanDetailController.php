<?php

namespace App\Http\Controllers\Spk;

use App\Http\Controllers\Controller;
use App\Models\SpkKeputusan;
use Illuminate\Http\Request;

abstract class KeputusanDetailController extends Controller
{
    /**
     * @var SpkKeputusan
     */
    protected $keputusan;

    /**
     * @var int
     */
    protected $idKeputusan;

    /**
     * Mengambil model Keputusan secara otomatis dari route parameter.
     * Karena seluruh route detail SPK menggunakan prefix {keputusan},
     * Laravel akan menyuntikkan model tersebut di sini.
     */
    public function __construct(Request $request)
    {
        $this->keputusan = $request->route('keputusan');
        if ($this->keputusan) {
            $this->idKeputusan = $this->keputusan->id_keputusan;
        }
    }
}