<?php

namespace App\Http\Controllers\Spk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\spkkeputusan; 
use App\Models\kriteria;
use App\Models\alternatif;
use App\Models\penilaian;
use App\Models\hasilakhir;
use App\Models\subkriteria; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Controller ini bertanggung jawab hanya untuk mengelola CRUD dari entitas 
 * Keputusan SPK itu sendiri (Level 1).
 * Note: Controller ini TIDAK extend KeputusanDetailController karena ini adalah halaman Index utama.
 */
class KeputusanController extends Controller
{
    // =================================================================
    // >>> KEPUTUSAN SPK (CRUD) <<<
    // =================================================================
    
    /**
     * Menampilkan daftar semua Keputusan SPK yang tersedia (index).
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch semua data keputusan dari database
        $keputusanList = spkkeputusan::all();
        
        $keputusanList = spkkeputusan::paginate(10);
        
        return view('pages.admin.spk.keputusan.index', [ 
            'keputusanList' => $keputusanList,
            'pageTitle' => 'Daftar Keputusan SPK'
        ]);
    }
    
    /**
     * Menampilkan form untuk membuat Keputusan SPK baru.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.admin.spk.keputusan.create', [
            'pageTitle' => 'Buat Keputusan SPK Baru'
        ]);
    }

    /**
     * Menyimpan Keputusan SPK baru ke database.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi input yang masuk
        $validated = $request->validate([
            'nama_keputusan' => 'required|string|max:255',
            'metode_yang_digunakan' => 'required|string|max:50',
            // Note: Tambahkan validasi untuk memastikan metode valid (misal: 'in:AHP,SAW,TOPSIS')
        ]);

        // 2. Buat record baru di database
        $keputusan = spkkeputusan::create([
            'nama_keputusan' => $validated['nama_keputusan'],
            'metode_yang_digunakan' => $validated['metode_yang_digunakan'],
            'tanggal_dibuat' => now(),
            'status' => 'Draft', // Status awal selalu Draft
        ]);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.spk.index')
                         ->with('success', 'Keputusan SPK "' . $keputusan->nama_keputusan . '" berhasil dibuat!');
    }

    /**
     * Menampilkan form edit Keputusan.
     * @param int $idKeputusan
     * @return \Illuminate\View\View
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
     * @param Request $request
     * @param int $idKeputusan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $validated = $request->validate([
            'nama_keputusan' => 'required|string|max:255',
            'metode_yang_digunakan' => 'required|string|max:50',
        ]);

        $keputusan->update($validated);

        // agar pengguna langsung bisa melanjutkan pengerjaan SPK.
        return redirect()->route('admin.spk.index')
                         ->with('success', 'Keputusan SPK "' . $keputusan->nama_keputusan . '" berhasil diperbarui.');
    }

    /**
     * Menghapus Keputusan SPK dan semua data terkait (Penghapusan Bertingkat).
     * @param int $idKeputusan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($idKeputusan)
    {
        // Menggunakan Transaction untuk memastikan semua penghapusan bertingkat berhasil (Atomic Operation)
        DB::beginTransaction();
        try {
            $keputusan = spkkeputusan::findOrFail($idKeputusan);
            $nama = $keputusan->nama_keputusan;

            // 1. Ambil ID terkait (Pluck ID hanya jika ada data, untuk menghindari kesalahan query)
            $kriteriaIds = kriteria::where('id_keputusan', $idKeputusan)->pluck('id_kriteria')->toArray();
            $alternatifIds = alternatif::where('id_keputusan', $idKeputusan)->pluck('id_alternatif')->toArray();

            // 2. Hapus data anak paling dalam (hanya jika Kriteria/Alternatif ditemukan)
            // Ini penting untuk model yang memiliki foreign key ke Kriteria atau Alternatif
            if (!empty($kriteriaIds)) {
                // Hapus Perbandingan Kriteria (jika Anda memiliki modelnya)
                // Hapus Penilaian yang terkait dengan Kriteria
                penilaian::whereIn('id_kriteria', $kriteriaIds)->delete();
                // Hapus Subkriteria yang terkait dengan Kriteria
                subkriteria::whereIn('id_kriteria', $kriteriaIds)->delete();
            }
            
            if (!empty($alternatifIds)) {
                // Hapus Penilaian yang terkait dengan Alternatif
                penilaian::whereIn('id_alternatif', $alternatifIds)->delete(); // Penilaian terikat ke Kriteria dan Alternatif
                // Hapus HasilAkhir yang terkait dengan Alternatif
                hasilakhir::whereIn('id_alternatif', $alternatifIds)->delete();
            }
            
            // 3. Hapus data orang tua (Kriteria dan Alternatif)
            kriteria::where('id_keputusan', $idKeputusan)->delete();
            alternatif::where('id_keputusan', $idKeputusan)->delete();

            // 4. Hapus Keputusan utama
            $keputusan->delete();
            
            DB::commit(); // Selesai, simpan perubahan

            return redirect()->route('admin.spk.index')
                             ->with('success', 'Keputusan SPK "' . $nama . '" dan semua data terkait berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua operasi jika ada error
            // Log error untuk debugging yang lebih mudah di environment developer
            Log::error("Gagal menghapus keputusan SPK: " . $e->getMessage(), ['id_keputusan' => $idKeputusan]); 
            
            // Mengembalikan pesan error yang jelas
            return redirect()->route('admin.spk.index')
                             ->with('error', 'Gagal menghapus keputusan SPK: ' . $e->getMessage() . '. Cek log server untuk detail.');
        }
    }
}