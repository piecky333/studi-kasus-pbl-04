<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaFactory extends Factory
{
    public function definition(): array
    {
        $storagePath = 'berita'; 
        $imageUrl = 'https://picsum.photos/1024/768';
        $filename = Str::uuid() . '.jpg';

        Storage::disk('public')->makeDirectory($storagePath);

        try {
            // 1. Unduh gambar
            $imageContent = Http::get($imageUrl)->body();

            // 2. Langsung simpan ke storage
            $fullPath = $storagePath . '/' . $filename;
            Storage::disk('public')->put($fullPath, $imageContent);

            $gambarPath = $fullPath;

        } catch (\Exception $e) {
            // Jika download gagal, biarkan null
            $gambarPath = null; 
        }

        // Ambil ID user acak 
        $userId = \App\Models\User::inRandomOrder()->first()
        ->id_user ?? \App\Models\User::factory()->create()->id_user;

        //tambahkan ini untuk isi berita dengan beberapa paragraf
        $paragraphs = $this->faker->paragraphs(5); 

        // 2. Gabungkan paragraf menjadi satu string dengan tag <p>
        $isi = '<p>' . implode('</p><p>', $paragraphs) . '</p>';

        // ----------------------------------

        return [
            'id_user' => $userId,
            'judul_berita' => $this->faker->sentence(6),
            'isi_berita' => $isi, 
            'kategori' => $this->faker->randomElement(['kegiatan', 'prestasi']),
            'gambar_berita' => $gambarPath,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}