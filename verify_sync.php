<?php

use App\Http\Controllers\Spk\PenilaianController;
use Illuminate\Http\Request;
use App\Models\spkkeputusan;

$request = new Request();
$keputusan = spkkeputusan::first();
echo "Testing Sync for Keputusan ID: " . $keputusan->id_keputusan . "\n";

$controller = new class($request) extends PenilaianController {
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
