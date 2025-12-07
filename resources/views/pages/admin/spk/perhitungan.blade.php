@extends('layouts.app') 

@section('content')
<div class="container-fluid">
    {{-- Bagian Header dan Card Konten --}}
    <h3>Matriks Ternormalisasi dan Terbobot</h3>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th rowspan="2">Alternatif</th>
                    @foreach($weights as $kode => $bobot)
                    <th colspan="2" class="text-center">{{ $kode }}</th>
                    @endforeach
                    <th rowspan="2">Nilai Preferensi (V)</th>
                </tr>
                <tr>
                    @foreach($weights as $kode => $bobot)
                    <th class="text-center bg-light text-success" title="Tipe: {{ $criteriaType[$kode] ?? 'N/A' }}">Rij (Normalisasi)</th>
                    <th class="text-center bg-light text-primary" title="Bobot: {{ round($bobot, 4) }}">Wj * Rij (Terbobot)</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($perhitunganData as $data)
                <tr>
                    <td>{{ $data['nama'] }}</td>
                    @foreach($weights as $kode => $bobot)
                    @php
                        $rij = $data['normalized_scores'][$kode] ?? 0;
                        $w_rij = $rij * $bobot;
                    @endphp
                    <td class="text-right">{{ number_format($rij, 4) }}</td>
                    <td class="text-right">{{ number_format($w_rij, 4) }}</td>
                    @endforeach
                    <td class="text-right font-weight-bold text-danger">
                        {{ number_format($data['nilai_preferensi_V'], 4) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection