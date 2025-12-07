<?php

namespace App\Http\Controllers\public;

use App\Http\Controllers\Controller;
use App\Models\berita;
use App\Models\komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class KomentarController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    /**
     * Simpan komentar baru atau balasan.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id_berita
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $id_berita)
    {
        // 1. Validasi
        $request->validate([
            'isi' => 'required|string|max:1000',
            'nama_komentator' => Auth::guest() ? 'required|string|max:255' : 'nullable|string|max:255',

            // Validasi 'parent_id' (Kunci untuk nested comment)
            // Memastikan jika parent_id dikirim, ID tersebut ada di tabel komentar
            'parent_id' => 'nullable|exists:komentar,id_komentar',
        ]);

        // Cari berita terkait.
        $berita = berita::findOrFail($id_berita);

        // Siapkan data komentar.
        $data = [
            'id_berita' => $berita->id_berita,
            'isi'       => $request->isi,
            'parent_id' => $request->parent_id, 
        ];

        // Cek status login.
        if (Auth::check()) {
            $data['id_user'] = Auth::id();
            $data['nama_komentator'] = Auth::user()->nama;
        } else {
            $data['id_user'] = null; 
            $data['nama_komentator'] = $request->nama_komentator;
        }

        // Simpan komentar ke database
        komentar::create($data);

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Komentar Anda berhasil dikirim.');
    }


    /**
     * Perbarui komentar.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\komentar $komentar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, komentar $komentar)
    {
        // 1. Otorisasi.
        $this->authorize('update', $komentar);

        // 2. Validasi
        $request->validate([
            'isi' => 'required|string|max:1000',
        ]);

        // 3. Update data.
        $komentar->update([
            'isi' => $request->isi,
        ]);

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }


    /**
     * Hapus komentar.
     *
     * @param \App\Models\komentar $komentar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(komentar $komentar)
    {
        // 1. Otorisasi.
        $this->authorize('destroy', $komentar);

        // 2. Hapus data.
        $komentar->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}