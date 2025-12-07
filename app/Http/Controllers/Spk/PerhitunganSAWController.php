<?php

namespace App\Http\Controllers\Spk;

use App\Models\hasilakhir;
use App\Services\SawService;
use Illuminate\Http\Request;
use App\Http\Controllers\Spk\KeputusanDetailController; // PENTING: Import Base Controller SPK

use App\Models\alternatif;
use App\Models\penilaian;

/**
 * Class PerhitunganSAWController
 * 
 * Controller ini bertanggung jawab untuk menangani logika bisnis terkait metode Simple Additive Weighting (SAW).
 * 
 * Tugas utama:
 * 1. Menampilkan hasil akhir perangkingan.
 * 2. Menampilkan detail proses perhitungan (Matriks Keputusan, Normalisasi, dll) untuk transparansi.
 * 3. Memicu proses perhitungan ulang jika ada perubahan data.
 * 
 * @package App\Http\Controllers\Spk
 */
class PerhitunganSAWController extends KeputusanDetailController
{
    protected SawService $sawService;

    /**
     * Constructor.
     * 
     * @param Request $request
     * @param SawService $sawService Service yang menangani logika matematis SAW.
     */
    public function __construct(Request $request, SawService $sawService)
    {
        // Memastikan konteks Keputusan dimuat dengan benar via parent constructor.
        parent::__construct($request);
        $this->sawService = $sawService;
    }

    /**
     * Menampilkan halaman Hasil Akhir (Tab Hasil).
     * 
     * Method ini juga melakukan pengecekan integritas data sebelum menampilkan hasil.
     * Jika data Alternatif atau Penilaian kosong, user akan diarahkan kembali untuk melengkapinya.
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // CEK DATA: Jika tidak ada alternatif atau penilaian, redirect ke halaman alternatif
        $hasAlternatif = alternatif::where('id_keputusan', $this->idKeputusan)->exists();
        
        // Cek apakah ada penilaian yang terkait dengan alternatif di keputusan ini
        $hasPenilaian = penilaian::whereHas('alternatif', function ($query) {
            $query->where('id_keputusan', $this->idKeputusan);
        })->exists();

        if (!$hasAlternatif || !$hasPenilaian) {
            return redirect()->route('admin.spk.alternatif.index', $this->idKeputusan)
                ->with('error', 'Harap tambahkan Alternatif dan Penilaian terlebih dahulu sebelum melihat Hasil Akhir.');
        }

        // 1. Ambil hasil ranking yang sudah tersimpan dari database (Tampilan Final).
        // Menggunakan Eager Loading 'alternatif' untuk efisiensi query.
        // Filter berdasarkan 'whereHas' memastikan hanya hasil milik keputusan ini yang diambil.
        $rankingResults = HasilAkhir::whereHas('alternatif', function ($query) {
            $query->where('id_keputusan', $this->idKeputusan);
        })
            ->with('alternatif') // Memuat relasi Alternatif untuk nama/detail
            ->orderBy('rangking', 'asc')
            ->get();
            
        // 2. Ambil data perhitungan lengkap (jika perlu ditampilkan detail proses)
        $calculationData = null;
        $isReady = true; // Flag untuk mengontrol apakah tombol 'Hitung' boleh ditekan
        
        try {
            // Memuat data proses (Matriks X, Matriks R, Bobot W) untuk ditampilkan di UI.
            // Service akan melempar Exception jika data tidak konsisten (misal: bobot AHP belum dihitung).
            $calculationData = $this->sawService->calculateProcessData($this->idKeputusan);
        } catch (\Exception $e) {
            // Jika ada exception (data tidak lengkap/tidak konsisten AHP)
            $isReady = false;
            // session()->now digunakan agar pesan error hanya muncul saat halaman dimuat
            session()->now('error', $e->getMessage()); 
        }

        return view('pages.admin.spk.hasil.index', [
            'idKeputusan' => $this->idKeputusan,
            'keputusan' => $this->keputusan,
            'rankingResults' => $rankingResults,
            'calculationData' => $calculationData,
            'isReady' => $isReady 
        ]);
    }

    /**
     * Memicu proses perhitungan SAW dan menyimpan hasilnya ke database.
     * 
     * Method ini menggunakan Post-Redirect-Get (PRG) pattern.
     * Setelah perhitungan selesai, user akan di-redirect kembali ke halaman index
     * untuk melihat hasilnya.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runCalculation()
    {
        // NOTE: Request object tidak perlu di-type hint jika tidak digunakan,
        // tetapi dipertahankan jika Anda ingin menambahkan validasi isMethod('POST') 
        // seperti yang disarankan di langkah sebelumnya.

        try {
            // Delegasikan logika perhitungan yang kompleks ke Service.
            // Ini menjaga Controller tetap ramping (Thin Controller).
            $this->sawService->executeAndSaveResult($this->idKeputusan);
            
            // Perbarui status keputusan (Status: Draft -> Selesai/Aktif)
            $this->keputusan->status = 'Selesai';
            $this->keputusan->save();

            $message = 'Perhitungan SAW berhasil dijalankan dan hasil ranking telah disimpan! Keputusan sekarang: ' . $this->keputusan->status;
            session()->flash('success', $message);

        } catch (\Exception $e) {
            // Tangkap exception jika data tidak valid/tidak lengkap
            session()->flash('error', "Gagal melakukan perhitungan SAW: " . $e->getMessage());
        }

        // Menggunakan PRG Pattern: Redirect kembali ke halaman Hasil Akhir (GET route)
        return redirect()->route('admin.spk.hasil.index', $this->idKeputusan);
    }
}