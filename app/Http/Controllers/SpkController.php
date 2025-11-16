<?php

namespace App\Http\Controllers;

use App\Services\SawService;
use App\Services\SpkDataService;
use Illuminate\Http\Request; // Asumsi menggunakan framework yang menyediakan Request

/**
 * Controller ini menangani permintaan untuk menjalankan perhitungan
 * Sistem Pendukung Keputusan (SPK) menggunakan metode SAW dan
 * mengembalikan hasil perangkingan mahasiswa.
 */
class SpkController extends Controller
{
    protected SpkDataService $spkDataService;
    protected SawService $sawService;

    /**
     * Konstruktor untuk menginjeksi layanan yang diperlukan.
     */
    public function __construct(SpkDataService $SpkDataService, SawService $sawService)
    {
        // Dalam framework, container akan otomatis menyediakan instance ini
        $this->spkDataService = $SpkDataService;
        $this->sawService = $sawService;
    }

    /**
     * Menjalankan proses perhitungan SAW dan menampilkan hasil perangkingan.
     * * @param Request $request Opsional, untuk filter atau parameter tambahan
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRanking(Request $request)
    {
        try {
            // 1. Ambil data mentah (matriks keputusan Xij)
            // Cek jika ada filter (misalnya, berdasarkan tahun akademik)
            // Catatan: Jika ada filter, logikanya harus diimplementasikan di SpkDataService
            $rawData = $this->spkDataService->getSpkRawData();
            
            if (empty($rawData)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada data mahasiswa yang valid untuk diproses.',
                    'data' => [],
                ], 200);
            }

            // 2. Hitung perangkingan menggunakan metode SAW
            $rankedData = $this->sawService->calculateRanking($rawData);

            // 3. Kembalikan hasil perangkingan
            return response()->json([
                'success' => true,
                'message' => 'Perangkingan SAW berhasil dihitung dan diurutkan.',
                'total_mahasiswa' => count($rawData),
                'data' => $rankedData,
            ], 200);

        } catch (\Exception $e) {
            // Log error
            \Log::error('SAW Ranking Error: ' . $e->getMessage(), ['exception' => $e]);

            // Kembalikan respons error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghitung perangkingan: ' . $e->getMessage(),
            ], 500);
        }
    }
}