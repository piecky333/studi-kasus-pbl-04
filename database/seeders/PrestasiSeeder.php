<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prestasi;
use App\Models\DataMahasiswa;
use Illuminate\Support\Facades\Schema;

class PrestasiSeeder extends Seeder
{
    /**
     * Seed data prestasi mahasiswa.
     * Memerlukan MahasiswaSeeder sudah dijalankan.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Prestasi::truncate();
        Schema::enableForeignKeyConstraints();

        $mahasiswaList = DataMahasiswa::inRandomOrder()->take(15)->get();

        if ($mahasiswaList->isEmpty()) {
            $this->command->warn('PrestasiSeeder: Tidak ada data mahasiswa. Jalankan MahasiswaSeeder terlebih dahulu.');
            return;
        }

        $prestasiData = [
            [
                'nama_kegiatan'    => 'Olimpiade Matematika Tingkat Nasional',
                'jenis_prestasi'   => 'Akademik',
                'tingkat_prestasi' => 'Nasional',
                'juara'            => 'Juara 1',
                'tahun'            => 2025,
                'status_validasi'  => 'disetujui',
            ],
            [
                'nama_kegiatan'    => 'Kompetisi Desain UI/UX Mahasiswa Indonesia',
                'jenis_prestasi'   => 'Non-Akademik',
                'tingkat_prestasi' => 'Nasional',
                'juara'            => 'Juara 2',
                'tahun'            => 2025,
                'status_validasi'  => 'disetujui',
            ],
            [
                'nama_kegiatan'    => 'Hackathon Inovasi Teknologi Kalimantan',
                'jenis_prestasi'   => 'Akademik',
                'tingkat_prestasi' => 'Provinsi',
                'juara'            => 'Juara 1',
                'tahun'            => 2024,
                'status_validasi'  => 'disetujui',
            ],
            [
                'nama_kegiatan'    => 'Lomba Karya Tulis Ilmiah Perguruan Tinggi',
                'jenis_prestasi'   => 'Akademik',
                'tingkat_prestasi' => 'Nasional',
                'juara'            => 'Juara 3',
                'tahun'            => 2024,
                'status_validasi'  => 'disetujui',
            ],
            [
                'nama_kegiatan'    => 'Turnamen Futsal Antar Kampus Politala',
                'jenis_prestasi'   => 'Non-Akademik',
                'tingkat_prestasi' => 'Universitas',
                'juara'            => 'Juara 1',
                'tahun'            => 2025,
                'status_validasi'  => 'disetujui',
            ],
            [
                'nama_kegiatan'    => 'Kontes Robot Nasional (KRN)',
                'jenis_prestasi'   => 'Akademik',
                'tingkat_prestasi' => 'Nasional',
                'juara'            => 'Finalis',
                'tahun'            => 2024,
                'status_validasi'  => 'menunggu',
            ],
            [
                'nama_kegiatan'    => 'Lomba Debat Bahasa Inggris Tingkat Provinsi',
                'jenis_prestasi'   => 'Akademik',
                'tingkat_prestasi' => 'Provinsi',
                'juara'            => 'Juara 2',
                'tahun'            => 2025,
                'status_validasi'  => 'disetujui',
            ],
            [
                'nama_kegiatan'    => 'Kejuaraan Badminton Mahasiswa Se-Kalimantan',
                'jenis_prestasi'   => 'Non-Akademik',
                'tingkat_prestasi' => 'Provinsi',
                'juara'            => 'Juara 3',
                'tahun'            => 2024,
                'status_validasi'  => 'disetujui',
            ],
            [
                'nama_kegiatan'    => 'Lomba Pemrograman Nasional IEEE',
                'jenis_prestasi'   => 'Akademik',
                'tingkat_prestasi' => 'Internasional',
                'juara'            => 'Juara Harapan 1',
                'tahun'            => 2025,
                'status_validasi'  => 'menunggu',
            ],
            [
                'nama_kegiatan'    => 'Festival Kreasi Mahasiswa Politala',
                'jenis_prestasi'   => 'Non-Akademik',
                'tingkat_prestasi' => 'Universitas',
                'juara'            => 'Juara 1',
                'tahun'            => 2025,
                'status_validasi'  => 'disetujui',
            ],
            [
                'nama_kegiatan'    => 'Data Science Competition Indonesia',
                'jenis_prestasi'   => 'Akademik',
                'tingkat_prestasi' => 'Nasional',
                'juara'            => 'Juara 2',
                'tahun'            => 2024,
                'status_validasi'  => 'disetujui',
            ],
            [
                'nama_kegiatan'    => 'Olimpiade Komputer Tingkat Kabupaten',
                'jenis_prestasi'   => 'Akademik',
                'tingkat_prestasi' => 'Kabupaten/Kota',
                'juara'            => 'Juara 1',
                'tahun'            => 2024,
                'status_validasi'  => 'disetujui',
            ],
        ];

        foreach ($prestasiData as $i => $data) {
            $mhs = $mahasiswaList[$i % $mahasiswaList->count()];
            Prestasi::create(array_merge($data, [
                'id_mahasiswa' => $mhs->id_mahasiswa,
            ]));
        }

        $this->command->info('PrestasiSeeder: ' . count($prestasiData) . ' data prestasi berhasil di-seed.');
    }
}
