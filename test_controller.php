<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing SpkManagementController methods...\n";

// Test controller instantiation
try {
    $controller = app(App\Http\Controllers\SpkManagementController::class);
    echo "✓ SpkManagementController instantiated successfully\n";
} catch (Exception $e) {
    echo "✗ Error instantiating controller: " . $e->getMessage() . "\n";
}

// Test route resolution
try {
    $url = route('admin.spk.index');
    echo "✓ Route admin.spk.index resolved: " . $url . "\n";
} catch (Exception $e) {
    echo "✗ Error resolving route: " . $e->getMessage() . "\n";
}

// Test specific SPK routes (corrected route names)
$testRoutes = [
    'admin.spk.create',
    'admin.spk.manage.kriteria',
    'admin.spk.manage.kriteria.subkriteria', // Corrected route name
    'admin.spk.manage.alternatif',
    'admin.spk.manage.penilaian',
    'admin.spk.calculate.proses',
    'admin.spk.manage.hasil'
];

foreach ($testRoutes as $routeName) {
    try {
        // For routes with parameters, we need to provide dummy IDs
        if (strpos($routeName, 'manage') !== false) {
            if (strpos($routeName, 'subkriteria') !== false) {
                $url = route($routeName, ['idKeputusan' => 1, 'idKriteria' => 1]);
            } else {
                $url = route($routeName, ['idKeputusan' => 1]);
            }
        } else {
            $url = route($routeName, ['idKeputusan' => 1]);
        }
        echo "✓ Route $routeName resolved\n";
    } catch (Exception $e) {
        echo "✗ Error resolving route $routeName: " . $e->getMessage() . "\n";
    }
}

echo "Controller testing completed.\n";
