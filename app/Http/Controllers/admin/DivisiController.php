<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\divisi;

/**
 * Class DivisiController
 * 
 * Controller ini bertanggung jawab untuk menampilkan informasi Divisi.
 * Saat ini fungsinya terbatas pada Read Only (Melihat daftar dan detail),
 * karena manajemen divisi mungkin dilakukan di level database atau fitur lain.
 * 
 * @package App\Http\Controllers\Admin
 */
class DivisiController extends Controller
{
    /**
     * Menampilkan daftar semua divisi.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $divisi = Divisi::orderBy('created_at', 'desc')->get();
        return view('pages.admin.divisi.index', compact('divisi'));
    }

    /**
     * Menampilkan detail informasi divisi tertentu.
     * 
     * @param int $id ID Divisi
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('pages.admin.divisi.show', compact('divisi'));
    }
}
