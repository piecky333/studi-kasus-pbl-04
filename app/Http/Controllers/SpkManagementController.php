<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan; 
use App\Models\kriteria;
use App\Models\alternatif;
use App\Models\penilaian;
use App\Models\hasilakhir;
use App\Models\subkriteria; // Diperlukan untuk penghapusan bertingkat (cascading delete)

class SpkManagementController extends Controller
{
    // =================================================================
    // >>> KEPUTUSAN SPK (CRUD) <<<
    // =================================================================
    
    public function index()
    {
        $keputusanList = spkkeputusan::all();
        return view('pages.admin.spk.daftar_keputusan.index', [ 
            'keputusanList' => $keputusanList,
            'pageTitle' => 'Daftar Keputusan SPK'
        ]);
    }
    
    public function create()
    {
        return view('pages.admin.spk.daftar_keputusan.create', [
            'pageTitle' => 'Buat Keputusan SPK Baru'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_keputusan' => 'required|string|max:255',
            'metode_yang_digunakan' => 'required|string|max:50',
        ]);

        $keputusan = spkkeputusan::create([
            'nama_keputusan' => $validated['nama_keputusan'],
            'metode_yang_digunakan' => $validated['metode_yang_digunakan'],
            'tanggal_dibuat' => now(),
            'status' => 'Draft',
        ]);

        return redirect()->route('admin.spk.index')
                         ->with('success', 'Keputusan SPK "' . $keputusan->nama_keputusan . '" berhasil dibuat!');
    }

    public function edit($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        return view('pages.admin.spk.edit', [
            'keputusan' => $keputusan,
            'pageTitle' => 'Edit Keputusan SPK'
        ]);
    }

    public function update(Request $request, $idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $validated = $request->validate([
            'nama_keputusan' => 'required|string|max:255',
            'metode_yang_digunakan' => 'required|string|max:50',
        ]);

        $keputusan->update($validated);

        return redirect()->route('admin.spk.index')
                         ->with('success', 'Keputusan SPK "' . $keputusan->nama_keputusan . '" berhasil diperbarui.');
    }

    public function destroy($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $nama = $keputusan->nama_keputusan;

        // Hapus semua data terkait untuk menjaga integritas database
        $kriteriaIds = kriteria::where('id_keputusan', $idKeputusan)->pluck('id_kriteria');
        $alternatifIds = alternatif::where('id_keputusan', $idKeputusan)->pluck('id_alternatif');

        penilaian::whereIn('id_kriteria', $kriteriaIds)->delete();
        subkriteria::whereIn('id_kriteria', $kriteriaIds)->delete(); 
        hasilakhir::whereIn('id_alternatif', $alternatifIds)->delete();

        kriteria::where('id_keputusan', $idKeputusan)->delete();
        alternatif::where('id_keputusan', $idKeputusan)->delete();

        $keputusan->delete();

        return redirect()->route('admin.spk.index')
                         ->with('success', 'Keputusan SPK "' . $nama . '" dan semua data terkait berhasil dihapus.');
    }

    // =================================================================
    // >>> KRITERIA (CRUD) <<<
    // =================================================================
    
    public function showKriteria($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)
                            ->with('subKriteria') 
                            ->get();

        return view('pages.admin.spk.kriteria.index', [
            'keputusan' => $keputusan,
            'kriteriaData' => $kriteria,
            'pageTitle' => 'Manajemen Kriteria & Sub Kriteria'
        ]);
    }

    public function createKriteria($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        
        return view('pages.admin.spk.kriteria.create', [
            'keputusan' => $keputusan,
            'pageTitle' => 'Tambah Kriteria Baru'
        ]);
    }

    public function storeKriteria(Request $request, $idKeputusan)
    {
        $validated = $request->validate([
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
    
    public function editKriteria($idKeputusan, $idKriteria)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
        
        return view('pages.admin.spk.kriteria_edit', [
            'keputusan' => $keputusan,
            'kriteria' => $kriteria,
            'pageTitle' => 'Edit Kriteria'
        ]);
    }

    public function updateKriteria(Request $request, $idKeputusan, $idKriteria)
    {
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->where('id_kriteria', $idKriteria)->firstOrFail();
        
        $validated = $request->validate([
            'kode_kriteria' => 'required|string|max:10|unique:kriteria,kode_kriteria,'.$idKriteria.',id_kriteria,id_keputusan,'.$idKeputusan,
            'nama_kriteria' => 'required|string|max:255',
            'jenis_kriteria' => 'required|in:Benefit,Cost',
            'bobot_kriteria' => 'required|numeric|between:0,1',
        ]);
        
        $kriteria->update($validated);

        return redirect()->route('admin.spk.manage.kriteria', $idKeputusan)
                         ->with('success', 'Kriteria "' . $kriteria->nama_kriteria . '" berhasil diperbarui.');
    }

    public function destroyKriteria($idKeputusan, $idKriteria)
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

    // =================================================================
    // >>> ALTERNATIF (CRUD) <<<
    // =================================================================

    public function showAlternatif($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)->get();

        return view('pages.admin.spk.alternatif.index', [
            'keputusan' => $keputusan,
            'alternatifData' => $alternatif,
            'pageTitle' => 'Manajemen Data Alternatif'
        ]);
    }

    public function createAlternatif($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        
        return view('pages.admin.spk.alternatif_create', [
            'keputusan' => $keputusan,
            'pageTitle' => 'Tambah Alternatif Baru'
        ]);
    }
    
    public function storeAlternatif(Request $request, $idKeputusan)
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

    public function editAlternatif($idKeputusan, $idAlternatif)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)->where('id_alternatif', $idAlternatif)->firstOrFail();
        
        return view('pages.admin.spk.alternatif_edit', [
            'keputusan' => $keputusan,
            'alternatif' => $alternatif,
            'pageTitle' => 'Edit Alternatif'
        ]);
    }

    public function updateAlternatif(Request $request, $idKeputusan, $idAlternatif)
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
    
    public function destroyAlternatif($idKeputusan, $idAlternatif)
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


    // =================================================================
    // >>> PENILAIAN, PERHITUNGAN, HASIL AKHIR (VIEW ONLY) <<<
    // =================================================================
    
    public function showPenilaian($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->get();
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)->get();

        // Mengambil semua data Penilaian yang dikelompokkan berdasarkan Alternatif
        $penilaian = penilaian::whereHas('alternatif', function($query) use ($idKeputusan) {
            $query->where('id_keputusan', $idKeputusan);
        })->get()->groupBy('id_alternatif');

        return view('pages.admin.spk.penilaian.index', [ 
            'keputusan' => $keputusan,
            'kriteria' => $kriteria,
            'alternatif' => $alternatif,
            'penilaianMatrix' => $penilaian,
            'pageTitle' => 'Matriks Penilaian (Input Data)'
        ]);
    }

    public function showHasilAkhir($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);

        $hasil = hasilakhir::whereHas('alternatif', function ($query) use ($idKeputusan) {
                             return $query->where('id_keputusan', $idKeputusan);
                           })
                           ->with('alternatif') 
                           ->orderBy('rangking', 'asc')
                           ->get();

        return view('pages.admin.spk.hasil_akhir_view', [ 
            'keputusan' => $keputusan,
            'hasilData' => $hasil,
            'pageTitle' => 'Hasil Akhir Keputusan'
        ]);
    }
}