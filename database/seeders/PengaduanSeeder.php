<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\laporan\pengaduan;

class PengaduanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil ID user yang memiliki role 'user'
        $userIds = User::where('role', 'user')->pluck('id_user')->toArray();

        if (empty($userIds)) {
            $this->command->info('Tidak ada user dengan role "user". Seeder Pengaduan dilewati.');
            return;
        }

        $jenisKasus = ['Bullying', 'Pelecehan Seksual', 'Kekerasan Fisik', 'Diskriminasi', 'Pelanggaran Kode Etik'];
        $statusList = ['Terkirim', 'Diproses', 'Selesai', 'Ditolak'];

        foreach (range(1, 20) as $index) {
            pengaduan::create([
                'id_user' => $faker->randomElement($userIds),
                'judul' => $faker->sentence(4),
                'jenis_kasus' => $faker->randomElement($jenisKasus),
                'deskripsi' => $faker->paragraph(3),
                'status' => $faker->randomElement($statusList),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 months', 'now'),
            ]);
        }
    }
}
