<?php

namespace App\Http\Controllers\pengurus;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\admin\Divisi;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Tampilkan daftar semua jabatan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $jabatan = Jabatan::with('divisi')->latest()->get();
        return view('pages.pengurus.jabatan.index', compact('jabatan'));
    }

    /**
     * Tampilkan form tambah jabatan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $divisi = Divisi::all();
        return view('pages.pengurus.jabatan.create', compact('divisi'));
    }

    /**
     * Simpan data jabatan baru.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'nama_jabatan' => 'required|string|max:100',
        ]);

        Jabatan::create($request->all());

        return redirect()->route('pengurus.jabatan.index')
                         ->with('success', 'Jabatan baru berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit jabatan.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $divisi = Divisi::all();
        return view('pages.pengurus.jabatan.edit', compact('jabatan', 'divisi'));
    }

    /**
     * Perbarui data jabatan.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_divisi' => 'required|exists:divisi,id_divisi',
            'nama_jabatan' => 'required|string|max:100',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($request->all());

        return redirect()->route('pengurus.jabatan.index')
                         ->with('success', 'Jabatan berhasil diperbarui!');
    }

    /**
     * Hapus data jabatan.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('pengurus.jabatan.index')
                         ->with('success', 'Jabatan berhasil dihapus!');
    }
}
