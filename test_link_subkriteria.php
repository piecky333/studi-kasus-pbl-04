<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing link 'Atur Sub' fix...\n";

// Test the corrected route for "Atur Sub" link
try {
    // This is the corrected route that should be used in kriteria index
    $url = route('admin.spk.manage.kriteria.subkriteria', [
        'idKeputusan' => 1,
        'idKriteria' => 1
    ]);
    echo "✓ Corrected 'Atur Sub' link route resolved: $url\n";
    echo "✓ Link should work for navigating to subkriteria management\n";
} catch (Exception $e) {
    echo "✗ Error with corrected route: " . $e->getMessage() . "\n";
}

// Test old broken route (should fail)
try {
    $url = route('admin.spk.manage.subkriteria', 1);
    echo "✗ Old route still exists (unexpected): $url\n";
} catch (Exception $e) {
    echo "✓ Old broken route properly removed: " . $e->getMessage() . "\n";
}

echo "Link testing completed.\n";
