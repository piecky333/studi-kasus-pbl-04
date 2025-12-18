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
        $jabatan = Jabatan::latest()->get();
        return view('pages.pengurus.jabatan.index', compact('jabatan'));
    }

    /**
     * Tampilkan form tambah jabatan.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.pengurus.jabatan.create');
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
            'nama_jabatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
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
        return view('pages.pengurus.jabatan.edit', compact('jabatan'));
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
            'nama_jabatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
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
