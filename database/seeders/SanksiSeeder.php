<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sanksi;
use App\Models\DataMahasiswa;
use Illuminate\Support\Facades\Schema;

class SanksiSeeder extends Seeder
{
    /**
     * Seed data sanksi mahasiswa.
     * Memerlukan MahasiswaSeeder sudah dijalankan.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Sanksi::truncate();
        Schema::enableForeignKeyConstraints();

        $mahasiswaList = DataMahasiswa::inRandomOrder()->take(10)->get();

        if ($mahasiswaList->isEmpty()) {
            $this->command->warn('SanksiSeeder: Tidak ada data mahasiswa. Jalankan MahasiswaSeeder terlebih dahulu.');
            return;
        }

        $sanksiData = [
            [
                'tanggal_sanksi' => '2025-01-15',
                'jenis_sanksi'   => 'Ringan',
                'jenis_hukuman'  => 'Teguran Lisan',
                'keterangan'     => 'Terlambat mengumpulkan laporan kegiatan organisasi lebih dari 3 hari.',
            ],
            [
                'tanggal_sanksi' => '2025-02-10',
                'jenis_sanksi'   => 'Ringan',
                'jenis_hukuman'  => 'Surat Peringatan SP-1',
                'keterangan'     => 'Tidak hadir dalam rapat koordinasi tanpa pemberitahuan.',
            ],
            [
                'tanggal_sanksi' => '2025-03-05',
                'jenis_sanksi'   => 'Sedang',
                'jenis_hukuman'  => 'Surat Peringatan SP-2',
                'keterangan'     => 'Menggunakan fasilitas organisasi tanpa izin pengurus.',
            ],
            [
                'tanggal_sanksi' => '2025-03-20',
                'jenis_sanksi'   => 'Sedang',
                'jenis_hukuman'  => 'Skorsing 1 Bulan',
                'keterangan'     => 'Terbukti menyebarkan informasi yang tidak benar terkait kegiatan HIMA-TI.',
            ],
            [
                'tanggal_sanksi' => '2025-04-01',
                'jenis_sanksi'   => 'Berat',
                'jenis_hukuman'  => 'Pencabutan Keanggotaan Sementara',
                'keterangan'     => 'Melanggar kode etik organisasi dan merusak nama baik HIMA-TI.',
            ],
            [
                'tanggal_sanksi' => '2025-04-15',
                'jenis_sanksi'   => 'Ringan',
                'jenis_hukuman'  => 'Teguran Tertulis',
                'keterangan'     => 'Absen dari kegiatan wajib tanpa keterangan selama 2 kali berturut-turut.',
            ],
            [
                'tanggal_sanksi' => '2025-05-02',
                'jenis_sanksi'   => 'Sedang',
                'jenis_hukuman'  => 'Pengurangan Hak Suara',
                'keterangan'     => 'Tidak menyelesaikan tugas divisi yang telah diamanahkan.',
            ],
        ];

        foreach ($sanksiData as $i => $data) {
            $mhs = $mahasiswaList[$i % $mahasiswaList->count()];
            Sanksi::create(array_merge($data, [
                'id_mahasiswa' => $mhs->id_mahasiswa,
            ]));
        }

        $this->command->info('SanksiSeeder: ' . count($sanksiData) . ' data sanksi berhasil di-seed.');
    }
}
