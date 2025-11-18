<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan;
use App\Models\kriteria;
use App\Models\subkriteria;
use App\Models\penilaian; // Diperlukan untuk cascading delete

/**
 * Mengelola semua operasi CRUD untuk Sub Kriteria.
 * Controller ini terikat pada parameter {idKeputusan} dan {idKriteria}.
 */
class SubKriteriaController extends Controller
{
    /**
     * Menampilkan daftar Sub Kriteria untuk Kriteria spesifik (View: pages.admin.spk.sub_kriteria_view).
     */
    public function index($idKeputusan, $idKriteria)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::findOrFail($idKriteria);

        // Ambil semua sub kriteria yang terkait
        $subKriteriaData = subkriteria::where('id_kriteria', $idKriteria)
                                      ->orderBy('nilai', 'desc') // Biasanya diurutkan berdasarkan nilai
                                      ->get();

        return view('pages.admin.spk.sub_kriteria.index', [
            'keputusan' => $keputusan,
            'kriteria' => $kriteria,
            'subKriteriaData' => $subKriteriaData,
            'pageTitle' => 'Manajemen Sub Kriteria'
        ]);
    }

    /**
     * Menampilkan form untuk menambah Sub Kriteria baru.
     */
    public function create($idKeputusan, $idKriteria)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::findOrFail($idKriteria);

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
        $validated = $request->validate([
            'nama_subkriteria' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0', // Pastikan nilai adalah numerik
        ]);

        // Catatan: Kriteria harus sudah diverifikasi ada melalui findOrFail di index/create

        subkriteria::create([
            'id_kriteria' => $idKriteria,
            'nama_subkriteria' => $validated['nama_subkriteria'],
            'nilai' => $validated['nilai'],
        ]);

        return redirect()->route('admin.spk.manage.subkriteria', ['idKeputusan' => $idKeputusan, 'idKriteria' => $idKriteria])
                         ->with('success', 'Sub Kriteria "' . $validated['nama_subkriteria'] . '" berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan form edit Sub Kriteria.
     */
    public function edit($idKeputusan, $idKriteria, $idSubKriteria)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::findOrFail($idKriteria);
        $subkriteria = subkriteria::where('id_kriteria', $idKriteria)->findOrFail($idSubKriteria);

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
        $subkriteria = subkriteria::where('id_kriteria', $idKriteria)->findOrFail($idSubKriteria);
        
        $validated = $request->validate([
            'nama_subkriteria' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0',
        ]);
        
        $subkriteria->update($validated);

        return redirect()->route('admin.spk.manage.subkriteria', ['idKeputusan' => $idKeputusan, 'idKriteria' => $idKriteria])
                         ->with('success', 'Sub Kriteria berhasil diperbarui.');
    }

    /**
     * Menghapus Sub Kriteria.
     */
    public function destroy($idKeputusan, $idKriteria, $idSubKriteria)
    {
        $subkriteria = subkriteria::where('id_kriteria', $idKriteria)->findOrFail($idSubKriteria);

        // Catatan: Jika Anda ingin menghapus semua Penilaian yang merujuk ke Nilai skala ini, 
        // Anda harus menambahkan logika penghapusan Penilaian yang lebih kompleks di sini.

        $nama = $subkriteria->nama_subkriteria;
        $subkriteria->delete();

        return redirect()->route('admin.spk.manage.subkriteria', ['idKeputusan' => $idKeputusan, 'idKriteria' => $idKriteria])
                         ->with('success', 'Sub Kriteria "' . $nama . '" berhasil dihapus.');
    }
}