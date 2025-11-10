<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\komentar; // Pastikan import model komentar Anda
use App\Models\berita;   // Kita butuh model berita
use App\Models\User;     // Kita butuh model user
use Illuminate\Support\Facades\DB; // Untuk truncate

class KomentarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kosongkan tabel komentar terlebih dahulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        komentar::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Ambil data acak untuk relasi
        // Pastikan Anda sudah menjalankan BeritaSeeder dan UserSeeder
        $berita = berita::first(); 
        $user1 = User::first();
        $user2 = User::skip(1)->first(); // Ambil user kedua

        // 3. Cek apakah data user/berita ada
        if (!$berita || !$user1 || !$user2) {
            $this->command->error('Gagal menjalankan KomentarSeeder. Pastikan UserSeeder dan BeritaSeeder sudah dijalankan.');
            return;
        }

        // 4. Buat Komentar Induk #1
        $komentarInduk1 = komentar::create([
            'id_berita' => $berita->id_berita,
            'id_user' => $user1->id_user,
            'nama_komentator' => $user1->nama,
            'isi' => 'Ini adalah komentar INDUK pertama. Keren sekali prestasinya!',
            'parent_id' => null, // PENTING: null berarti ini induk
            'created_at' => now()->subDays(2),
        ]);

        // 5. Buat Balasan untuk Komentar #1
        komentar::create([
            'id_berita' => $berita->id_berita,
            'id_user' => $user2->id_user,
            'nama_komentator' => $user2->nama,
            'isi' => 'Setuju! Ini balasan untuk komentar pertama.',
            'parent_id' => $komentarInduk1->id_komentar, // <-- KUNCI: Menunjuk ke ID Induk
            'created_at' => now()->subDays(1),
        ]);

        // 6. Buat Balasan LAINNYA untuk Komentar #1
        komentar::create([
            'id_berita' => $berita->id_berita,
            'id_user' => $user1->id_user,
            'nama_komentator' => $user1->nama,
            'isi' => 'Saya balas komentar saya sendiri.',
            'parent_id' => $komentarInduk1->id_komentar, // <-- KUNCI: Menunjuk ke ID Induk
            'created_at' => now(),
        ]);

        // 7. Buat Komentar Induk #2 (tanpa balasan)
        komentar::create([
            'id_berita' => $berita->id_berita,
            'id_user' => $user2->id_user,
            'nama_komentator' => $user2->nama,
            'isi' => 'Ini adalah komentar INDUK kedua. Semoga sukses selalu!',
            'parent_id' => null, // PENTING: null berarti ini induk
            'created_at' => now()->subHours(5),
        ]);
        
        $this->command->info('KomentarSeeder (dengan balasan) berhasil dijalankan.');
    }
}