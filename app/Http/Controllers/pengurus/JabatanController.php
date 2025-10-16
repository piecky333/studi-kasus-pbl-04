<?php

namespace App\Http\Controllers\pengurus;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\admin\Divisi;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    // ✅ 1. Tampilkan semua jabatan
    public function index()
    {
        $jabatan = Jabatan::with('divisi')->latest()->get();
        return view('pages.pengurus.jabatan.index', compact('jabatan'));
    }

    // ✅ 2. Form tambah jabatan
    public function create()
    {
        $divisi = Divisi::all();
        return view('pages.pengurus.jabatan.create', compact('divisi'));
    }

    // ✅ 3. Simpan data baru
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

    // ✅ 4. Form edit jabatan
    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $divisi = Divisi::all();
        return view('pages.pengurus.jabatan.edit', compact('jabatan', 'divisi'));
    }

    // ✅ 5. Update jabatan
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

    // ✅ 6. Hapus jabatan
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('pengurus.jabatan.index')
                         ->with('success', 'Jabatan berhasil dihapus!');
    }
}
