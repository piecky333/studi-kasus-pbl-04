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
        $user = User::create([
            'nama' => 'Admin Sistem',
            'username' => 'admin',
            'email' => 'admin@politala.ac.id',
            'password' => 'password_admin', 
            'role' => 'admin',
        ]);

        // Create Admin detail record
        \App\Models\Admin::create([
            'id_user' => $user->id_user,
            'nama_admin' => 'Admin Sistem 1',
            'jabatan_admin' => 'Administrator Utama',
        ]);

        // 1b. Akun Admin 2
        $user2 = User::create([
            'nama' => 'Admin Sistem 2',
            'username' => 'admin2',
            'email' => 'admin2@politala.ac.id',
            'password' => 'password_admin', 
            'role' => 'admin',
        ]);

        \App\Models\Admin::create([
            'id_user' => $user2->id_user,
            'nama_admin' => 'Admin Sistem 2',
            'jabatan_admin' => 'Administrator Pendukung',
        ]);

        // 2. Akun Pengurus
        User::create([
            'nama' => 'Pengurus Ormawa',
            'username' => 'pengurus',
            'email' => 'pengurus@politala.ac.id',
            'password' => 'password_pengurus', 
            'role' => 'pengurus',
        ]);
        
        // 3. Akun Mahasiswa (Role Mahasiswa - domain @mhs)
        User::create([
            'nama' => 'Mahasiswa Politala',
            'username' => 'mahasiswa',
            'email' => 'mahasiswa@mhs.politala.ac.id',
            'password' => 'password_mahasiswa',
            'role' => 'mahasiswa',
        ]);

        // 4. Akun User Biasa (Umum)
        User::create([
            'nama' => 'User Umum',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => 'password_user',
            'role' => 'user',
        ]);
    }
}

