<?php

namespace Database\Factories;

use App\Models\Sanksi;
use App\Models\DataMahasiswa;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sanksi>
 */
class SanksiFactory extends Factory
{
    protected $model = Sanksi::class;

    public function definition(): array
    {
        return [
            'id_mahasiswa' => DataMahasiswa::factory(),
            'id_admin' => Admin::factory(),
            'jenis_sanksi' => $this->faker->randomElement(['Peringatan 1', 'Peringatan 2', 'Peringatan 3', 'Skorsing']),
            'keterangan' => $this->faker->sentence(),
            'tanggal_sanksi' => $this->faker->date(),
        ];
    }
}
