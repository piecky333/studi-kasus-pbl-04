<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\spkkeputusan;
use App\Models\kriteria;
use App\Models\SubKriteria;
use App\Models\admin\Datamahasiswa;
use App\Models\admin\Prestasi;
use App\Models\alternatif;
use App\Models\User; // Assuming User model is needed for Datamahasiswa foreign key
use App\Services\AhpService; // To recalculate weights

class SpkCaseSeeder extends Seeder
{
    public function run()
    {
        DB::beginTransaction();
        try {
            // 1. Create Decision Event
            $keputusan = spkkeputusan::create([
                'nama_keputusan' => 'Pemilihan Mahasiswa Berprestasi (Case Study)',
                'tanggal_dibuat' => now(),
                'status' => 'Draft'
            ]);

            $idKeputusan = $keputusan->id_keputusan;
            $this->command->info("Created Keputusan ID: $idKeputusan");

            // 2. Create Criteria (Benefits only based on User Request)
            $criteriaData = [
                ['nama' => 'IPK', 'kode' => 'C1', 'type' => 'Benefit', 'sumber' => 'Mahasiswa', 'attr' => 'ipk'],
                ['nama' => 'Tingkatan Juara', 'kode' => 'C2', 'type' => 'Benefit', 'sumber' => 'Prestasi', 'attr' => null],
                ['nama' => 'Juara', 'kode' => 'C3', 'type' => 'Benefit', 'sumber' => 'Prestasi', 'attr' => null],
                ['nama' => 'Jumlah Prestasi', 'kode' => 'C4', 'type' => 'Benefit', 'sumber' => 'Prestasi', 'attr' => null],
            ];

            $criteriaIds = [];
            foreach ($criteriaData as $c) {
                $k = kriteria::create([
                    'id_keputusan' => $idKeputusan,
                    'nama_kriteria' => $c['nama'],
                    'kode_kriteria' => $c['kode'],
                    'jenis_kriteria' => $c['type'],
                    'sumber_data' => $c['sumber'],
                    'atribut_sumber' => $c['attr'],
                    'bobot_kriteria' => 0 // Akan dihitung via AHP
                ]);
                $criteriaIds[$c['nama']] = $k->id_kriteria;
            }

            // 3. Create Sub-Criteria (Dynamic Weights)
            // Tingkatan Juara
            $subTingkat = [
                ['nama' => 'Internasional', 'nilai' => 5],
                ['nama' => 'Nasional', 'nilai' => 4],
                ['nama' => 'Provinsi', 'nilai' => 3],
                ['nama' => 'Kabupaten/Kota', 'nilai' => 2],
                ['nama' => 'Internal', 'nilai' => 1]
            ];
            foreach ($subTingkat as $sub) {
                SubKriteria::create([
                    'id_kriteria' => $criteriaIds['Tingkatan Juara'],
                    'nama_subkriteria' => $sub['nama'],
                    'nilai' => $sub['nilai'],
                    'id_keputusan' => $idKeputusan
                ]);
            }

            // Juara
            $subJuara = [
                ['nama' => 'Juara 1', 'nilai' => 5],
                ['nama' => 'Emas', 'nilai' => 5],
                ['nama' => 'Juara 2', 'nilai' => 4],
                ['nama' => 'Perak', 'nilai' => 4],
                ['nama' => 'Juara 3', 'nilai' => 3],
                ['nama' => 'Perunggu', 'nilai' => 3],
                ['nama' => 'Harapan', 'nilai' => 2],
                ['nama' => 'Favorit', 'nilai' => 2],
                ['nama' => 'Finalis', 'nilai' => 1],
                ['nama' => 'Peserta', 'nilai' => 1],
            ];
            foreach ($subJuara as $sub) {
                SubKriteria::create([
                    'id_kriteria' => $criteriaIds['Juara'],
                    'nama_subkriteria' => $sub['nama'],
                    'nilai' => $sub['nilai'],
                    'id_keputusan' => $idKeputusan
                ]);
            }

            // 4. Calculate AHP Weights (Matrix from User)
            // IPK (0), Tk.Juara (1), Juara (2), Jml.Prestasi (3)
            // Matrix:
            //       IPK  TJ   J    Jml
            // IPK   1    5    5    5
            // TJ    0.2  1    2    2
            // J     0.2  0.5  1    2
            // Jml   0.2  0.5  0.5  1
            
            $ahpService = new AhpService();
            // Map Index to ID: 0=>C1, 1=>C2, 2=>C3, 3=>C4
            $indexMap = [
                0 => $criteriaIds['IPK'],
                1 => $criteriaIds['Tingkatan Juara'],
                2 => $criteriaIds['Juara'],
                3 => $criteriaIds['Jumlah Prestasi']
            ];
            
            $matrixInput = [
                [1.0, 5.0, 5.0, 5.0],
                [0.2, 1.0, 2.0, 2.0],
                [0.2, 0.5, 1.0, 2.0],
                [0.2, 0.5, 0.5, 1.0]
            ];
            
            // Reconstruct logic manually since AhpService::buildMatrix takes form input
            // But AhpService::calculateAhp takes raw matrix array.
            
            $ahpResult = $ahpService->calculateAhp($matrixInput, 4);
            $weights = $ahpResult['weights'];
            
            // Save Weights to DB
            foreach ($indexMap as $idx => $cId) {
                kriteria::where('id_kriteria', $cId)->update(['bobot_kriteria' => $weights[$idx]]);
            }
            $this->command->info("AHP Weights Calculated and Saved.");

            // 5. Create Students (Mahasiswa 1-10) and Achievements
            // Target Scores:
            // M1: IPK 3.75, TJ 7, J 7, Count 2
            // M2: IPK 3.71, TJ 8, J 10, Count 2
            // M3: IPK 3.16, TJ 4, J 8, Count 2
            // M4: IPK 2.60, TJ 1, J 5, Count 2
            // M5: IPK 3.60, TJ 4, J 1, Count 1
            // M6: IPK 3.88, TJ 3, J 1, Count 1
            // M7: IPK 2.93, TJ 6, J 8, Count 2
            // M8: IPK 3.95, TJ 1, J 4, Count 1
            // M9: IPK 3.62, TJ 7, J 8, Count 2
            // M10: IPK 2.77, TJ 3, J 3, Count 1

            $students = [
                ['name' => 'Mahasiswa 1', 'ipk' => 3.75, 'tj' => 7, 'j' => 7, 'cnt' => 2],
                ['name' => 'Mahasiswa 2', 'ipk' => 3.71, 'tj' => 8, 'j' => 10, 'cnt' => 2],
                ['name' => 'Mahasiswa 3', 'ipk' => 3.16, 'tj' => 4, 'j' => 8, 'cnt' => 2],
                ['name' => 'Mahasiswa 4', 'ipk' => 2.60, 'tj' => 1, 'j' => 5, 'cnt' => 2],
                ['name' => 'Mahasiswa 5', 'ipk' => 3.60, 'tj' => 4, 'j' => 1, 'cnt' => 1],
                ['name' => 'Mahasiswa 6', 'ipk' => 3.88, 'tj' => 3, 'j' => 1, 'cnt' => 1],
                ['name' => 'Mahasiswa 7', 'ipk' => 2.93, 'tj' => 6, 'j' => 8, 'cnt' => 2],
                ['name' => 'Mahasiswa 8', 'ipk' => 3.95, 'tj' => 1, 'j' => 4, 'cnt' => 1],
                ['name' => 'Mahasiswa 9', 'ipk' => 3.62, 'tj' => 7, 'j' => 8, 'cnt' => 2],
                ['name' => 'Mahasiswa 10', 'ipk' => 2.77, 'tj' => 3, 'j' => 3, 'cnt' => 1],
            ];

            foreach ($students as $s) {
                // Create Dummy User & Mahasiswa
                $user = User::create([
                    'nama' => $s['name'], 
                    'username' => strtolower(str_replace(' ', '', $s['name'])), // Add username
                    'email' => strtolower(str_replace(' ', '', $s['name'])) . '@example.com',
                    'password' => bcrypt('password'),
                ]);

                $mhs = Datamahasiswa::create([
                    'id_user' => $user->id_user, // Correct primary key access
                    'nim' => 'NIM' . rand(1000, 9999),
                    'nama' => $s['name'],
                    'email' => strtolower(str_replace(' ', '', $s['name'])) . '@example.com', // Add email
                    'ipk' => $s['ipk'],
                    'semester' => 6
                ]);

                // Create Alternatif Link
                alternatif::create([
                    'id_keputusan' => $idKeputusan,
                    'id_mahasiswa' => $mhs->id_mahasiswa,
                    'nama_alternatif' => $s['name']
                ]);

                // Reverse Engineer Achievements to match TJ and J
                // This is a simplified "Best Fit" logic since multiple combinations exist.
                // We just need ANY combination that sums up to the target.
                
                $remainingTJ = $s['tj'];
                $remainingJ = $s['j'];
                $count = $s['cnt'];

                // Distribute broadly
                for ($i = 0; $i < $count; $i++) {
                    $valTJ = ($i == $count - 1) ? $remainingTJ : floor($remainingTJ / $count);
                    $valJ = ($i == $count - 1) ? $remainingJ : floor($remainingJ / $count);
                    
                    $remainingTJ -= $valTJ;
                    $remainingJ -= $valJ;

                    // Map Value to Name
                    $namaTingkat = $this->getTingkatName($valTJ);
                    $namaJuara = $this->getJuaraName($valJ);

                    Prestasi::create([
                        'id_mahasiswa' => $mhs->id_mahasiswa,
                        'nama_kegiatan' => "Lomba " . $s['name'] . " " . ($i+1),
                        'tingkat_prestasi' => $namaTingkat,
                        'juara' => $namaJuara,
                        'tahun' => date('Y'), // Add current year
                        'status_validasi' => 'disetujui'
                    ]);
                }
            }
            
            DB::commit();
            $this->command->info("Seeding Completed Successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Seeding Failed: " . $e->getMessage());
        }
    }

    private function getTingkatName($val) {
        $map = [5=>'Internasional', 4=>'Nasional', 3=>'Provinsi', 2=>'Kabupaten/Kota', 1=>'Internal'];
        return $map[$val] ?? 'Internal'; // Fallback
    }

    private function getJuaraName($val) {
        $map = [5=>'Juara 1', 4=>'Juara 2', 3=>'Juara 3', 2=>'Harapan 1', 1=>'Finalis'];
        return $map[$val] ?? 'Peserta';
    }
}
