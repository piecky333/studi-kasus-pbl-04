<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\spkkeputusan;
use App\Models\kriteria;
use App\Models\PerbandinganKriteria;

class SpkMahasiswaBerprestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Buat atau Ambil Keputusan "Pemilihan Mahasiswa Berprestasi"
        $keputusan = spkkeputusan::firstOrCreate(
            ['nama_keputusan' => 'Pemilihan Mahasiswa Berprestasi'],
            [
                'tanggal_dibuat' => now(),
                'status' => 'Draft'
            ]
        );

        $idKeputusan = $keputusan->id_keputusan;

        // 2. Bersihkan data lama agar idempotent (bisa dijalankan berkali-kali)
        PerbandinganKriteria::where('id_keputusan', $idKeputusan)->delete();
        kriteria::where('id_keputusan', $idKeputusan)->delete();

        // 3. Insert Kriteria Baru
        // C1: IPK, Benefit, 0.6069
        $c1 = kriteria::create([
            'id_keputusan' => $idKeputusan,
            'kode_kriteria' => 'C1',
            'nama_kriteria' => 'IPK',
            'jenis_kriteria' => 'Benefit',
            'bobot_kriteria' => 0.6069,
            'sumber_data' => 'Mahasiswa', 
        ]);

        // C2: Tingkatan Juara, Benefit, 0.1758
        $c2 = kriteria::create([
            'id_keputusan' => $idKeputusan,
            'kode_kriteria' => 'C2',
            'nama_kriteria' => 'Tingkatan Juara',
            'jenis_kriteria' => 'Benefit',
            'bobot_kriteria' => 0.1758,
            'sumber_data' => 'Prestasi',
        ]);

        // C3: Juara, Benefit, 0.1285
        $c3 = kriteria::create([
            'id_keputusan' => $idKeputusan,
            'kode_kriteria' => 'C3',
            'nama_kriteria' => 'Juara',
            'jenis_kriteria' => 'Benefit',
            'bobot_kriteria' => 0.1285,
            'sumber_data' => 'Prestasi',
        ]);

        // C4: Jumlah Prestasi, Benefit, 0.0888
        $c4 = kriteria::create([
            'id_keputusan' => $idKeputusan,
            'kode_kriteria' => 'C4',
            'nama_kriteria' => 'Jumlah Prestasi',
            'jenis_kriteria' => 'Benefit',
            'bobot_kriteria' => 0.0888,
            'sumber_data' => 'Prestasi',
        ]);

        // 4. Insert Matriks Perbandingan Berpasangan
        // Format: Kriteria 1 vs Kriteria 2 = Nilai
        // Jika Nilai > 1, Kriteria 1 lebih penting.
        // Jika Nilai di array input (cth: UI) tampil di kiri 'merah' (Kriteria 2 lebih penting), 
        // maka di DB simpan nilai = 1/Nilai atau balik posisi K1/K2.
        
        // Sesuai Gambar: Kriteria A (Kiri) | Skala | Kriteria B (Kanan)
        // Gambar menunjukkan Skala Hijau (Kiri) aktif, berarti Kriteria A lebih penting.

        // Row 1: C1 (IPK) vs C2 (Tingkatan Juara) = 5
        PerbandinganKriteria::create([
            'id_keputusan' => $idKeputusan,
            'id_kriteria_1' => $c1->id_kriteria,
            'id_kriteria_2' => $c2->id_kriteria,
            'nilai_perbandingan' => 5
        ]);

        // Row 2: C1 (IPK) vs C3 (Juara) = 5
        PerbandinganKriteria::create([
            'id_keputusan' => $idKeputusan,
            'id_kriteria_1' => $c1->id_kriteria,
            'id_kriteria_2' => $c3->id_kriteria,
            'nilai_perbandingan' => 5
        ]);

        // Row 3: C1 (IPK) vs C4 (Jumlah Prestasi) = 5
        PerbandinganKriteria::create([
            'id_keputusan' => $idKeputusan,
            'id_kriteria_1' => $c1->id_kriteria,
            'id_kriteria_2' => $c4->id_kriteria,
            'nilai_perbandingan' => 5
        ]);

        // Row 4: C2 (Tingkatan Juara) vs C3 (Juara) = 2
        PerbandinganKriteria::create([
            'id_keputusan' => $idKeputusan,
            'id_kriteria_1' => $c2->id_kriteria,
            'id_kriteria_2' => $c3->id_kriteria,
            'nilai_perbandingan' => 2
        ]);

        // Row 5: C2 (Tingkatan Juara) vs C4 (Jumlah Prestasi) = 2
        PerbandinganKriteria::create([
            'id_keputusan' => $idKeputusan,
            'id_kriteria_1' => $c2->id_kriteria,
            'id_kriteria_2' => $c4->id_kriteria,
            'nilai_perbandingan' => 2
        ]);

        // Row 6: C3 (Juara) vs C4 (Jumlah Prestasi) = 2
        PerbandinganKriteria::create([
            'id_keputusan' => $idKeputusan,
            'id_kriteria_1' => $c3->id_kriteria,
            'id_kriteria_2' => $c4->id_kriteria,
            'nilai_perbandingan' => 2
        ]);
        
        $this->command->info('Seeder SPK Mahasiswa Berprestasi berhasil dijalankan!');
    }
}
