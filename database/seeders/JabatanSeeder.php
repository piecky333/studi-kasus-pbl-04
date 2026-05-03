<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Schema;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Jabatan::truncate();
        Schema::enableForeignKeyConstraints();

        $jabatan = [
            // Pengurus Inti
            'Ketua Umum',
            'Wakil Ketua Umum',
            'Sekretaris Umum',
            'Bendahara Umum',
            // Divisi Akademik
            'Ketua Divisi Akademik',
            'Anggota Divisi Akademik',
            // Divisi Minat & Bakat
            'Ketua Divisi Minat & Bakat',
            'Anggota Divisi Minat & Bakat',
            // Divisi Kewirausahaan
            'Ketua Divisi Kewirausahaan',
            'Anggota Divisi Kewirausahaan',
            // Divisi Hubungan Masyarakat
            'Ketua Divisi Hubungan Masyarakat',
            'Anggota Divisi Hubungan Masyarakat',
            // Divisi Sosial & Lingkungan
            'Ketua Divisi Sosial & Lingkungan',
            'Anggota Divisi Sosial & Lingkungan',
            // Divisi Teknologi & Informasi
            'Ketua Divisi Teknologi & Informasi',
            'Anggota Divisi Teknologi & Informasi',
        ];

        foreach ($jabatan as $nama) {
            Jabatan::create(['nama_jabatan' => $nama]);
        }

        $this->command->info('JabatanSeeder: ' . count($jabatan) . ' jabatan berhasil di-seed.');
    }
}
