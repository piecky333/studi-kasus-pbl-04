<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

$k = \App\Models\kriteria::find(13);
if ($k) {
    // Delete relations manually to be safe
    \App\Models\penilaian::where('id_kriteria', 13)->delete();
    \App\Models\subkriteria::where('id_kriteria', 13)->delete();
    
    $k->delete();
    echo "Deleted Kriteria ID 13 (C9).\n";
} else {
    echo "Kriteria ID 13 not found.\n";
}
