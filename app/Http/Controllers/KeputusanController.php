<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan; 
use App\Models\kriteria;
use App\Models\alternatif;
use App\Models\penilaian;
use App\Models\hasilakhir;
use App\Models\subkriteria; 
use Illuminate\Support\Facades\DB; // Diperlukan untuk memastikan operasi delete bertingkat

/**
 * Controller ini bertanggung jawab hanya untuk mengelola CRUD dari entitas 
 * Keputusan SPK itu sendiri. Menggantikan fungsi CRUD Keputusan dari 
 * SpkManagementController yang lama.
 */
class KeputusanController extends Controller
{
    // =================================================================
    // >>> KEPUTUSAN SPK (CRUD) <<<
    // =================================================================
    
    /**
     * Menampilkan daftar semua Keputusan SPK yang tersedia (index).
     */
    public function index()
    {
        $keputusanList = spkkeputusan::all();
        return view('pages.admin.spk.keputusan.index', [ 
            'keputusanList' => $keputusanList,
            'pageTitle' => 'Daftar Keputusan SPK'
        ]);
    }
    
    /**
     * Menampilkan form untuk membuat Keputusan SPK baru.
     */
    public function create()
    {
        return view('pages.admin.spk.keputusan.create', [
            'pageTitle' => 'Buat Keputusan SPK Baru'
        ]);
    }

    /**
     * Menyimpan Keputusan SPK baru ke database.
     */
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

        return redirect()->route('admin.spk.keputusan.index')
                         ->with('success', 'Keputusan SPK "' . $keputusan->nama_keputusan . '" berhasil dibuat!');
    }

    /**
     * Menampilkan form edit Keputusan.
     */
    public function edit($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        return view('pages.admin.spk.keputusan.edit', [
            'keputusan' => $keputusan,
            'pageTitle' => 'Edit Keputusan SPK'
        ]);
    }

    /**
     * Memperbarui Keputusan yang sudah ada.
     */
    public function update(Request $request, $idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $validated = $request->validate([
            'nama_keputusan' => 'required|string|max:255',
            'metode_yang_digunakan' => 'required|string|max:50',
        ]);

        $keputusan->update($validated);

        return redirect()->route('admin.spk.keputusan.index')
                         ->with('success', 'Keputusan SPK "' . $keputusan->nama_keputusan . '" berhasil diperbarui.');
    }

    /**
     * Menghapus Keputusan SPK dan semua data terkait.
     */
    public function destroy($idKeputusan)
    {
        // Menggunakan Transaction untuk memastikan semua penghapusan bertingkat berhasil
        DB::beginTransaction();
        try {
            $keputusan = spkkeputusan::findOrFail($idKeputusan);
            $nama = $keputusan->nama_keputusan;

            // 1. Ambil ID terkait
            $kriteriaIds = kriteria::where('id_keputusan', $idKeputusan)->pluck('id_kriteria');
            $alternatifIds = alternatif::where('id_keputusan', $idKeputusan)->pluck('id_alternatif');

            // 2. Hapus data anak (Penilaian, SubKriteria, HasilAkhir)
            penilaian::whereIn('id_kriteria', $kriteriaIds)->delete();
            subkriteria::whereIn('id_kriteria', $kriteriaIds)->delete(); 
            hasilakhir::whereIn('id_alternatif', $alternatifIds)->delete();
            
            // 3. Hapus data orang tua (Kriteria dan Alternatif)
            kriteria::where('id_keputusan', $idKeputusan)->delete();
            alternatif::where('id_keputusan', $idKeputusan)->delete();

            // 4. Hapus Keputusan utama
            $keputusan->delete();
            
            DB::commit();

            return redirect()->route('admin.spk.keputusan.index')
                             ->with('success', 'Keputusan SPK "' . $nama . '" dan semua data terkait berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage()); // Opsional: Logging error
            return redirect()->route('admin.spk.keputusan.index')
                             ->with('error', 'Gagal menghapus keputusan SPK: ' . $e->getMessage());
        }
    }
}