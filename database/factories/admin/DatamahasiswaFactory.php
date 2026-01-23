<?php

namespace Database\Factories\Admin;

use App\Models\Admin\DataMahasiswa;
use App\Models\User;
use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\DataMahasiswa>
 */
class DataMahasiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DataMahasiswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nim' => $this->faker->unique()->numerify('########'),
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'semester' => $this->faker->numberBetween(1, 14),
            'id_user' => User::factory(), // Create a related user
            'id_admin' => Admin::factory(), // Create a related admin
        ];
    }
}
