<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan;
use App\Models\alternatif;
use App\Models\penilaian;
use App\Models\hasilakhir;

/**
 * Mengelola semua operasi CRUD untuk Alternatif dalam sebuah Keputusan SPK.
 * Selalu terikat pada parameter {idKeputusan}.
 */
class AlternatifController extends Controller
{
    /**
     * Menampilkan daftar Alternatif (View: pages.admin.spk.alternatif_view).
     */
    public function index($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)->get();

        return view('pages.admin.spk.alternatif_view', [
            'keputusan' => $keputusan,
            'alternatifData' => $alternatif,
            'pageTitle' => 'Manajemen Data Alternatif'
        ]);
    }

    /**
     * Menampilkan form untuk menambah Alternatif baru.
     */
    public function create($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        
        return view('pages.admin.spk.alternatif.create', [
            'keputusan' => $keputusan,
            'pageTitle' => 'Tambah Alternatif Baru'
        ]);
    }
    
    /**
     * Menyimpan Alternatif baru ke database.
     */
    public function store(Request $request, $idKeputusan)
    {
        $validated = $request->validate([
            'nama_alternatif' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $alternatif = alternatif::create([
            'id_keputusan' => $idKeputusan,
            'nama_alternatif' => $validated['nama_alternatif'],
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('admin.spk.manage.alternatif', $idKeputusan)
                         ->with('success', 'Alternatif "' . $alternatif->nama_alternatif . '" berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Alternatif.
     */
    public function edit($idKeputusan, $idAlternatif)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)->where('id_alternatif', $idAlternatif)->firstOrFail();
        
        return view('pages.admin.spk.alternatif.edit', [
            'keputusan' => $keputusan,
            'alternatif' => $alternatif,
            'pageTitle' => 'Edit Alternatif'
        ]);
    }

    /**
     * Memperbarui Alternatif yang sudah ada.
     */
    public function update(Request $request, $idKeputusan, $idAlternatif)
    {
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)->where('id_alternatif', $idAlternatif)->firstOrFail();
        
        $validated = $request->validate([
            'nama_alternatif' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        $alternatif->update($validated);

        return redirect()->route('admin.spk.manage.alternatif', $idKeputusan)
                         ->with('success', 'Alternatif "' . $alternatif->nama_alternatif . '" berhasil diperbarui.');
    }
    
    /**
     * Menghapus Alternatif dan data terkait (penilaian, hasil akhir).
     */
    public function destroy($idKeputusan, $idAlternatif)
    {
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)
                                ->where('id_alternatif', $idAlternatif)
                                ->firstOrFail();

        // Hapus semua data penilaian dan hasil terkait alternatif ini
        penilaian::where('id_alternatif', $idAlternatif)->delete();
        hasilakhir::where('id_alternatif', $idAlternatif)->delete(); 
        
        $nama = $alternatif->nama_alternatif;
        $alternatif->delete();

        return redirect()->route('admin.spk.manage.alternatif', $idKeputusan)
                         ->with('success', 'Alternatif "' . $nama . '" berhasil dihapus.');
    }
}