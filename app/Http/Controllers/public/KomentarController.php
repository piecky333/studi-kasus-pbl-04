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
     * Menyimpan komentar baru (induk) ATAU balasan (nested).
     * Method ini dipanggil oleh: Route::post('/berita/{id_berita}/komentar', ...)
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

        // Cari berita yang dikomentari (jika tidak ada, akan 404)
        $berita = berita::findOrFail($id_berita);

        // Siapkan data untuk disimpan
        $data = [
            'id_berita' => $berita->id_berita,
            'isi'       => $request->isi,
            
            // Simpan 'parent_id' dari form. 
            // Jika ini komentar induk baru, $request->parent_id akan null.
            // Jika ini balasan, $request->parent_id akan berisi ID induk.
            'parent_id' => $request->parent_id, 
        ];

        // Cek apakah user login atau tamu
        if (Auth::check()) {
            // User sedang login
            $data['id_user'] = Auth::id();
            $data['nama_komentator'] = Auth::user()->nama;
        } else {
            // User adalah tamu
            $data['id_user'] = null; 
            $data['nama_komentator'] = $request->nama_komentator;
        }

        // Simpan komentar ke database
        komentar::create($data);

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Komentar Anda berhasil dikirim.');
    }


    /**
     * Memperbarui komentar yang sudah ada.
     * Method ini dipanggil oleh: Route::put('/komentar/{komentar}', ...)
     */
    public function update(Request $request, komentar $komentar)
    {
        // 1. OTORISASI (Cek KomentarPolicy@update)
        $this->authorize('update', $komentar);

        // 2. Validasi
        $request->validate([
            'isi' => 'required|string|max:1000',
        ]);

        // 3. Logika Update
        $komentar->update([
            'isi' => $request->isi,
        ]);

        return back()->with('success', 'Komentar berhasil diperbarui.');
    }


    /**
     * Menghapus komentar.
     * Method ini dipanggil oleh: Route::delete('/komentar/{komentar}', ...)
     */
    public function destroy(komentar $komentar)
    {
        // 1. OTORISASI (Cek KomentarPolicy@destroy)
        $this->authorize('destroy', $komentar);

        // 2. Logika Hapus
        $komentar->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}