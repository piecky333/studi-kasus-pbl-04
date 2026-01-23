<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\admin\DataMahasiswa;
use App\Models\admin\Prestasi;
use App\Models\admin\Admin; // Import correct Admin model
use Illuminate\Support\Facades\DB;

class FixSpkDataSeeder extends Seeder
{
    public function run()
    {
        // Get a valid admin ID
        $adminId = Admin::value('id_admin');
        if (!$adminId) {
            $this->command->error("No admin found in database. Cannot create Prestasi.");
            return;
        }

        // 1. Annisa (IPK 2.77)
        $annisa = DataMahasiswa::where('ipk', 2.77)->orWhere('nama', 'LIKE', '%Annisa%')->first();
        if ($annisa) {
            $this->updatePrestasi($annisa, $adminId, [
                [
                    'nama_kegiatan' => 'Lomba Puisi Provinsi',
                    'jenis_prestasi' => 'Non-Akademik',
                    'tingkat_prestasi' => 'Provinsi', // Score 3
                    'juara' => 'Juara 3', // Score 3
                    'tahun' => date('Y'),
                    'status_validasi' => 'disetujui'
                ]
            ]);
            $this->command->info("Updated Annisa.");
        }

        // 2. Maryana (IPK 3.16)
        $maryana = DataMahasiswa::where('ipk', 3.16)->orWhere('nama', 'LIKE', '%Maryana%')->first();
        if ($maryana) {
            $this->updatePrestasi($maryana, $adminId, [
                [
                    'nama_kegiatan' => 'Lomba 1',
                    'jenis_prestasi' => 'Akademik',
                    'tingkat_prestasi' => 'Kabupaten', // Score 2
                    'juara' => 'Juara 2', // Score 4
                    'tahun' => date('Y'),
                    'status_validasi' => 'disetujui'
                ],
                [
                    'nama_kegiatan' => 'Lomba 2',
                    'jenis_prestasi' => 'Akademik',
                    'tingkat_prestasi' => 'Kabupaten', // Score 2
                    'juara' => 'Juara 2', // Score 4
                    'tahun' => date('Y'),
                    'status_validasi' => 'disetujui'
                ]
            ]);
            $this->command->info("Updated Maryana.");
        }

        // 3. Siti Nurhaliza (IPK 3.88)
        $siti = DataMahasiswa::where('ipk', 3.88)->orWhere('nama', 'LIKE', '%Siti Nurhaliza%')->first();
        if ($siti) {
            $this->updatePrestasi($siti, $adminId, [
                [
                    'nama_kegiatan' => 'Seminar Nasional',
                    'jenis_prestasi' => 'Akademik',
                    'tingkat_prestasi' => 'Provinsi', // Score 3
                    'juara' => 'Peserta', // Score 1
                    'tahun' => date('Y'),
                    'status_validasi' => 'disetujui'
                ]
            ]);
            $this->command->info("Updated Siti Nurhaliza.");
        }

        // 4. Rahmah Sa'adah (IPK 2.60)
        $rahmah = DataMahasiswa::where('ipk', 2.60)->orWhere('nama', 'LIKE', '%Rahmah%')->first();
        if ($rahmah) {
            $this->updatePrestasi($rahmah, $adminId, [
                [
                    'nama_kegiatan' => 'Lomba Lokal',
                    'jenis_prestasi' => 'Non-Akademik',
                    'tingkat_prestasi' => 'Sekolah', // Score 1
                    'juara' => 'Juara 2', // Score 4
                    'tahun' => date('Y'),
                    'status_validasi' => 'disetujui'
                ],
                [
                    'nama_kegiatan' => 'Partisipasi',
                    'jenis_prestasi' => 'Non-Akademik',
                    'tingkat_prestasi' => 'Sekolah', // Score 1
                    'juara' => 'Peserta', // Score 1
                    'tahun' => date('Y'),
                    'status_validasi' => 'disetujui'
                ]
            ]);
            $this->command->info("Updated Rahmah Sa'adah.");
        }
        
    }

    private function updatePrestasi($mahasiswa, $adminId, $prestasiList)
    {
        // Delete existing based on mahasiswa id
        // Note: calling $mahasiswa->prestasi()->delete() works too
        Prestasi::where('id_mahasiswa', $mahasiswa->id_mahasiswa)->delete();

        foreach ($prestasiList as $data) {
            Prestasi::create(array_merge($data, [
                'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                'id_admin' => $adminId,
                'deskripsi' => 'Fixed by Seeder'
            ]));
        }
    }
}
