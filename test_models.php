<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing model relationships...\n";

// Test admin user
$user = App\Models\User::where('role', 'admin')->first();
if ($user) {
    echo "✓ Admin user found: " . $user->nama . " (" . $user->email . ")\n";
} else {
    echo "✗ No admin user found\n";
}

// Test SPK models
try {
    $keputusan = App\Models\spkkeputusan::first();
    if ($keputusan) {
        echo "✓ Keputusan SPK found: " . $keputusan->nama_keputusan . "\n";

        // Test relationships
        $kriteriaCount = $keputusan->kriteria()->count();
        echo "✓ Kriteria count: " . $kriteriaCount . "\n";

        $alternatifCount = $keputusan->alternatif()->count();
        echo "✓ Alternatif count: " . $alternatifCount . "\n";
    } else {
        echo "ℹ No keputusan SPK found (database might be empty)\n";
    }
} catch (Exception $e) {
    echo "✗ Error testing SPK models: " . $e->getMessage() . "\n";
}

echo "Model testing completed.\n";
