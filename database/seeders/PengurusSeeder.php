<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\admin\pengurus;
use App\Models\User;
use App\Models\admin\divisi;

class PengurusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user yang akan dijadikan pengurus (misal user dengan role 'pengurus' atau user biasa)
        // Di sini kita ambil user acak atau buat user baru jika perlu
        // Asumsi: UserSeeder sudah dijalankan dan ada user yang tersedia
        
        $users = User::where('role', 'pengurus')->get();
        $divisi = divisi::all();

        if ($users->count() > 0 && $divisi->count() > 0) {
            foreach ($users as $index => $user) {
                // Assign user ke divisi secara bergantian atau acak
                $selectedDivisi = $divisi->random();
                
                pengurus::create([
                    'id_divisi' => $selectedDivisi->id_divisi,
                    'id_user' => $user->id_user,
                    'posisi_jabatan' => 'Anggota Divisi ' . $selectedDivisi->nama_divisi, // Contoh posisi
                ]);
            }
        } else {
            // Fallback jika tidak ada data yang cukup, buat dummy
            $this->command->info('Tidak ada user dengan role pengurus atau data divisi kosong. Seeder pengurus dilewati atau buat data dummy manual.');
        }
    }
}
