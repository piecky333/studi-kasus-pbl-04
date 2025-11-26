<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $berita = \App\Models\berita::factory()->create();
    echo "Berita ID: " . $berita->id_berita . "\n";
    echo "Status: " . $berita->status . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
