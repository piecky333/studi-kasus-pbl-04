<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan;
use App\Models\alternatif;
use App\Models\kriteria;
use App\Models\penilaian;

/**
 * Mengelola semua operasi CRUD untuk Alternatif (Mahasiswa) dalam sebuah Keputusan.
 */
class AlternatifController extends Controller
{
    /**
     * Menampilkan daftar Alternatif.
     */
    public function index($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $alternatifData = alternatif::where('id_keputusan', $idKeputusan)->get();

        return view('pages.admin.spk.alternatif.index', [
            'keputusan' => $keputusan,
            'alternatifData' => $alternatifData,
            'pageTitle' => 'Manajemen Alternatif'
        ]);
    }

    /**
     * Menampilkan form untuk menambah Alternatif baru.
     */
    public function create($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        
        //menampilkan form create alternatif
        return view('pages.admin.spk.alternatif.create', [
            'keputusan' => $keputusan,
            'pageTitle' => 'Tambah Alternatif Baru'
        ]);
    }

    /**
     * Menyimpan Alternatif baru ke database dan menginisiasi Penilaian.
     */
    public function store(Request $request, $idKeputusan)
    {
        $validated = $request->validate([
            // Asumsi: id_mahasiswa adalah field opsional atau diisi dari select box
            'id_mahasiswa' => 'nullable|integer', 
            'nama_alternatif' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // 1. Buat Alternatif baru
        $alternatifBaru = alternatif::create([
            'id_keputusan' => $idKeputusan,
            'id_mahasiswa' => $validated['id_mahasiswa'] ?? null,
            'nama_alternatif' => $validated['nama_alternatif'],
            'keterangan' => $validated['keterangan'],
        ]);
        
        // 2. LOGIKA KRUSIAL: Inisiasi Penilaian untuk SEMUA Kriteria
        $kriteriaList = kriteria::where('id_keputusan', $idKeputusan)->get();
        
        foreach ($kriteriaList as $kriteria) {
            // Membuat entri Penilaian default (nilai 0)
            penilaian::create([
                'id_alternatif' => $alternatifBaru->id_alternatif,
                'id_kriteria' => $kriteria->id_kriteria,
                'nilai' => 0, // Nilai default 0, siap diisi di Matriks Penilaian
            ]);
        }

        return redirect()->route('alternatif.index', $idKeputusan)
                         ->with('success', 'Alternatif "' . $alternatifBaru->nama_alternatif . '" berhasil ditambahkan dan penilaian diinisiasi.');
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
            'id_mahasiswa' => 'nullable|integer',
            'nama_alternatif' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        $alternatif->update($validated);

        return redirect()->route('alternatif.index', $idKeputusan)
                         ->with('success', 'Alternatif "' . $alternatif->nama_alternatif . '" berhasil diperbarui.');
    }

    /**
     * Menghapus Alternatif dan data penilaian terkait.
     */
    public function destroy($idKeputusan, $idAlternatif)
    {
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)
                            ->where('id_alternatif', $idAlternatif)
                            ->firstOrFail();

        // LOGIKA KRUSIAL: Hapus data penilaian terkait dulu
        penilaian::where('id_alternatif', $idAlternatif)->delete();
        
        $nama = $alternatif->nama_alternatif;
        $alternatif->delete();

        return redirect()->route('alternatif.index', $idKeputusan)
                         ->with('success', 'Alternatif "' . $nama . '" berhasil dihapus beserta seluruh penilaiannya.');
    }
}