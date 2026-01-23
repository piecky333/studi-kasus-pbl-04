<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Divisi;

class DivisiController extends Controller
{
    /**
     * Tampilkan daftar semua divisi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $semuaDivisi = Divisi::orderBy('nama_divisi')->get(); 

        return view('pages.public.divisi.index', compact('semuaDivisi'));
    }

    /**
     * Tampilkan detail divisi.
     *
     * @param string|int $slugOrId
     * @return \Illuminate\View\View
     */
    public function show($slugOrId) 
    {
        $divisi = Divisi::findOrFail($slugOrId);

        return view('pages.public.divisi.show', compact('divisi'));
    }
}
