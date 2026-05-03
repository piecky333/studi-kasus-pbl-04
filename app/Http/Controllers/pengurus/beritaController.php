<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::where('id_user', auth()->user()->id_user)->latest();

        if ($request->filled('search')) {
            $query->where('judul_berita', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $beritas = $query->paginate(10);

        return view('pages.pengurus.berita.index', compact('beritas'));
    }

    public function create()
    {
        return view('pages.pengurus.berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_berita'  => 'required|string|max:255',
            'isi_berita'    => 'required|string',
            'gambar_berita' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambar = null;
        if ($request->hasFile('gambar_berita')) {
            $gambar = $request->file('gambar_berita')->store('uploads/berita', 'public');
        }

        Berita::create([
            'id_user'       => auth()->user()->id_user,
            'judul_berita'  => $request->judul_berita,
            'isi_berita'    => $request->isi_berita,
            'kategori'      => 'kegiatan',
            'gambar_berita' => $gambar,
            'status'        => 'pending',
        ]);

        return redirect()->route('pengurus.berita.index')
                         ->with('success', 'Berita berhasil dibuat dan menunggu verifikasi admin.');
    }

    public function edit(Berita $berita)
    {
        if ($berita->id_user !== auth()->user()->id_user) {
            abort(403);
        }

        return view('pages.pengurus.berita.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        if ($berita->id_user !== auth()->user()->id_user) {
            abort(403);
        }

        $request->validate([
            'judul_berita'  => 'required|string|max:255',
            'isi_berita'    => 'required|string',
            'gambar_berita' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambar = $berita->gambar_berita;
        if ($request->hasFile('gambar_berita')) {
            if ($berita->gambar_berita) {
                Storage::disk('public')->delete($berita->gambar_berita);
            }
            $gambar = $request->file('gambar_berita')->store('uploads/berita', 'public');
        }

        $berita->update([
            'judul_berita'  => $request->judul_berita,
            'isi_berita'    => $request->isi_berita,
            'kategori'      => 'kegiatan',
            'gambar_berita' => $gambar,
        ]);

        return redirect()->route('pengurus.berita.index')
                         ->with('success', 'Berita berhasil diperbarui dan menunggu verifikasi admin.');
    }

    public function show(Berita $berita)
    {
        // Hanya pemilik berita yang bisa melihat detail via panel pengurus (opsional, tergantung kebijakan)
        // Atau biarkan saja jika ingin bisa melihat semua berita organisasi.
        // Di sini saya batasi ke pemilik agar konsisten dengan index.
        if ($berita->id_user !== auth()->user()->id_user) {
            abort(403);
        }

        return view('pages.pengurus.berita.show', compact('berita'));
    }

    public function destroy(Berita $berita)
    {
        if ($berita->id_user !== auth()->user()->id_user) {
            abort(403);
        }

        if ($berita->gambar_berita) {
            Storage::disk('public')->delete($berita->gambar_berita);
        }

        $berita->delete();

        return redirect()->route('pengurus.berita.index')
                         ->with('success', 'Berita berhasil dihapus.');
    }
}

