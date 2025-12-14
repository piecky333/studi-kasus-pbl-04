<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //menonaktifkan foreign key
        Schema::disableForeignKeyConstraints();

        //mengosongkan tabel users
        User::truncate();

        //mengaktifkan foreign key
        Schema::enableForeignKeyConstraints();

        // 1. Akun Admin
        User::create([
            'nama' => 'Admin Sistem',
            'username' => 'admin',
            'email' => 'admin@politala.ac.id',
            'password' => Hash::make('password_admin'), 
            'role' => 'admin',
        ]);

        // 2. Akun Pengurus
        User::create([
            'nama' => 'Pengurus Ormawa',
            'username' => 'pengurus',
            'email' => 'pengurus@politala.ac.id',
            'password' => Hash::make('password_pengurus'), 
            'role' => 'pengurus',
        ]);
        
        // 3. Akun Mahasiswa (Role Mahasiswa - domain @mhs)
        User::create([
            'nama' => 'Mahasiswa Politala',
            'username' => 'mahasiswa',
            'email' => 'mahasiswa@mhs.politala.ac.id',
            'password' => Hash::make('password_mahasiswa'),
            'role' => 'mahasiswa',
        ]);

        // 4. Akun User Biasa (Umum)
        User::create([
            'nama' => 'User Umum',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password_user'),
            'role' => 'user',
        ]);

        User::factory()->count(10)->pengurus()->create();

        User::factory()->count(30)->create();
    }
}
