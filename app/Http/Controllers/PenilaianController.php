<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan;
use App\Models\kriteria;
use App\Models\alternatif;
use App\Models\penilaian;
use Illuminate\Support\Facades\DB; // Untuk transaksi database

/**
 * Mengelola tampilan dan Mass Update Matriks Penilaian (Xij)
 * dalam sebuah Keputusan SPK.
 * Terikat pada parameter {idKeputusan}.
 */
class PenilaianController extends Controller
{
    /**
     * Menampilkan Matriks Penilaian saat ini (View: pages.admin.spk.penilaian_view).
     * (Menggantikan showPenilaian di SpkManagementController)
     */
    public function index($idKeputusan)
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

    /**
     * Menampilkan formulir untuk MENGEDIT SELURUH MATRIKS PENILAIAN (Mass Edit).
     */
    public function editMatriks($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->get();
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)->get();

        // Mengambil semua data Penilaian yang dikelompokkan (siap untuk pre-fill form)
        $penilaian = penilaian::whereHas('alternatif', function($query) use ($idKeputusan) {
            $query->where('id_keputusan', $idKeputusan);
        })->get()->keyBy(function ($item) {
            // Menggunakan key kombinasi untuk memudahkan pencarian nilai di view (AlternatifID_KriteriaID)
            return $item->id_alternatif . '_' . $item->id_kriteria;
        });

        return view('pages.admin.spk.penilaian_edit_matriks', [
            'keputusan' => $keputusan,
            'kriteria' => $kriteria,
            'alternatif' => $alternatif,
            'penilaianData' => $penilaian, // Data Penilaian yang sudah ada
            'pageTitle' => 'Edit Matriks Penilaian'
        ]);
    }

    /**
     * Menyimpan atau memperbarui SELURUH MATRIKS PENILAIAN (Mass Update).
     */
    public function updateMatriks(Request $request, $idKeputusan)
    {
        $alternatif = alternatif::where('id_keputusan', $idKeputusan)->pluck('id_alternatif')->toArray();
        $kriteria = kriteria::where('id_keputusan', $idKeputusan)->pluck('id_kriteria')->toArray();
        $inputPenilaian = $request->input('penilaian'); // Input adalah array [id_alternatif => [id_kriteria => nilai]]

        if (empty($inputPenilaian)) {
            return back()->with('error', 'Tidak ada data penilaian yang dikirimkan.');
        }

        DB::beginTransaction();
        try {
            foreach ($inputPenilaian as $idAlt => $kriteriaNilai) {
                // Pastikan idAlt adalah bagian dari alternatif keputusan ini (opsional tapi aman)
                if (!in_array($idAlt, $alternatif)) continue; 

                foreach ($kriteriaNilai as $idKrit => $nilai) {
                    // Pastikan idKrit adalah bagian dari kriteria keputusan ini (opsional tapi aman)
                    if (!in_array($idKrit, $kriteria)) continue;

                    // Validasi nilai spesifik (contoh sederhana)
                    if (!is_numeric($nilai) || $nilai < 0) {
                        DB::rollBack();
                        return back()->with('error', 'Nilai penilaian harus berupa angka positif.');
                    }
                    
                    // Lakukan Mass Update atau Insert (upsert)
                    penilaian::updateOrCreate(
                        [
                            'id_alternatif' => $idAlt,
                            'id_kriteria' => $idKrit,
                        ],
                        [
                            'nilai' => (float) $nilai, // Simpan sebagai float
                        ]
                    );
                }
            }

            DB::commit();
            return redirect()->route('admin.spk.manage.penilaian', $idKeputusan)
                             ->with('success', 'Matriks Penilaian berhasil diperbarui dan disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            return back()->with('error', 'Gagal menyimpan matriks penilaian: ' . $e->getMessage());
        }
    }
}