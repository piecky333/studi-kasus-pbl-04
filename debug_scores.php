<?php

use App\Models\kriteria;
use App\Models\HasilAkhir;
use App\Models\alternatif;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CURRENT WEIGHTS (BOBOT) ===\n";
$kriterias = kriteria::all();
foreach ($kriterias as $k) {
    echo "{$k->kode_kriteria} ({$k->nama_kriteria}): {$k->bobot_kriteria}\n";
}

echo "\n=== CURRENT SCORES (HASIL AKHIR) ===\n";
$results = HasilAkhir::with('alternatif')->orderBy('rangking')->get();
foreach ($results as $res) {
    $nama = $res->alternatif->nama_alternatif ?? 'Unknown';
    echo "Rank {$res->rangking}: {$nama} - Score: {$res->skor_akhir}\n";
}
