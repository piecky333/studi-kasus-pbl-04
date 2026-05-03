<?php

namespace Database\Factories;

use App\Models\DataMahasiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataMahasiswa>
 */
class DataMahasiswaFactory extends Factory
{
    protected $model = DataMahasiswa::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'nim' => $this->faker->unique()->numerify('##########'),
            'nama' => $this->faker->name(),
            'program_studi' => $this->faker->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Manajemen Informatika']),
            'angkatan' => $this->faker->numberBetween(2020, 2024),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
        ];
    }
}
