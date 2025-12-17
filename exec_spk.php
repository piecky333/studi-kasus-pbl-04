<?php

use App\Models\spkkeputusan;
use App\Models\alternatif;
use App\Models\penilaian;
use App\Services\SpkCalculator;
use App\Services\SawService;
use App\Services\WeightService;
use App\Services\AhpService;
use App\Services\SpkDataService;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 1. Get Latest Keputusan
$keputusan = spkkeputusan::latest('id_keputusan')->first();
if (!$keputusan) {
    echo "No Keputusan found.\n";
    exit;
}
echo "Processing Keputusan ID: " . $keputusan->id_keputusan . "\n";

// 2. Sync Scores (Logic replicated from PenilaianController)
$kriteriaList = $keputusan->kriteria;
$alternatifList = alternatif::where('id_keputusan', $keputusan->id_keputusan)->with('mahasiswa.prestasi')->get();

echo "Syncing Scores...\n";
foreach ($alternatifList as $alternatif) {
    $mahasiswa = $alternatif->mahasiswa;
    $prestasiValid = $mahasiswa->prestasi; // All seeded are valid

    foreach ($kriteriaList as $kriteria) {
        $name = strtolower($kriteria->nama_kriteria);
        $value = 0;

        if ($kriteria->sumber_data == 'Mahasiswa') {
            if (str_contains($name, 'ipk')) $value = $mahasiswa->ipk;
        } elseif ($kriteria->sumber_data == 'Prestasi') {
            if (str_contains($name, 'tingkat')) {
                $value = SpkCalculator::calculateTingkatScore($prestasiValid, $kriteria);
            } elseif (str_contains($name, 'juara')) {
                $value = SpkCalculator::calculateJuaraScore($prestasiValid, $kriteria);
            } elseif (str_contains($name, 'jumlah')) {
                $value = $prestasiValid->count();
            }
        }
        
        penilaian::updateOrCreate(
            ['id_alternatif' => $alternatif->id_alternatif, 'id_kriteria' => $kriteria->id_kriteria],
            ['nilai' => $value]
        );
    }
}
echo "Sync Complete.\n";

// 3. Run SAW Calculation
$sawService = new SawService(new SpkDataService(), new WeightService(new AhpService()));
$results = $sawService->calculateProcessData($keputusan->id_keputusan);

echo "SAW Calculation Results:\n";
echo str_pad("Alternatif", 20) . str_pad("Score", 10) . str_pad("Rank", 5) . "\n";
echo str_repeat("-", 35) . "\n";

foreach ($results['ranking_results'] as $res) {
    echo str_pad($res['nama'], 20) . str_pad(number_format($res['final_score'], 4), 10) . str_pad($res['rank'], 5) . "\n";
}
