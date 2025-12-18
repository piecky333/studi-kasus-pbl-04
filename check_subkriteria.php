<?php

use App\Models\SubKriteria;
use App\Models\kriteria;

$c2 = kriteria::where('nama_kriteria', 'Tingkatan Juara')->first();
if ($c2) {
    echo "--- C2 (Tingkatan Juara) ---\n";
    foreach($c2->subkriteria as $sub) {
        echo $sub->nama_subkriteria . " => " . $sub->nilai . "\n";
    }
} else {
    echo "C2 not found\n";
}

$c3 = kriteria::where('nama_kriteria', 'Juara')->first();
if ($c3) {
    echo "--- C3 (Juara) ---\n";
    foreach($c3->subkriteria as $sub) {
        echo $sub->nama_subkriteria . " => " . $sub->nilai . "\n";
    }
} else {
    echo "C3 not found\n";
}
