<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\spkkeputusan;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class SubKriteriaTest extends TestCase
{
    use RefreshDatabase;

    public function test_subkriteria_edit_page_accessible()
    {
        // 1. Setup Data
        $user = User::factory()->create(['role' => 'admin']);
        $keputusan = spkkeputusan::create([
            'nama_keputusan' => 'Test Keputusan',
            'tanggal' => now(),
        ]);
        $kriteria = Kriteria::create([
            'id_keputusan' => $keputusan->id_keputusan,
            'nama_kriteria' => 'Test Kriteria',
            'kode_kriteria' => 'C1',
            'jenis_kriteria' => 'Benefit', // Added missing field
            'bobot_kriteria' => 0.5, // Added missing field (though bobot was there, ensuring consistency)
            'jenis' => 'benefit', // Keeping this if model uses it, but likely 'jenis_kriteria' is the column
        ]);
        $subkriteria = SubKriteria::create([
            'id_kriteria' => $kriteria->id_kriteria,
            'id_keputusan' => $keputusan->id_keputusan,
            'nama_subkriteria' => 'Test Sub',
            'nilai' => 10,
        ]);

        // 2. Define URL
        // /admin/spk/{idKeputusan}/kriteria/{idKriteria}/subkriteria/{subkriteriumId}/edit
        $url = route('admin.spk.kriteria.subkriteria.edit', [
            'idKeputusan' => $keputusan->id_keputusan,
            'idKriteria' => $kriteria->id_kriteria,
            'subkriteriumId' => $subkriteria->id_subkriteria,
        ]);

        echo "\nTesting URL: " . $url . "\n";

        // 3. Act
        $response = $this->actingAs($user)->get($url);

        // 4. Assert
        if ($response->status() !== 200) {
            echo "Status: " . $response->status() . "\n";
            // echo "Content: " . $response->getContent() . "\n";
        }
        
        $response->assertStatus(200);
    }
}
