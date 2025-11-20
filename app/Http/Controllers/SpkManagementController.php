<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spkkeputusan;
use App\Models\kriteria;
use App\Models\alternatif;
use App\Models\penilaian;
use App\Models\hasilakhir;
use App\Models\subkriteria;
use Illuminate\Support\Facades\DB;

class SpkManagementController extends Controller
{

    // =================================================================
    // >>> HASIL AKHIR (VIEW ONLY) <<<
    // =================================================================

    public function showHasilAkhir($idKeputusan)
    {
        $keputusan = spkkeputusan::findOrFail($idKeputusan);

        $hasil = hasilakhir::whereHas('alternatif', function ($query) use ($idKeputusan) {
            return $query->where('id_keputusan', $idKeputusan);
        })
            ->with('alternatif')
            ->orderBy('rangking', 'asc')
            ->get();

        return view('pages.admin.spk.hasil_akhir_view', [
            'keputusan' => $keputusan,
            'hasilData' => $hasil,
            'pageTitle' => 'Hasil Akhir Keputusan'
        ]);
    }
}