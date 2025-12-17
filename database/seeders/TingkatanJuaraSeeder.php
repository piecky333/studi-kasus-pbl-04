<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\kriteria;
use App\Models\SubKriteria;

class TingkatanJuaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Cari Kriteria 'Tingkatan Juara'
        // Gunakan 'like' untuk fleksibilitas jika ada prefix kode [C2] dst
        $kriteria = Kriteria::where('nama_kriteria', 'like', '%Tingkatan Juara%')->first();

        if (!$kriteria) {
            $this->command->error("Kriteria 'Tingkatan Juara' tidak ditemukan. Pastikan Seeder Kriteria sudah dijalankan.");
            return;
        }

        $this->command->info("Menambahkan Sub Kriteria untuk: " . $kriteria->nama_kriteria);

        // 2. Daftar Sub Kriteria yang akan ditambahkan (Standard Options)
        $subKriteriaItems = [
            'Juara 1',
            'Juara 2',
            'Juara 3',
            'Juara Harapan 1',
            'Juara Harapan 2',
            'Juara Harapan 3',
            'Favorit',
            'Finalis',
            'Peserta',
        ];

        // 3. Loop insert/update
        foreach ($subKriteriaItems as $nama) {
            SubKriteria::firstOrCreate(
                [
                    'id_kriteria' => $kriteria->id_kriteria,
                    'nama_subkriteria' => $nama,
                ],
                [
                    'id_keputusan' => $kriteria->id_keputusan, // Gunakan ID keputusan dari parent kriteria
                    'nilai' => 1, // Default value sesuai request user
                ]
            );
        }

        $this->command->info("Berhasil menambahkan " . count($subKriteriaItems) . " sub kriteria.");
    }
}
