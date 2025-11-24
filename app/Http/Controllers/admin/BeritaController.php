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

        Berita::create([
            'id_user' => auth()->id(),
            'judul_berita' => $request->judul_berita, 
            'isi_berita' => $request->isi_berita,     
            'gambar_berita' => $pathGambar,          
            'kategori' => $request->kategori,
            'status' => 'verified', // Admin langsung verified
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit berita
     */
    public function edit($id)
    {
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

        $data = [
            'judul_berita' => $request->judul_berita,
            'isi_berita'   => $request->isi_berita,
            'kategori'     => $request->kategori,
            // Admin bisa ubah status juga kalau mau, default biarkan tetap
        ];

        if ($request->hasFile('gambar_berita')) {
            if ($berita->gambar_berita) { 
                Storage::disk('public')->delete($berita->gambar_berita);
            }
            $data['gambar_berita'] = $request->file('gambar_berita')->store('berita', 'public');
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

        if ($berita->gambar_berita) {
            Storage::disk('public')->delete($berita->gambar_berita);
        }
        
        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus');
    }

    /**
     * Verifikasi berita pending
     */
    public function verifikasi($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->status = 'verified';
        $berita->save();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diverifikasi.');
    }

    /**
     * Tolak berita pending
     */
    public function tolak($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->status = 'rejected';
        $berita->save();

        return redirect()->route('admin.berita.index')->with('success', 'Berita ditolak.');
    }
}
