<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class BeritaController
 * 
 * Controller ini bertanggung jawab untuk mengelola data Berita/Artikel.
 * Fitur mencakup CRUD (Create, Read, Update, Delete) serta Verifikasi berita
 * yang diajukan oleh pengguna lain (jika ada fitur kontribusi user).
 * 
 * @package App\Http\Controllers\Admin
 */
class BeritaController extends Controller
{
    /**
     * Menampilkan daftar semua berita.
     * 
     * Data ditampilkan dengan paginasi (10 item per halaman) dan diurutkan
     * berdasarkan waktu pembuatan terbaru (latest).
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Berita::with(['user', 'verifikator', 'penolak']);

        // Filter Penulis (Nama User)
        if ($request->filled('penulis')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->penulis . '%');
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $beritas = $query->latest()->paginate(10);
        return view('pages.admin.berita.index', compact('beritas'));
    }

    /**
     * Menampilkan form untuk membuat berita baru.
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.admin.berita.create');
    }

    /**
     * Menyimpan berita baru ke database.
     * 
     * Proses mencakup:
     * 1. Validasi input (judul, isi, kategori, gambar).
     * 2. Upload gambar (jika ada) ke storage publik.
     * 3. Penyimpanan data ke database dengan status default 'verified' (karena dibuat oleh Admin).
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
     * Menampilkan form edit untuk berita tertentu.
     * 
     * @param int $id ID Berita yang akan diedit
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('pages.admin.berita.edit', compact('berita'));
    }

    /**
     * Memperbarui data berita yang sudah ada.
     * 
     * Menangani penggantian gambar:
     * - Jika gambar baru diupload, gambar lama akan dihapus dari storage.
     * - Jika tidak ada gambar baru, gambar lama tetap dipertahankan.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
            // Admin bisa ubah status juga, default biarkan tetap
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
     * Menghapus berita secara permanen.
     * 
     * Juga menghapus file gambar terkait dari storage untuk menghemat ruang penyimpanan.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
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
     * Memverifikasi berita yang statusnya 'pending'.
     * 
     * Digunakan jika ada alur kontribusi berita dari user biasa yang butuh persetujuan admin.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifikasi($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->status = 'verified';
        $berita->id_verifikator = auth()->id(); // Catat verifikator
        $berita->save();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diverifikasi.');
    }

    /**
     * Menolak berita yang diajukan.
     * 
     * Mengubah status berita menjadi 'rejected'.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tolak($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->status = 'rejected';
        $berita->id_penolak = auth()->id(); // Catat penolak
        $berita->save();

        return redirect()->route('admin.berita.index')->with('success', 'Berita ditolak.');
    }
}
