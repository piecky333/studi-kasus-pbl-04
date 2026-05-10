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
     * Seed 5 data pengurus HIMA-TI.
     * Memerlukan DivisiSeeder, JabatanSeeder, dan MahasiswaSeeder sudah dijalankan.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Pengurus::truncate();
        Schema::enableForeignKeyConstraints();

        // Ambil 5 mahasiswa pertama (urutan konsisten, bukan random)
        $mahasiswa = DataMahasiswa::with('user')->take(5)->get();
        $divisiList = Divisi::all();
        $jabatanList = Jabatan::all();

        if ($mahasiswa->isEmpty() || $divisiList->isEmpty() || $jabatanList->isEmpty()) {
            $this->command->warn('PengurusSeeder: Pastikan DivisiSeeder, JabatanSeeder, dan MahasiswaSeeder sudah dijalankan.');
            return;
        }

        // 5 pengurus dengan jabatan dan divisi berbeda
        $pengurusData = [
            ['jabatan' => 'Ketua Umum',            'divisi_index' => 0],
            ['jabatan' => 'Sekretaris Umum',        'divisi_index' => 0],
            ['jabatan' => 'Bendahara Umum',         'divisi_index' => 0],
            ['jabatan' => 'Ketua Divisi Akademik',  'divisi_index' => 1],
            ['jabatan' => 'Ketua Divisi Minat & Bakat', 'divisi_index' => 2],
        ];

        foreach ($pengurusData as $i => $data) {
            $mhs     = $mahasiswa[$i];
            $divisi  = $divisiList[$data['divisi_index'] % $divisiList->count()];
            $jabatan = Jabatan::where('nama_jabatan', $data['jabatan'])->first()
                       ?? $jabatanList->first();

            Pengurus::create([
                'id_user'    => $mhs->id_user,
                'id_divisi'  => $divisi->id_divisi,
                'id_jabatan' => $jabatan->id_jabatan,
            ]);

            // Ubah role user menjadi pengurus agar mereka bisa login ke panel pengurus
            $mhs->user->update(['role' => 'pengurus']);
        }

        $this->command->info('PengurusSeeder: 5 data pengurus berhasil di-seed.');
    }
}
