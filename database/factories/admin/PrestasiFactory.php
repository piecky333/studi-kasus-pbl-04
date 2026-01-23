<?php

namespace Database\Factories\admin;

use App\Models\admin\Prestasi;
use App\Models\admin\DataMahasiswa;
use App\Models\admin\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\admin\Prestasi>
 */
class PrestasiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Prestasi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
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
