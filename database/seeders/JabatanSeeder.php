<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;
use App\Models\admin\Divisi;
use Illuminate\Support\Facades\Schema;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        Schema::disableForeignKeyConstraints();
        Jabatan::truncate();
        Schema::enableForeignKeyConstraints();

        // Get all divisions
        $divisiList = Divisi::all();

        if ($divisiList->isEmpty()) {
            $this->command->info('No divisions found. Please run DivisiSeeder first.');
            return;
        }

        $jabatanPerDivisi = [
            'Ketua Divisi',
            'Sekretaris Divisi',
            'Bendahara Divisi',
            'Anggota Divisi',
        ];

        // foreach ($divisiList as $divisi) {
        //     foreach ($jabatanPerDivisi as $namaJabatan) {
        //         Jabatan::create([
        //             'nama_jabatan' => $namaJabatan . ' ' . $divisi->nama_divisi,
        //         ]);
        //     }
        // }
    }
}
