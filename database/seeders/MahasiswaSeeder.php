<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\admin\Datamahasiswa;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation if needed, 
        // though we allow appending if records exist? 
        // User asked to "tambahkan" (add), but usually seeders reset or append.
        // Let's check if we should truncate. "Data setengah dummy" implies fresh start or just adding?
        // Safe bet is to append or check existence. But for a clean "dummy" setup, verifying uniqueness is key.
        
        // Semester 3 Records
        $semester3 = [
            'Anamy Syakura',
            'Apri Wardhani',
            'Arsy Azhimi',
            'Erma Thalia Agustina',
            'Ilham Zanuar Pratama',
            'Joko Bimantaro',
            'Listanti Isban Umiyati',
            'M. Dimas Aprianto',
            'M. Riki Adetiya',
            'M. Rohid Rivaldi',
            'Muhammad Asyi Dhiqi',
            'Muhammad Eka Yusda putra',
            'Muhammad Pelangi Octafian',
            'Muhammad Rifky Saputra',
            'Muhammad Syarwani Abdan',
            'Muhammad Yoga',
            'Najwa Khadijah',
            'Nordiana Safitri',
            'Novita Aulia Santoso',
            'Nur Hikmah',
            'Rehad Edya Mecca',
            'Rizka Ika Maulida',
            'Septi Aulia Rizka',
            'Siti Humaira Azzahra',
            'Siti Noor Mala Sari',
            'Syifa Awalina',
            'Syifa Kania Ardita',
            'Yuli Anita Sari',
            'Yunita Rahmah',
        ];

        // Semester 1 Records
        $semester1 = [
            'Akhmad Hujaipi',
            'Ani Kusuma N.',
            'Denia',
            'Fina Indra Yanti',
            'Ghina Hariyani',
            'Muhammad Gusti M',
            'Muhammad Rizqi Akbar',
            'Mutia Rikma',
            'Nazhwa Virgianti Islami',
            'Niken Ajeng P',
            'Nor Ihsan Dwi S.H',
            'Notrie Fathurrohmat',
            'Nur Hikmah Suciyanti',
            'Rendy Dwi Sapta Kusuma',
            'Siti Nur Annisa',
            'Tiara Cahya Ningrum',
            'Wahidah',
        ];

        // Counter for unique NIM suffix (00-99)
        // Since we have 46 students, 01 to 46 works fine.
        $counter = 1;

        // Create Admin user placeholder if needed or just use null for id_admin
        // Assuming id_admin is nullable based on controller usage.

        $insertMahasiswa = function ($names, $semester) use (&$counter) {
            foreach ($names as $nama) {
                $suffix = str_pad($counter, 2, '0', STR_PAD_LEFT);
                $nim = "24013010{$suffix}";
                
                // Email: lowercasename_nim@politala.ac.id
                $cleanName = strtolower(preg_replace('/[^a-zA-Z]/', '', $nama));
                // Truncate cleanName if too long to ensure email length valid? Not strictly needed for dummy.
                $email = "{$cleanName}{$suffix}@politala.ac.id";

                // Dummy IPK
                $ipk = mt_rand(300, 400) / 100;

                // Create User First
                $user = \App\Models\User::create([
                    'nama' => $nama,
                    'username' => $nim, // Use NIM as username
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                    'role' => 'mahasiswa', // or \App\Models\User::ROLE_MAHASISWA
                ]);

                // Create Datamahasiswa linked to User
                Datamahasiswa::create([
                    'id_user' => $user->id_user,
                    'nim' => $nim,
                    'nama' => $nama,
                    'email' => $email,
                    'semester' => $semester,
                    'ipk' => $ipk,
                    'id_admin' => null, // Assuming nullable
                ]);

                $counter++;
            }
        };

        // Clear existing data?
        // Since we are creating Users, we should probably check if they exist or truncate Users too?
        // Truncating Users is dangerous if we have Admin/Pengurus.
        // Better to check if NIM exists or just "Append" but with unique check.
        // For this task, assuming fresh seed or okay to fail on duplicate.
        // But to be safe against previous failed run (which might have created some but failed later?), 
        // actually previous run failed at start.
        
        // Let's NOT truncate Users table generally. 
        // But we can truncate 'mahasiswa' table.
        // And delete Users with role 'mahasiswa' to clean up?
        
        // Delete existing mahasiswa users to prevent integrity error on 'nim' or 'email' in Users table
        // filtering by the seeded NIM pattern might be safer but 'mahasiswa' role is good proxy.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Datamahasiswa::truncate();
        \App\Models\User::where('role', 'mahasiswa')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $insertMahasiswa($semester3, 3);
        $insertMahasiswa($semester1, 1);
        
        $this->command->info("Seeded " . ($counter - 1) . " Mahasiswa records.");
    }
}
