<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //user dengan role admin
        User::create([
            'nama' => 'Administrator',
            'username' => 'iwan',
            'email' => 'admin1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin' 
        ]);
    }
}
