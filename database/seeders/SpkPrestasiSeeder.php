<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Alternatif;
use App\Models\Spkkeputusan; 
use App\Models\Penilaian;    

use Illuminate\Support\Facades\Schema;

class SpkPrestasiSeeder extends Seeder
{
    /**
     * Menjalankan seeds database untuk kasus Mahasiswa Berprestasi.
     */
    public function run(): void
    {
        // Pastikan tabel dikosongkan terlebih dahulu untuk pengujian yang bersih
        Schema::disableForeignKeyConstraints();
        DB::table('penilaian')->truncate();
        DB::table('alternatif')->truncate();
        DB::table('subkriteria')->truncate();
        DB::table('kriteria')->truncate();
        DB::table('spkkeputusan')->truncate();
        Schema::enableForeignKeyConstraints();
        
        // --------------------------------------------------------
        // 1. DATA KEPUTUSAN (SPK)
        // Kolom: 'nama_keputusan', 'metode_yang_digunakan', 'status'
        // --------------------------------------------------------
        $keputusan = Spkkeputusan::create([
            'nama_keputusan' => 'Pemilihan Mahasiswa Berprestasi (MAWAPRES) 2025',
            'metode_yang_digunakan' => 'SAW', 
            'status' => 'Aktif',
        ]);
        $idKeputusan = $keputusan->id_keputusan;

        // --------------------------------------------------------
        // 2. DATA KRITERIA (C1, C2, C3)
        // --------------------------------------------------------
        $kriteriaData = [
            [
                'id_keputusan' => $idKeputusan,
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Indeks Prestasi Kumulatif (IPK)',
                'jenis_kriteria' => 'benefit',
                'bobot_kriteria' => 0.40,
                'cara_penilaian' => 'Input Langsung'
            ],
            [
                'id_keputusan' => $idKeputusan,
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Pengalaman Organisasi',
                'jenis_kriteria' => 'benefit',
                'bobot_kriteria' => 0.35,
                'cara_penilaian' => 'Pilihan Sub Kriteria'
            ],
            [
                'id_keputusan' => $idKeputusan,
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Tingkat Ketidakhadiran (Absensi)',
                'jenis_kriteria' => 'cost',
                'bobot_kriteria' => 0.25,
                'cara_penilaian' => 'Pilihan Sub Kriteria'
            ],
        ];

        DB::table('kriteria')->insert($kriteriaData);
        $kriteriaList = Kriteria::where('id_keputusan', $idKeputusan)->pluck('id_kriteria', 'kode_kriteria');
        $idKriteriaC1 = $kriteriaList['C1'];
        $idKriteriaC2 = $kriteriaList['C2'];
        $idKriteriaC3 = $kriteriaList['C3'];


        // --------------------------------------------------------
        // 3. DATA SUBKRITERIA (Untuk C2 dan C3)
        // --------------------------------------------------------
        $subkriteriaData = [
            // Subkriteria untuk C2: Pengalaman Organisasi (Benefit)
            ['id_kriteria' => $idKriteriaC2, 'nama_subkriteria' => 'Nasional/Internasional', 'nilai' => 5, 'id_keputusan' => $idKeputusan],
            ['id_kriteria' => $idKriteriaC2, 'nama_subkriteria' => 'Tingkat Fakultas/Universitas', 'nilai' => 4, 'id_keputusan' => $idKeputusan],
            ['id_kriteria' => $idKriteriaC2, 'nama_subkriteria' => 'Tingkat Jurusan', 'nilai' => 3, 'id_keputusan' => $idKeputusan],
            ['id_kriteria' => $idKriteriaC2, 'nama_subkriteria' => 'Tidak Berorganisasi', 'nilai' => 1, 'id_keputusan' => $idKeputusan],

            // Subkriteria untuk C3: Tingkat Ketidakhadiran (Cost)
            ['id_kriteria' => $idKriteriaC3, 'nama_subkriteria' => '0-1 Kali (Sangat Baik)', 'nilai' => 1, 'id_keputusan' => $idKeputusan],
            ['id_kriteria' => $idKriteriaC3, 'nama_subkriteria' => '2-3 Kali (Baik)', 'nilai' => 2, 'id_keputusan' => $idKeputusan],
            ['id_kriteria' => $idKriteriaC3, 'nama_subkriteria' => '4-5 Kali (Cukup)', 'nilai' => 3, 'id_keputusan' => $idKeputusan],
            ['id_kriteria' => $idKriteriaC3, 'nama_subkriteria' => '6+ Kali (Kurang)', 'nilai' => 5, 'id_keputusan' => $idKeputusan], 
        ];

        DB::table('subkriteria')->insert($subkriteriaData);


        // --------------------------------------------------------
        // 4. DATA ALTERNATIF (Mahasiswa)
        // Kolom Sesuai Model: 'id_keputusan', 'nama_alternatif'
        // --------------------------------------------------------
        $alternatifData = [
            ['id_keputusan' => $idKeputusan, 'nama_alternatif' => 'Budi Setiawan'], 
            ['id_keputusan' => $idKeputusan, 'nama_alternatif' => 'Siti Nurhaliza'], 
            ['id_keputusan' => $idKeputusan, 'nama_alternatif' => 'Andre Maulana'], 
            ['id_keputusan' => $idKeputusan, 'nama_alternatif' => 'Rina Wijaya'], 
        ];
        DB::table('alternatif')->insert($alternatifData);

        // Pluck berdasarkan 'nama' di seeder sebelumnya, sekarang menggunakan nama yang sesuai
        $alternatifList = Alternatif::where('id_keputusan', $idKeputusan)->pluck('id_alternatif', 'nama_alternatif');


        // --------------------------------------------------------
        // 5. DATA PENILAIAN (Xij)
        // Kolom Sesuai Model: 'id_kriteria', 'id_alternatif', 'nilai'
        // --------------------------------------------------------
        
        /* Penilaian Mentah (Xij):
        A1 (Budi): C1=3.80, C2=4 (Fakultas), C3=1 (0-1 kali absen)
        A2 (Siti): C1=3.55, C2=5 (Nasional), C3=3 (4-5 kali absen)
        A3 (Andre): C1=3.95, C2=3 (Jurusan), C3=2 (2-3 kali absen)
        A4 (Rina): C1=3.60, C2=1 (Tidak Org), C3=1 (0-1 kali absen)
        */

        $penilaianData = [
            // A1 (Budi Setiawan)
            ['id_alternatif' => $alternatifList['Budi Setiawan'], 'id_kriteria' => $idKriteriaC1, 'nilai' => 3.80], 
            ['id_alternatif' => $alternatifList['Budi Setiawan'], 'id_kriteria' => $idKriteriaC2, 'nilai' => 4],    
            ['id_alternatif' => $alternatifList['Budi Setiawan'], 'id_kriteria' => $idKriteriaC3, 'nilai' => 1],   

            // A2 (Siti Nurhaliza)
            ['id_alternatif' => $alternatifList['Siti Nurhaliza'], 'id_kriteria' => $idKriteriaC1, 'nilai' => 3.55], 
            ['id_alternatif' => $alternatifList['Siti Nurhaliza'], 'id_kriteria' => $idKriteriaC2, 'nilai' => 5],    
            ['id_alternatif' => $alternatifList['Siti Nurhaliza'], 'id_kriteria' => $idKriteriaC3, 'nilai' => 3],    

            // A3 (Andre Maulana)
            ['id_alternatif' => $alternatifList['Andre Maulana'], 'id_kriteria' => $idKriteriaC1, 'nilai' => 3.95], 
            ['id_alternatif' => $alternatifList['Andre Maulana'], 'id_kriteria' => $idKriteriaC2, 'nilai' => 3],    
            ['id_alternatif' => $alternatifList['Andre Maulana'], 'id_kriteria' => $idKriteriaC3, 'nilai' => 2],    
            
            // A4 (Rina Wijaya)
            ['id_alternatif' => $alternatifList['Rina Wijaya'], 'id_kriteria' => $idKriteriaC1, 'nilai' => 3.60], 
            ['id_alternatif' => $alternatifList['Rina Wijaya'], 'id_kriteria' => $idKriteriaC2, 'nilai' => 1],    
            ['id_alternatif' => $alternatifList['Rina Wijaya'], 'id_kriteria' => $idKriteriaC3, 'nilai' => 1],    
        ];

        DB::table('penilaian')->insert($penilaianData);

        $this->command->info('Data SPK Mahasiswa Berprestasi berhasil di-seed!');
    }
}