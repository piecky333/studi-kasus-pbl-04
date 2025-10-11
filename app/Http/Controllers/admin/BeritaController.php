<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    // Tampilkan semua berita
    public function index()
    {
        $beritas = Berita::latest()->get();
        return view('pages.berita.index', compact('beritas'));
    }

    // Form tambah berita
    public function create()
    {
        return view('pages.berita.create');
    }

    // Simpan berita baru
   public function store(Request $request)
{
    $request->validate([
        'judul_berita' => 'required|string|max:255',
        'isi_berita' => 'required',
        'gambar_berita' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    $data = $request->only(['judul_berita', 'isi_berita']); 
    $data['id_user'] = auth()->id() ?? 1; 

    if ($request->hasFile('gambar_berita')) {
        $data['gambar_berita'] = $request->file('gambar_berita')->store('berita', 'public');
    }
    $data['tanggal'] = now();
    Berita::create($data);

    return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
}


    // Form edit berita
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('pages.berita.edit', compact('berita'));
    }

    // Update berita
    public function update(Request $request, $id)
{
    $berita = Berita::findOrFail($id);

    $request->validate([
        'judul_berita' => 'required|string|max:255',
        'isi_berita' => 'required',
        'gambar_berita' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    $data = $request->only(['judul_berita', 'isi_berita']);

    if ($request->hasFile('gambar_berita')) {
        $data['gambar_berita'] = $request->file('gambar_berita')->store('berita', 'public');
    }

    $berita->update($data);

    return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diupdate.');
}


    // Hapus berita
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();

        return redirect()->route('pages.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
