<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Tampilkan daftar berita milik pengurus.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Berita::where('id_user', auth()->user()->id_user)->latest();

        // Filter search (Judul) - Optional since Admin uses Penulis search, but let's give them Title search capability if wanted, or stick to just filters to match "Layout".
        // Admin layout has "Cari Penulis". We will replace that with "Cari Judul" in view.
        if ($request->filled('search')) {
            $query->where('judul_berita', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $beritas = $query->paginate(10);

        return view('pages.pengurus.berita.index', compact('beritas'));
    }

    /**
     * Tampilkan form tambah berita.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.pengurus.berita.create');
    }

    /**
     * Simpan berita baru.
     * Set status default ke 'pending'.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_berita' => 'required|string|max:255',
            'isi_berita' => 'required|string',
            'kategori' => 'required|string|in:kegiatan,prestasi',
            'gambar_berita' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambar = null;
        if ($request->hasFile('gambar_berita')) {
            $gambar = $request->file('gambar_berita')->store('uploads/berita', 'public');
        }

        Berita::create([
            'id_user' => auth()->user()->id_user,
            'judul_berita' => $request->judul_berita,
            'isi_berita' => $request->isi_berita,
            'kategori' => $request->kategori,
            'gambar_berita' => $gambar,
            'status' => 'pending', // otomatis pending
        ]);

        return redirect()->route('pengurus.berita.index')
                         ->with('success', 'Berita berhasil dibuat dan menunggu verifikasi admin.');
    }

    /**
     * Tampilkan form edit berita.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $berita = Berita::where('id_user', auth()->user()->id_user)
                        ->findOrFail($id);

        return view('pages.pengurus.berita.edit', compact('berita'));
    }

    /**
     * Perbarui data berita.
     * Ganti gambar jika ada upload baru.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::where('id_user', auth()->user()->id_user)
                        ->findOrFail($id);

        $request->validate([
            'judul_berita' => 'required|string|max:255',
            'isi_berita' => 'required|string',
            'kategori' => 'required|string|in:kegiatan,prestasi',
            'gambar_berita' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambar = $berita->gambar_berita; // default gambar lama
        if ($request->hasFile('gambar_berita')) {
            // Hapus gambar lama jika ada
            if ($berita->gambar_berita) {
                Storage::disk('public')->delete($berita->gambar_berita);
            }
            $gambar = $request->file('gambar_berita')->store('uploads/berita', 'public');
        }

        $berita->update([
            'judul_berita' => $request->judul_berita,
            'isi_berita' => $request->isi_berita,
            'kategori' => $request->kategori,
            'gambar_berita' => $gambar,
            // status tetap pending atau status lama, pengurus tidak bisa ubah
        ]);

        return redirect()->route('pengurus.berita.index')
                         ->with('success', 'Berita berhasil diperbarui dan menunggu verifikasi admin.');
    }

    /**
     * Hapus berita.
     * Hapus file gambar terkait.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $berita = Berita::where('id_user', auth()->user()->id_user)
                        ->findOrFail($id);

        if ($berita->gambar_berita) {
            Storage::disk('public')->delete($berita->gambar_berita);
        }

        $berita->delete();

        return redirect()->route('pengurus.berita.index')
                         ->with('success', 'Berita berhasil dihapus.');
    }
}
