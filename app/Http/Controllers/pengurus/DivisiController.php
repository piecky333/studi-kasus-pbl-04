<?php

namespace App\Http\Controllers\pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\divisi;

class DivisiController extends Controller
{
    // 📋 Tampilkan semua divisi
    public function index()
    {
        $divisi = Divisi::orderBy('created_at', 'desc')->get();
        return view('pages.divisi.index', compact('divisi'));
    }

    // ➕ Form tambah divisi
    public function create()
    {
        return view('pages.divisi.create');
    }

    // 💾 Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'isi_divisi' => 'nullable|string',
            'foto_divisi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_divisi')) {
            $fotoPath = $request->file('foto_divisi')->store('uploads/divisi', 'public');
        }

        Divisi::create([
            'nama_divisi' => $request->nama_divisi,
            'isi_divisi' => $request->isi_divisi,
            'foto_divisi' => $fotoPath,
        ]);

        return redirect()->route('pengurus.divisi.index')->with('success', 'Divisi berhasil ditambahkan!');
    }

    // ✏️ Form edit
    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('pages.divisi.edit', compact('divisi'));
    }

    // 🔄 Update data
    public function update(Request $request, $id)
    {
        $divisi = Divisi::findOrFail($id);

        $request->validate([
            'nama_divisi' => 'required|string|max:255',
            'isi_divisi' => 'nullable|string',
            'foto_divisi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = $divisi->foto_divisi;
        if ($request->hasFile('foto_divisi')) {
            $fotoPath = $request->file('foto_divisi')->store('uploads/divisi', 'public');
        }

        $divisi->update([
            'nama_divisi' => $request->nama_divisi,
            'isi_divisi' => $request->isi_divisi,
            'foto_divisi' => $fotoPath,
        ]);

        return redirect()->route('pengurus.divisi.index')->with('success', 'Divisi berhasil diperbarui!');
    }

    // 🗑️ Hapus data
    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);
        $divisi->delete();

        return redirect()->route('pengurus.divisi.index')->with('success', 'Divisi berhasil dihapus!');
    }
}
