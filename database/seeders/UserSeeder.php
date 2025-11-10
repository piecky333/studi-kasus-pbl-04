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

        // Buat Akun Admin
        User::create([
            'nama' => 'Admin Sistem',
            'username' => 'admin',
            'email' => 'admin@politala.ac.id',
            'password' => Hash::make('password_admin'), 
            'role' => 'admin',
        ]);

        // Buat Akun Pengurus
        User::create([
            'nama' => 'Pengurus Ormawa',
            'username' => 'pengurus',
            'email' => 'pengurus@mhs.politala.ac.id',
            'password' => Hash::make('password_pengurus'), 
            'role' => 'pengurus',
        ]);
        
        // Buat Akun User Biasa
        User::create([
            'nama' => 'Mahasiswa Contoh',
            'username' => 'mahasiswa',
            'email' => 'mahasiswa@mhs.politala.ac.id',
            'password' => Hash::make('password_user'),
            'role' => 'user',
        ]);

        User::factory()->count(10)->pengurus()->create();

        User::factory()->count(30)->create();
    }
}
