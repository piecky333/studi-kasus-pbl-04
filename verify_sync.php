<?php

use App\Http\Controllers\Spk\PenilaianController;
// use Illuminate\Http\Request; // Not needed for this script context if we just call sync
use App\Models\spkkeputusan;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// The original script instantiated Illuminate\Http\Request, but the anonymous class
// explicitly skipped the parent constructor, making the $request object unused.
// As per the instruction to remove Illuminate\Http\Request usage, this line is removed.
// $request = new Request();

$keputusan = spkkeputusan::first();
echo "Testing Sync for Keputusan ID: " . $keputusan->id_keputusan . "\n";

$controller = new class(null) extends PenilaianController {
    public function __construct($req) {} // Skip parent
    public function setProps($id, $k) {
        $this->idKeputusan = $id;
        $this->keputusan = $k;
        $this->middleware = []; // prevent middleware issues
    }
};

$controller->setProps($keputusan->id_keputusan, $keputusan);

try {
    $controller->syncScores();
    echo "Result: " . session('success') . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
