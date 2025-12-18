<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Subkriteria; // Uppercase S, lowercase k
use App\Models\kriteria; // lowercase k

$c2 = kriteria::where('nama_kriteria', 'like', '%Tingkatan Juara%')->first();
if ($c2) {
    echo "--- C2 (Tingkatan Juara) ---\n";
    $subs = Subkriteria::where('id_kriteria', $c2->id_kriteria)->get();
    foreach($subs as $sub) {
        echo "'" . $sub->nama_subkriteria . "' => " . $sub->nilai . "\n";
    }
} else {
    echo "C2 not found\n";
}

$c3 = kriteria::where('nama_kriteria', 'like', '%Juara%')->where('nama_kriteria', '!=', 'Tingkatan Juara')->first();
// Or just check all criteria containing Juara
if ($c3) {
    echo "--- C3 (Juara ?) ---\n";
    $subs = Subkriteria::where('id_kriteria', $c3->id_kriteria)->get();
    foreach($subs as $sub) {
        echo "'" . $sub->nama_subkriteria . "' => " . $sub->nilai . "\n";
    }
} else {
    echo "C3 not found\n";
}
