<?php

namespace Database\Factories\admin;

use App\Models\admin\sanksi;
use App\Models\admin\DataMahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\admin\sanksi>
 */
class SanksiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = sanksi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_mahasiswa' => DataMahasiswa::factory(),
            'tanggal_sanksi' => $this->faker->date(),
            'jenis_sanksi' => $this->faker->randomElement(['Teguran Lisan', 'Teguran Tertulis', 'Skorsing', 'Drop Out']),
        ];
    }
}
