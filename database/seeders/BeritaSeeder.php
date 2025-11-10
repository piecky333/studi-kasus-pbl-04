<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita; 
use Illuminate\Support\Facades\Storage; // <-- Tambahkan ini
use Illuminate\Support\Facades\DB;      // <-- Tambahkan ini

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Matikan foreign key check untuk truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Berita::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Hapus folder 'berita' lama di storage
        Storage::disk('public')->deleteDirectory('berita');
        
        // 3. Buat ulang folder 'berita'
        Storage::disk('public')->makeDirectory('berita');

        // 4. Membuat 25 data berita (factory akan menangani download)
        $this->command->info('Membuat 25 data berita (mengunduh gambar)...');
        Berita::factory()->count(25)->create();

        $this->command->info('BeritaSeeder selesai.');
    }
}