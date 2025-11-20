<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan;
use App\Models\kriteria;
use App\Models\penilaian;
use App\Models\subkriteria;
use App\Models\alternatif; // Ditambahkan: Untuk menginisiasi penilaian alternatif baru

/**
 * Mengelola semua operasi CRUD untuk Kriteria dalam sebuah Keputusan SPK.
 * Selalu terikat pada parameter {idKeputusan}.
 */
class KriteriaController extends Controller
{
    /**
     * Menampilkan daftar Kriteria dan Sub Kriteria.
     */
    public function index($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)
                            ->with('subKriteria') 
                            ->get();

        return view('pages.admin.spk.kriteria.index', [
            'keputusan' => $keputusan,
            'kriteriaData' => $kriteria,
            'pageTitle' => 'Manajemen Kriteria'
        ]);
    }

    /**
     * Menampilkan form untuk menambah Kriteria baru.
     */
    public function create($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        
        // Koreksi: Mengubah 'reate' menjadi 'create'
        return view('pages.admin.spk.kriteria.create', [ 
            'keputusan' => $keputusan,
            'pageTitle' => 'Tambah Kriteria Baru'
        ]);
    }

    /**
     * Menyimpan Kriteria baru ke database dan menginisiasi penilaian untuk alternatif yang sudah ada.
     */
    public function store(Request $request, $idKeputusan)
    {
        $validated = $request->validate([
            // unique per id_keputusan
            'kode_kriteria' => 'required|string|max:10|unique:kriteria,kode_kriteria,NULL,id_kriteria,id_keputusan,'.$idKeputusan, 
            'nama_kriteria' => 'required|string|max:255',
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            'bobot_kriteria' => 'required|numeric|between:0,1',
            // Tambahkan validasi untuk cara_penilaian (jika ada inputnya)
            'cara_penilaian' => 'nullable|string', 
        ]);

        // 1. Buat Kriteria baru
        $kriteriaBaru = kriteria::create([
            'id_keputusan' => $idKeputusan,
            'kode_kriteria' => $validated['kode_kriteria'],
            'nama_kriteria' => $validated['nama_kriteria'],
            'jenis_kriteria' => $validated['jenis_kriteria'],
            'bobot_kriteria' => $validated['bobot_kriteria'],
            // Jika Anda tidak menggunakan input form untuk cara_penilaian, biarkan default di DB
            'cara_penilaian' => $validated['cara_penilaian'] ?? 'Input Langsung', 
        ]);
        
        // 2. Inisiasi Penilaian: Tambahkan entri Penilaian dengan nilai default (misal 0) 
        //    untuk SEMUA alternatif yang sudah ada pada keputusan ini.
        $alternatifList = alternatif::where('id_keputusan', $idKeputusan)->get();
        
        foreach ($alternatifList as $alternatif) {
            penilaian::create([
                'id_alternatif' => $alternatif->id_alternatif,
                'id_kriteria' => $kriteriaBaru->id_kriteria,
                'nilai' => 0, // Nilai default 0 atau sesuaikan dengan kebutuhan Anda
            ]);
        }

        return redirect()->route('admin.spk.kriteria.index', $idKeputusan) // Mengubah route
                         ->with('success', 'Kriteria "' . $validated['nama_kriteria'] . '" berhasil ditambahkan, dan penilaian alternatif sudah diinisiasi.');
    }
    
    /**
     * Menampilkan form edit Kriteria.
     */
    public function edit($idKeputusan, $idKriteria)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
        
        return view('pages.admin.spk.kriteria.edit', [
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
            'cara_penilaian' => 'nullable|string', 
        ]);
        
        // Tambahkan 'cara_penilaian' ke data yang akan diupdate jika ada di request
        $dataToUpdate = $validated;
        if (!isset($dataToUpdate['cara_penilaian'])) {
             // Jika tidak ada di request, gunakan nilai yang sudah ada atau default
             $dataToUpdate['cara_penilaian'] = $kriteria->cara_penilaian ?? 'Input Langsung'; 
        }
        
        $kriteria->update($dataToUpdate);

        return redirect()->route('admin.spk.kriteria.index', $idKeputusan) // Mengubah route
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

        return redirect()->route('admin.spk.kriteria.index', $idKeputusan) // Mengubah route
                         ->with('success', 'Kriteria "' . $nama . '" berhasil dihapus.');
    }
    
    /**
     * Menampilkan daftar Sub Kriteria (untuk Kriteria tertentu).
     * Ini digunakan untuk tombol 'Atur Sub' di view.
     */
    public function subkriteriaIndex($idKeputusan, $idKriteria)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
        
        // Ambil data subkriteria terkait
        $subKriteriaData = subkriteria::where('id_kriteria', $idKriteria)->get();

        return view('pages.admin.spk.subkriteria.index', [
            'keputusan' => $keputusan,
            'kriteria' => $kriteria,
            'subKriteriaData' => $subKriteriaData,
            'pageTitle' => 'Manajemen Sub Kriteria: ' . $kriteria->kode_kriteria,
        ]);
    }
}