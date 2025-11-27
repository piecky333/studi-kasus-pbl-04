<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleLoginCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_registered_with_google_cannot_login_with_password_if_null()
    {
        // Create a user with google_id and null password
        $user = User::factory()->create([
            'email' => 'googleuser@example.com',
            'google_id' => '123456789',
            'password' => null, 
            'nama' => 'Google User',
            'username' => 'googleuser',
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'email' => 'googleuser@example.com',
            'password' => 'password',
        ]);

        // Should fail with standard auth error because password doesn't match (null vs 'password')
        $response->assertSessionHasErrors(['email' => trans('auth.failed')]);
        
        // Clean up
        $user->delete();
    }

    public function test_users_registered_with_google_can_login_if_password_set()
    {
        // Create a user with google_id AND a password
        $user = User::factory()->create([
            'email' => 'googleuserwithpass@example.com',
            'google_id' => '987654321',
            'password' => bcrypt('password'), 
            'nama' => 'Google User With Pass',
            'username' => 'googleuserwithpass',
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'email' => 'googleuserwithpass@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('user.dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
        
        // Clean up
        $user->delete();
    }

    public function test_normal_users_can_login()
    {
        $user = User::factory()->create([
            'email' => 'normaluser@example.com',
            'password' => bcrypt('password'),
            'nama' => 'Normal User',
            'username' => 'normaluser',
            'role' => 'user',
            'google_id' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'normaluser@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('user.dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);

        // Clean up
        $user->delete();
    }
}
