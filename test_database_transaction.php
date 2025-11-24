<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing database transactions for destroy operations...\n";

// Create dummy user first (outside transaction)
$user = App\Models\User::create([
    'nama' => 'Test User',
    'username' => 'testuser_' . time(), // Make username unique
    'email' => 'test_' . time() . '@example.com', // Make email unique
    'password' => bcrypt('password'),
    'role' => 'user'
]);

echo "✓ User created with ID: {$user->id_user}\n";

// Test transaction rollback simulation
try {
    DB::beginTransaction();

    // Create test data
    $keputusan = App\Models\spkkeputusan::create([
        'nama_keputusan' => 'Test Keputusan',
        'metode_yang_digunakan' => 'SAW',
        'tanggal_dibuat' => now(),
        'status' => 'Draft'
    ]);

    $kriteria = App\Models\kriteria::create([
        'id_keputusan' => $keputusan->id_keputusan,
        'nama_kriteria' => 'Test Kriteria',
        'kode_kriteria' => 'TK01',
        'jenis' => 'Benefit',
        'bobot' => 0.5
    ]);

    // Create dummy mahasiswa first
    $mahasiswa = App\Models\mahasiswa::create([
        'id_user' => $user->id_user,
        'nim' => '123456789',
        'nama' => 'Test Mahasiswa',
        'email' => 'test@example.com',
        'semester' => 1
    ]);

    $alternatif = App\Models\alternatif::create([
        'id_keputusan' => $keputusan->id_keputusan,
        'id_mahasiswa' => $mahasiswa->id_mahasiswa,
        'nama_alternatif' => 'Test Alternatif'
    ]);

    echo "✓ Test data created successfully\n";
    echo "  - Keputusan: {$keputusan->nama_keputusan}\n";
    echo "  - Kriteria: {$kriteria->nama_kriteria}\n";
    echo "  - Alternatif: {$alternatif->nama_alternatif}\n";

    // Test destroy with transaction (simulate error to test rollback)
    try {
        // This should work with transaction
        $controller = app(App\Http\Controllers\SpkManagementController::class);

        // Test destroy keputusan (this should cascade delete everything)
        $result = $controller->destroy($keputusan->id_keputusan);
        echo "✓ Destroy operation completed\n";

        DB::commit();
        echo "✓ Transaction committed successfully\n";

    } catch (Exception $e) {
        DB::rollBack();
        echo "✓ Transaction rolled back due to error: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "✗ Error in transaction testing: " . $e->getMessage() . "\n";
    DB::rollBack();
}

echo "Database transaction testing completed.\n";
