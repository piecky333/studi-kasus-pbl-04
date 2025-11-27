<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestasiSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_search_mahasiswa_by_nim()
    {
        // Create admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Create mahasiswa
        $mahasiswa = Mahasiswa::create([
            'nim' => '12345678',
            'nama' => 'Test Mahasiswa',
            'email' => 'test@mhs.politala.ac.id',
            'semester' => 4,
            'id_user' => User::factory()->create(['role' => 'user'])->id_user,
        ]);

        // Act as admin
        $response = $this->actingAs($admin)
                         ->getJson(route('admin.prestasi.cariMahasiswa', ['nim' => '12345678']));

        // Assert
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'mahasiswa' => [
                         'nim' => '12345678',
                         'nama' => 'Test Mahasiswa',
                     ]
                 ]);
    }

    public function test_search_returns_error_if_not_found()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
                         ->getJson(route('admin.prestasi.cariMahasiswa', ['nim' => '99999999']));

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Mahasiswa tidak ditemukan'
                 ]);
    }
}
