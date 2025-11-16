<?php

namespace App\Services;

use App\Models\spkkeputusan; // Pastikan menggunakan alias jika nama file Model berbeda
use App\Models\alternatif; 
use App\Models\kriteria; 
use App\Models\penilaian; // Tambahkan ini jika dibutuhkan untuk operasi lain

/**
 * Layanan untuk mengumpulkan data mentah (matriks keputusan Xij)
 * dari database berdasarkan Keputusan SPK yang spesifik.
 */
class SpkDataService
{
    // jika semua data penilaian sudah disimpan di tabel 'penilaian') ...

    protected $tingkatScores = [
        'Internasional' => 5,
        'Nasional' => 4,
        'Provinsi' => 3,
        'Kota/Kabupaten' => 2,
        'Kampus' => 1,
    ];

    protected $peringkatScores = [
        'Juara 1' => 3,
        'Juara 2' => 2,
        'Juara 3' => 1,
        'Harapan' => 0.5,
    ];

    /**
     * Mengambil data mentah SPK untuk proses perhitungan.
     *
     * @param int $idKeputusan ID dari SpkKeputusan yang akan diproses.
     * @return array Data yang siap untuk dinormalisasi (SAW).
     */
    public function getSpkRawData(int $idKeputusan): array
    {
        // 1. Ambil Kriteria yang terkait dengan keputusan ini
        $kriteriaList = kriteria::where('id_keputusan', $idKeputusan)
            ->get(['id_kriteria', 'kode_kriteria', 'jenis_kriteria', 'nama_kriteria']);
        
        $kriteriaMap = $kriteriaList->pluck('kode_kriteria', 'id_kriteria')->toArray();
        $criteriaKeys = $kriteriaList->pluck('kode_kriteria')->toArray();

        // 2. Ambil semua Alternatif (Mahasiswa) dan Penilaian mereka dalam satu query
        $alternatives = alternatif::with(['penilaian' => function ($query) use ($kriteriaList) {
                // Hanya ambil penilaian yang termasuk kriteria dalam keputusan ini
                $query->whereIn('id_kriteria', $kriteriaList->pluck('id_kriteria'));
            }])
            ->where('id_keputusan', $idKeputusan)
            ->get();

        $rawData = [];

        foreach ($alternatives as $alternatif) {
            $dataAlternatif = [
                // PERBAIKAN: Menggunakan id_alternatif, bukan id_mahasiswa, 
                // karena ini adalah Primary Key dari array alternatif di SAWService
                'id_alternatif' => $alternatif->id_alternatif, 
                'nama' => $alternatif->nama_alternatif,
            ];
            
            // Inisialisasi skor kriteria dengan 0 (untuk memastikan semua kriteria ada kuncinya)
            foreach ($criteriaKeys as $key) {
                $dataAlternatif[$key] = 0;
            }

            // 3. Isi nilai kriteria dari tabel Penilaian
            foreach ($alternatif->penilaian as $penilaian) {
                $idKriteria = $penilaian->id_kriteria;
                // Pastikan kriteria tersebut ada dalam map (seharusnya selalu ada)
                if (isset($kriteriaMap[$idKriteria])) {
                    $kodeKriteria = $kriteriaMap[$idKriteria];
                    // Gunakan nilai dari kolom 'nilai' di tabel penilaian
                    $dataAlternatif[$kodeKriteria] = (float) $penilaian->nilai; 
                }
            }
            
            // CATATAN PENTING UNTUK KELANJUTAN:
            // Jika ada kriteria seperti C2 (Skor Prestasi) yang datanya tidak ada di tabel 'penilaian'
            // tetapi perlu dihitung dari relasi lain (misalnya Mahasiswa->Prestasi),
            // maka proses perhitungan/pembobotan harus dilakukan di sini atau di tempat lain SEBELUM 
            // array $rawData dikembalikan, dan hasilnya harus disimpan kembali ke tabel 'penilaian'.
            
            $rawData[] = $dataAlternatif;
        }

        return [
            'alternatives' => $rawData,
            'criteria' => $kriteriaList,
        ];
    }
    
    /**
     * Saran: Metode untuk menghitung dan menyimpan kriteria yang kompleks (e.g., C2 dan C3).
     *
     * @param int $idKeputusan
     * @return bool
     */
    // public function calculateAndSaveComplexCriteria(int $idKeputusan): bool
    // {
    //     // 1. Ambil semua alternatif untuk $idKeputusan
    //     // 2. Loop melalui alternatif
    //     // 3. Ambil data prestasi (jika ada relasi) untuk setiap alternatif
    //     // 4. Hitung skor C2 (total skor) dan C3 (jumlah)
    //     // 5. Simpan/update nilai C2 dan C3 di tabel 'penilaian' untuk alternatif terkait.
    //     // return true/false
    // }
}