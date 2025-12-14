<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\spkkeputusan;
use App\Models\kriteria;
use Carbon\Carbon;

class SpkMahasiswaBerprestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates a sample SPK Decision "Mahasiswa Berprestasi"
     * with criteria corresponding to the user's Excel template:
     * 1. IPK
     * 2. Tingkatan Juara
     * 3. Juara
     * 4. Jumlah Prestasi
     */
    public function run()
    {
        // 1. Buat Keputusan SPK
        $keputusan = spkkeputusan::create([
            'nama_keputusan' => 'Pemilihan Mahasiswa Berprestasi Tahun ' . date('Y'),
            'status' => 'Draft',
            'tanggal_dibuat' => Carbon::now(),
        ]);

        $id = $keputusan->id_keputusan;

        // 2. Buat Kriteria
        // Bobot awal diset rata (0.25) atau sesuai prioritas umum. Bisa diedit user nanti.
        
        $kriteriaData = [
            [
                'id_keputusan' => $id,
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'IPK',
                'jenis_kriteria' => 'Benefit',
                'bobot_kriteria' => 0.25,
                'sumber_data' => 'Mahasiswa',
                'atribut_sumber' => 'ipk',
            ],
            [
                'id_keputusan' => $id,
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Tingkatan Juara', 
                'jenis_kriteria' => 'Benefit',
                'bobot_kriteria' => 0.25,
                'sumber_data' => 'Prestasi',
                'atribut_sumber' => null, 
            ],
            [
                'id_keputusan' => $id,
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'Juara', // Matches 'juara' keyword in logic
                'jenis_kriteria' => 'Benefit',
                'bobot_kriteria' => 0.25,
                'sumber_data' => 'Prestasi',
                'atribut_sumber' => null, // Calculated by SpkCalculator
            ],
            [
                'id_keputusan' => $id,
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'Jumlah Prestasi', // Matches 'jumlah' keyword in logic
                'jenis_kriteria' => 'Benefit',
                'bobot_kriteria' => 0.25,
                'sumber_data' => 'Prestasi',
                'atribut_sumber' => null, // Count
            ],
        ];

        foreach ($kriteriaData as $k) {
            kriteria::create($k);
        }
    }
}
