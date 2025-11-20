<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Ditambahkan untuk validasi unik jika diperlukan
use App\Models\spkkeputusan;
use App\Models\kriteria;
use App\Models\subkriteria;

/**
 * Mengelola semua operasi CRUD untuk Sub Kriteria, terikat pada Kriteria tertentu.
 */
class SubkriteriaController extends Controller
{
    /**
     * Menampilkan daftar Sub Kriteria.
     */
    public function index($idKeputusan, $idKriteria)
    {
        // 1. Pastikan Keputusan ada
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        
        // 2. Pastikan Kriteria ada dan milik Keputusan ini
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)
                             ->where('id_kriteria', $idKriteria)
                             ->firstOrFail();
        
        // 3. Ambil Sub Kriteria
        $subKriteriaData = subkriteria::where('id_kriteria', $idKriteria)->get();

        return view('pages.admin.spk.sub_kriteria.index', [
            'keputusan' => $keputusan,
            'kriteria' => $kriteria,
            'subKriteriaData' => $subKriteriaData,
            'pageTitle' => 'Manajemen Sub Kriteria: ' . $kriteria->nama_kriteria
        ]);
    }

    /**
     * Menampilkan form untuk menambah Sub Kriteria baru.
     */
    public function create($idKeputusan, $idKriteria)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
        
        return view('pages.admin.spk.sub_kriteria.create', [
            'keputusan' => $keputusan,
            'kriteria' => $kriteria,
            'pageTitle' => 'Tambah Sub Kriteria Baru'
        ]);
    }

    /**
     * Menyimpan Sub Kriteria baru ke database.
     */
    public function store(Request $request, $idKeputusan, $idKriteria)
    {
        // Pastikan Kriteria ada, meskipun tidak digunakan secara langsung
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
        
        $validated = $request->validate([
            'nama_subkriteria' => 'required|string|max:255',
            'nilai_konversi' => 'required|numeric|min:0', 
        ]);

        subkriteria::create([
            'id_kriteria' => $idKriteria,
            'nama_subkriteria' => $validated['nama_subkriteria'],
            'nilai_konversi' => $validated['nilai_konversi'],
        ]);

        return redirect()->route('admin.spk.kriteria.subkriteria.index', [$idKeputusan, $idKriteria])
                         ->with('success', 'Sub Kriteria "' . $validated['nama_subkriteria'] . '" berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Sub Kriteria.
     */
    public function edit($idKeputusan, $idKriteria, $idSubKriteria)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
    
        $subkriteria = subkriteria::where('id_kriteria', $idKriteria)
                                  ->findOrFail($idSubKriteria);
        
        return view('pages.admin.spk.sub_kriteria.edit', [
            'keputusan' => $keputusan,
            'kriteria' => $kriteria,
            'subkriteria' => $subkriteria,
            'pageTitle' => 'Edit Sub Kriteria'
        ]);
    }

    /**
     * Memperbarui Sub Kriteria yang sudah ada.
     */
    public function update(Request $request, $idKeputusan, $idKriteria, $idSubKriteria)
    {
        $subkriteria = subkriteria::where('id_kriteria', $idKriteria)
                                  ->findOrFail($idSubKriteria);
        
        $validated = $request->validate([
            'nama_subkriteria' => 'required|string|max:255',
            'nilai_konversi' => 'required|numeric|min:0',
        ]);
        
        
        $subkriteria->update($validated);

        //kembali ke halaman index subkriteria
        return redirect()->route('admin.spk.kriteria.subkriteria.index', [$idKeputusan, $idKriteria])
                         ->with('success', 'Sub Kriteria "' . $subkriteria->nama_subkriteria . '" berhasil diperbarui.');
    }

    /**
     * Menghapus Sub Kriteria.
     */
    public function destroy($idKeputusan, $idKriteria, $idSubKriteria)
    {
        //sub kriteria memilik kriteria 
        $subkriteria = subkriteria::where('id_kriteria', $idKriteria)
                                  ->findOrFail($idSubKriteria);

        $nama = $subkriteria->nama_subkriteria;
        
        //menghapus subkriteria
        $subkriteria->delete();

        return redirect()->route('admin.spk.kriteria.subkriteria.index', [$idKeputusan, $idKriteria])
                         ->with('success', 'Sub Kriteria "' . $nama . '" berhasil dihapus.');
    }
}