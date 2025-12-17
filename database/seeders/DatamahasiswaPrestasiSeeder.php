<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\admin\Datamahasiswa;
use App\Models\admin\Prestasi;
use App\Models\User;
use App\Models\admin\Admin;

class DatamahasiswaPrestasiSeeder extends Seeder
{
    public function run()
    {
        // Disable Foreign Key Checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate Tables
        Datamahasiswa::truncate();
        Prestasi::truncate();
        \App\Models\alternatif::truncate(); // Reset Alternatif as well
        // User::truncate(); // Be careful truncating User if there are other users. 
                             // But since we are creating users, we might want to delete users with role 'mahasiswa' or something.
                             // For safety in this specific task context, I will just truncate Datamahasiswa and Prestasi.
                             // The created users will remain but won't be linked if I don't delete them.
                             // Let's delete users created by this seeder pattern if possible, or just truncate if it is safe.
                             // Given "Kasus PBL", it's likely a project. I'll truncate User too to be clean.
        User::where('role', 'mahasiswa')->delete(); 

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ensure we have a dummy user for admin
        $userAdmin = User::firstOrCreate(
            ['username' => 'admin_seeder'],
            [
                'nama' => 'Admin Seeder',
                'email' => 'admin_seeder@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );

        $admin = Admin::firstOrCreate(
             ['id_user' => $userAdmin->id_user],
             [
                 'nama_admin' => 'Admin Seeder',
                 // 'jabatan' => 'Staff' // Column not found/optional
             ]
        );

        // Data from the user's first image
        $students = [
            [
                'nama' => 'DANU RIZKY MAULANA',
                'ipk' => 3.75,
                'prestasi' => [
                    ['tingkat' => 'Provinsi', 'juara' => 'Juara 2 Kategori Video Editing'],
                    ['tingkat' => 'Nasional', 'juara' => 'Juara 3'],
                ]
            ],
            [
                'nama' => 'MUHAMMAD RAWILDHAN',
                'ipk' => 3.71,
                'prestasi' => [
                    ['tingkat' => 'Provinsi', 'juara' => 'Juara 1 Kategori Mengetik Cepat'],
                    ['tingkat' => 'Internasional', 'juara' => 'Juara 1 Mengetik Cepat'],
                ]
            ],
            [
                'nama' => 'MARYANA',
                'ipk' => 3.16,
                'prestasi' => [
                    ['tingkat' => 'Kabupaten/Kota', 'juara' => 'Juara 2'],
                    ['tingkat' => 'Kabupaten/Kota', 'juara' => 'Juara 2'],
                ]
            ],
            [
                'nama' => "RAHMAH SA'ADAH",
                'ipk' => 2.60,
                'prestasi' => [
                    ['tingkat' => 'Internal', 'juara' => 'Lolos Seleksi Administrasi'],
                ]
            ],
            [
                'nama' => 'TITIN SADIYAH',
                'ipk' => 3.60,
                'prestasi' => [
                    ['tingkat' => 'Nasional', 'juara' => 'Lolos Seleksi Administrasi'],
                ]
            ],
            [
                'nama' => 'SITI NURHALIZA',
                'ipk' => 3.88,
                'prestasi' => [
                    ['tingkat' => 'Provinsi', 'juara' => 'Juara 3'],
                ]
            ],
            [
                'nama' => 'M. REZA FAHLEVI',
                'ipk' => 2.93,
                'prestasi' => [
                    ['tingkat' => 'Provinsi', 'juara' => 'Juara 2 Embu Beregu Putera'],
                    ['tingkat' => 'Provinsi', 'juara' => 'Juara 2 Embu Berpasangan Putera'],
                ]
            ],
            [
                'nama' => 'MUHAMMAD ZAKI',
                'ipk' => 3.95,
                'prestasi' => [
                    ['tingkat' => 'Internal', 'juara' => 'Juara 2 Embu Beregu Putera'],
                ]
            ],
            [
                'nama' => 'Reihan Fariza',
                'ipk' => 3.62,
                'prestasi' => [
                    ['tingkat' => 'Nasional', 'juara' => 'Juara 1 Beregu U-21'],
                    ['tingkat' => 'Provinsi', 'juara' => 'Juara 3 Pasangan U-21'],
                ]
            ],
            [
                'nama' => 'Annisa',
                'ipk' => 2.77,
                'prestasi' => [
                    ['tingkat' => 'Provinsi', 'juara' => 'Medali Perunggu (Juara 3)'],
                ]
            ],
        ];

        foreach ($students as $index => $data) {
            // Create Dummy User for each student
            $username = strtolower(str_replace([' ', "'", '.'], '', $data['nama'])) . rand(100, 999);
            $user = User::create([
                'nama' => $data['nama'],
                'username' => $username,
                'email' => $username . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'mahasiswa'
            ]);

            // Create Mahasiswa
            $mahasiswa = Datamahasiswa::create([
                'nim' => '2023' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'nama' => $data['nama'],
                'ipk' => $data['ipk'],
                'id_user' => $user->id_user,
                'id_admin' => $admin->id_admin,
                'email' => $user->email,
                'semester' => 5
            ]);

            // Create Prestasi
            foreach ($data['prestasi'] as $p) {
                Prestasi::create([
                    'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                    'id_admin' => $admin->id_admin,
                    'nama_kegiatan' => 'Kegiatan ' . $p['juara'],
                    'jenis_prestasi' => 'Akademik', // Default or Non-Akademik as needed
                    'tingkat_prestasi' => $p['tingkat'],
                    'juara' => $p['juara'],
                    'tahun' => date('Y'),
                    'status_validasi' => 'disetujui',
                    'deskripsi' => 'Prestasi seeded automatically',
                ]);
            }

            // Create Alternatif for SPK
            // Find the active decision (created by SpkMahasiswaBerprestasiSeeder)
            $keputusan = \App\Models\spkkeputusan::where('nama_keputusan', 'Pemilihan Mahasiswa Berprestasi')->first();
            
            if ($keputusan) {
                $alternatif = \App\Models\alternatif::create([
                    'id_keputusan' => $keputusan->id_keputusan,
                    'id_mahasiswa' => $mahasiswa->id_mahasiswa, // Link directly
                    'nama_alternatif' => $mahasiswa->nama,
                ]);

                // Sync/Calculate Score for Penilaian
                // 1. IPK (C1)
                $criteriaIPK = \App\Models\kriteria::where('id_keputusan', $keputusan->id_keputusan)->where('kode_kriteria', 'C1')->first();
                if ($criteriaIPK) {
                    \App\Models\penilaian::create([
                        'id_alternatif' => $alternatif->id_alternatif,
                        'id_kriteria' => $criteriaIPK->id_kriteria,
                        'nilai' => $mahasiswa->ipk
                    ]);
                }

                $prestasiValid = $mahasiswa->prestasi; // All seeded as 'disetujui'

                // 2. Tingkatan Juara (C2)
                $criteriaC2 = \App\Models\kriteria::where('id_keputusan', $keputusan->id_keputusan)->where('kode_kriteria', 'C2')->first();
                if ($criteriaC2) {
                     $val = \App\Services\SpkCalculator::calculateTingkatScore($prestasiValid);
                     \App\Models\penilaian::create([
                        'id_alternatif' => $alternatif->id_alternatif,
                        'id_kriteria' => $criteriaC2->id_kriteria,
                        'nilai' => $val
                    ]);
                }

                // 3. Juara (C3)
                $criteriaC3 = \App\Models\kriteria::where('id_keputusan', $keputusan->id_keputusan)->where('kode_kriteria', 'C3')->first();
                if ($criteriaC3) {
                     $val = \App\Services\SpkCalculator::calculateJuaraScore($prestasiValid);
                     \App\Models\penilaian::create([
                        'id_alternatif' => $alternatif->id_alternatif,
                        'id_kriteria' => $criteriaC3->id_kriteria,
                        'nilai' => $val
                    ]);
                }

                // 4. Jumlah Prestasi (C4)
                $criteriaC4 = \App\Models\kriteria::where('id_keputusan', $keputusan->id_keputusan)->where('kode_kriteria', 'C4')->first();
                if ($criteriaC4) {
                     $val = $prestasiValid->count();
                     \App\Models\penilaian::create([
                        'id_alternatif' => $alternatif->id_alternatif,
                        'id_kriteria' => $criteriaC4->id_kriteria,
                        'nilai' => $val
                    ]);
                }
            }
        }
        
        $this->command->info('Datamahasiswa, Prestasi, and Alternatif seeded successfully!');
    }
}
