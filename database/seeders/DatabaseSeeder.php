<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Mahasiswa;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $adminUser = User::create([
            'nama' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        \App\Models\Admin::create([
            'id_user' => $adminUser->id_user,
            'nama' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create Mahasiswa User
        $mahasiswaUser = User::create([
            'nama' => 'Mahasiswa User',
            'username' => 'mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        \App\Models\Mahasiswa::create([
            'id_user' => $mahasiswaUser->id_user,
            'nim' => '12345678',
            'nama' => 'Mahasiswa User',
            'email' => 'mahasiswa@example.com',
            'semester' => 5,
            'alamat' => 'Jl. Contoh No. 1',
            'no_hp' => '08123456789',
        ]);
    }
}
