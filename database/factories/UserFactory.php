<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Kata sandi default untuk semua user dummy
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'), // Password default: "password"
            'role' => 'user', // <-- Role default adalah 'user'
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State untuk membuat user dengan role 'admin'
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * State untuk membuat user dengan role 'pengurus'
     */
    public function pengurus(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pengurus',
        ]);
    }
}