<?php

namespace Database\Factories;

use App\Models\Prestasi;
use App\Models\DataMahasiswa;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prestasi>
 */
class PrestasiFactory extends Factory
{
    protected $model = Prestasi::class;

    public function definition(): array
    {
        return [
            'id_mahasiswa' => DataMahasiswa::factory(),
            'id_admin' => Admin::factory(),
            'nama_kegiatan' => $this->faker->sentence(3),
            'tingkat_prestasi' => $this->faker->randomElement(['Nasional', 'Internasional', 'Provinsi', 'Kabupaten/Kota']),
            'tahun' => $this->faker->year(),
            'status_validasi' => $this->faker->randomElement(['menunggu', 'disetujui', 'ditolak']),
        ];
    }
}
