<?php

namespace Database\Factories\admin;

use App\Models\admin\Datamahasiswa;
use App\Models\User;
use App\Models\admin\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\admin\Datamahasiswa>
 */
class DatamahasiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Datamahasiswa::class;

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
