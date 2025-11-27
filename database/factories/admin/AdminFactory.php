<?php

namespace Database\Factories\admin;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\admin\Admin;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\admin\Admin>
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => User::factory()->admin(), // Buat user dengan role admin
            'nama_admin' => fake()->name(),
            'jabatan_admin' => fake()->jobTitle(),
        ];
    }
}
