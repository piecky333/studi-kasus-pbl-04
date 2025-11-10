<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Menampilkan semua berita (dengan paginasi)
     */
    public function index()
    {
        $beritas = Berita::latest()->paginate(10); 
        return view('pages.admin.berita.index', compact('beritas'));
    }

    /**
     * Menampilkan form tambah berita
     */
    public function create()
    {
        return view('pages.admin.berita.create');
    }

    /**
     * Menyimpan berita baru
     */
    public function store(Request $request)
    {
        // Validasi Anda di sini sudah benar
        $request->validate([
            'judul_berita' => 'required|string|max:255',
            'isi_berita' => 'required',
            'kategori' => 'required|string|in:kegiatan,prestasi',
            'gambar_berita' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $pathGambar = null;
        if ($request->hasFile('gambar_berita')) {
            $pathGambar = $request->file('gambar_berita')->store('berita', 'public');
        }

        //menyimpan data berita ke database
        Berita::create([
            'id_user' => auth()->id(),
            'judul_berita' => $request->judul_berita, 
            'isi_berita' => $request->isi_berita,     
            'gambar_berita' => $pathGambar,          
            'kategori' => $request->kategori,    
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit berita
     */
    public function edit($id)
    {
        // mengambil data berita berdasarkan ID
        $berita = Berita::findOrFail($id);
        return view('pages.admin.berita.edit', compact('berita'));
    }

    /**
     * Update berita yang ada
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul_berita' => 'required|string|max:255',
            'isi_berita' => 'required',
            'kategori' => 'required|string|in:kegiatan,prestasi',
            'gambar_berita' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // --- PERBAIKAN DI SINI ---
        // Key array HARUS SAMA DENGAN NAMA KOLOM DATABASE
        $data = [
            'judul_berita' => $request->judul_berita, // <-- DIUBAH
            'isi_berita'   => $request->isi_berita,   // <-- DIUBAH
            'kategori'     => $request->kategori,
        ];

        if ($request->hasFile('gambar_berita')) {
            // Hapus gambar lama JIKA ada
            // PERBAIKAN DI SINI
            if ($berita->gambar_berita) { 
                Storage::disk('public')->delete($berita->gambar_berita); // <-- DIUBAH
            }
            // Simpan gambar baru
            // PERBAIKAN DI SINI
            $data['gambar_berita'] = $request->file('gambar_berita')->store('berita', 'public'); // <-- DIUBAH
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diupdate.');
    }

    /**
     * Hapus berita
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        // --- PERBAIKAN DI SINI ---
        if ($berita->gambar_berita) { // <-- DIUBAH
            Storage::disk('public')->delete($berita->gambar_berita); // <-- DIUBAH
        }
        
        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}