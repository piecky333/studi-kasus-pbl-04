<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //memanggil semua seeder
        $this->call([
            UserSeeder::class,
            BeritaSeeder::class,
            KomentarSeeder::class,
            SpkMahasiswaBerprestasiSeeder::class,
            DivisiSeeder::class,
            JabatanSeeder::class,
            TingkatanJuaraSeeder::class,
        ]);
    }
}