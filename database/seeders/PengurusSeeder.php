<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengurus;
use App\Models\DataMahasiswa;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class PengurusSeeder extends Seeder
{
    /**
     * Seed data pengurus HIMA-TI.
     * Memerlukan DivisiSeeder, JabatanSeeder, dan MahasiswaSeeder sudah dijalankan.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Pengurus::truncate();
        Schema::enableForeignKeyConstraints();

        // Ambil user pengurus
        $userPengurus = User::where('role', 'pengurus')->first();

        // Ambil beberapa mahasiswa dari seeder
        $mahasiswa = DataMahasiswa::inRandomOrder()->take(10)->get();
        $divisiList = Divisi::all();
        $jabatanList = Jabatan::all();

        if ($mahasiswa->isEmpty() || $divisiList->isEmpty() || $jabatanList->isEmpty()) {
            $this->command->warn('PengurusSeeder: Pastikan DivisiSeeder, JabatanSeeder, dan MahasiswaSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        // Data pengurus yang akan dibuat
        $pengurusData = [
            // Pengurus Inti - 4 orang
            ['jabatan' => 'Ketua Umum',       'divisi_index' => 0],
            ['jabatan' => 'Wakil Ketua Umum', 'divisi_index' => 0],
            ['jabatan' => 'Sekretaris Umum',  'divisi_index' => 0],
            ['jabatan' => 'Bendahara Umum',   'divisi_index' => 0],

            // Kepala & Anggota Divisi - mengambil divisi 1,2,3,4,5,6 (index 1-6)
            ['jabatan' => 'Ketua Divisi Akademik',              'divisi_index' => 1],
            ['jabatan' => 'Anggota Divisi Akademik',            'divisi_index' => 1],
            ['jabatan' => 'Ketua Divisi Minat & Bakat',         'divisi_index' => 2],
            ['jabatan' => 'Anggota Divisi Minat & Bakat',       'divisi_index' => 2],
            ['jabatan' => 'Ketua Divisi Kewirausahaan',         'divisi_index' => 3],
            ['jabatan' => 'Anggota Divisi Kewirausahaan',       'divisi_index' => 3],
        ];

        foreach ($pengurusData as $i => $data) {
            // Ambil mahasiswa secara berurutan (loop)
            $mhs = $mahasiswa[$i % $mahasiswa->count()];

            // Cari divisi berdasarkan index (mod agar tidak out of range)
            $divisi = $divisiList[$data['divisi_index'] % $divisiList->count()];

            // Cari jabatan berdasarkan nama
            $jabatan = Jabatan::where('nama_jabatan', $data['jabatan'])->first()
                ?? $jabatanList->first();

            Pengurus::create([
                'id_user'    => $mhs->id_user,
                'id_divisi'  => $divisi->id_divisi,
                'id_jabatan' => $jabatan->id_jabatan,
            ]);
        }

        $this->command->info('PengurusSeeder: ' . count($pengurusData) . ' data pengurus berhasil di-seed.');
    }
}
