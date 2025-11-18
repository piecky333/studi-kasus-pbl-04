<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan;
use App\Models\kriteria;
use App\Models\penilaian;
use App\Models\subkriteria;

/**
 * Mengelola semua operasi CRUD untuk Kriteria dalam sebuah Keputusan SPK.
 * Selalu terikat pada parameter {idKeputusan}.
 */
class KriteriaController extends Controller
{
    /**
     * Menampilkan daftar Kriteria dan Sub Kriteria (View: pages.admin.spk.kriteria_view).
     */
    public function index($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)
                            ->with('subKriteria') 
                            ->get();

        return view('pages.admin.spk.kriteria_view', [
            'keputusan' => $keputusan,
            'kriteriaData' => $kriteria,
            'pageTitle' => 'Manajemen Kriteria & Sub Kriteria'
        ]);
    }

    /**
     * Menampilkan form untuk menambah Kriteria baru.
     */
    public function create($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        
        return view('pages.admin.spk.kriteria_create', [
            'keputusan' => $keputusan,
            'pageTitle' => 'Tambah Kriteria Baru'
        ]);
    }

    /**
     * Menyimpan Kriteria baru ke database.
     */
    public function store(Request $request, $idKeputusan)
    {
        $validated = $request->validate([
            // unique per id_keputusan
            'kode_kriteria' => 'required|string|max:10|unique:kriteria,kode_kriteria,NULL,id_kriteria,id_keputusan,'.$idKeputusan, 
            'nama_kriteria' => 'required|string|max:255',
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            'bobot_kriteria' => 'required|numeric|between:0,1',
        ]);

        kriteria::create([
            'id_keputusan' => $idKeputusan,
            'kode_kriteria' => $validated['kode_kriteria'],
            'nama_kriteria' => $validated['nama_kriteria'],
            'jenis_kriteria' => $validated['jenis_kriteria'],
            'bobot_kriteria' => $validated['bobot_kriteria'],
        ]);

        return redirect()->route('admin.spk.manage.kriteria', $idKeputusan)
                         ->with('success', 'Kriteria "' . $validated['nama_kriteria'] . '" berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan form edit Kriteria.
     */
    public function edit($idKeputusan, $idKriteria)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
        
        return view('pages.admin.spk.kriteria_edit', [
            'keputusan' => $keputusan,
            'kriteria' => $kriteria,
            'pageTitle' => 'Edit Kriteria'
        ]);
    }

    /**
     * Memperbarui Kriteria yang sudah ada.
     */
    public function update(Request $request, $idKeputusan, $idKriteria)
    {
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
        
        $validated = $request->validate([
            // unique harus mengecualikan ID kriteria yang sedang diupdate
            'kode_kriteria' => 'required|string|max:10|unique:kriteria,kode_kriteria,'.$idKriteria.',id_kriteria,id_keputusan,'.$idKeputusan,
            'nama_kriteria' => 'required|string|max:255',
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            'bobot_kriteria' => 'required|numeric|between:0,1',
        ]);
        
        $kriteria->update($validated);

        return redirect()->route('admin.spk.manage.kriteria', $idKeputusan)
                         ->with('success', 'Kriteria "' . $kriteria->nama_kriteria . '" berhasil diperbarui.');
    }

    /**
     * Menghapus Kriteria dan data terkait (penilaian, subkriteria).
     */
    public function destroy($idKeputusan, $idKriteria)
    {
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)
                            ->where('id_kriteria', $idKriteria)
                            ->firstOrFail();

        // Hapus data penilaian dan subkriteria terkait
        penilaian::where('id_kriteria', $idKriteria)->delete();
        subkriteria::where('id_kriteria', $idKriteria)->delete(); 
        
        $nama = $kriteria->nama_kriteria;
        $kriteria->delete();

        return redirect()->route('admin.spk.manage.kriteria', $idKeputusan)
                         ->with('success', 'Kriteria "' . $nama . '" berhasil dihapus.');
    }
}