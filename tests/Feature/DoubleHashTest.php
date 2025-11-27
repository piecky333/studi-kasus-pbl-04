<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DoubleHashTest extends TestCase
{
    use RefreshDatabase;

    public function test_double_hashing_hypothesis()
    {
        // Create user with Hash::make
        $user = User::create([
            'nama' => 'Double Hash User',
            'username' => 'doublehash',
            'email' => 'double@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $storedUser = User::where('email', 'double@example.com')->first();
        
        // Check if the stored password matches 'password'
        if (Hash::check('password', $storedUser->password)) {
             $this->assertTrue(true, 'Password matches, so NO double hashing issue.');
        } else {
             $this->fail('Password does NOT match. Double hashing occurred!');
        }
    }

    public function test_single_hashing_works()
    {
        // Create user WITHOUT Hash::make, relying on 'hashed' cast
        $user = User::create([
            'nama' => 'Single Hash User',
            'username' => 'singlehash',
            'email' => 'single@example.com',
            'password' => 'password', // Plain text, let cast handle it
            'role' => 'user',
        ]);

        // Try to login
        $response = $this->post('/login', [
            'email' => 'single@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('user.dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
    }
}
