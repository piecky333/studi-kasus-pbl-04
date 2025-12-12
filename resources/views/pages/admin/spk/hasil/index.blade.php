@extends('pages.admin.spk.detail_base', ['currentTab' => 'hasil', 'idKeputusan' => $idKeputusan, 'keputusan' => $keputusan])

@section('detail_content')

<div class="bg-white rounded-lg">
    
    {{-- Menampilkan notifikasi sukses atau error --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm font-semibold" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm font-semibold" role="alert">
            {{ session('error') }}
            {{-- Menampilkan pesan error dari Exception jika data perhitungan tidak lengkap --}}
        </div>
    @endif

            {{-- 
                =============================================
                HASIL RINGKASAN PERANGKINGAN (Diletakkan di Awal)
                =============================================
            --}}
            {{-- Tabel Hasil Perangkingan Akhir --}}
            <h4 class="text-lg font-bold text-gray-800 mb-3 flex items-center mt-4">
                Hasil Perangkingan Akhir
            </h4>
            <p class="text-sm text-gray-600 mb-4">Urutan peringkat alternatif berdasarkan nilai total preferensi (Vi) tertinggi ke terendah.</p>
            
            <div class="overflow-x-auto border border-gray-200 rounded-lg mb-8 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Alternatif</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-32">Peringkat</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider w-40">Nilai Akhir (Vi)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($calculationData['ranking_results'] as $index => $rankResult)
                            @if($loop->iteration > 5) @break @endif
                            @php
                                $isWinner = $rankResult['rank'] == 1;
                                $isTop3 = $rankResult['rank'] <= 3;
                            @endphp
                            <tr class="{{ $isWinner ? 'bg-green-50' : 'hover:bg-gray-50' }} transition-colors">
                                {{-- Nama Alternatif --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($isWinner)
                                            <div class="flex-shrink-0 mr-3">
                                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <i class="fas fa-crown text-green-600 text-sm"></i>
                                                </div>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium {{ $isWinner ? 'text-green-900' : 'text-gray-900' }}">
                                                {{ $rankResult['nama'] }}
                                            </div>
                                            @if($isWinner)
                                                <div class="text-xs text-green-600">Rekomendasi Utama</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Peringkat --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($isWinner)
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-600 text-white font-bold shadow-sm ring-2 ring-green-100">
                                            1
                                        </span>
                                    @elseif($isTop3)
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-600 text-white font-bold shadow-sm">
                                            {{ $rankResult['rank'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-600 font-bold border border-gray-200">
                                            {{ $rankResult['rank'] }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Nilai Akhir --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full {{ $isWinner ? 'bg-green-100 text-green-800' : 'bg-indigo-50 text-indigo-700' }} border {{ $isWinner ? 'border-green-200' : 'border-indigo-100' }}">
                                        {{ number_format($rankResult['final_score'], 5) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr class="my-8 border-t border-gray-200">

            {{-- 
                =============================================
                DETAIL PERHITUNGAN (Bobot, Matriks, Normalisasi)
                =============================================
            --}}

            <div class="mb-8">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                    Bobot Kriteria Akhir (Wj)
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach ($calculationData['criteria_metadata'] as $kriteria)
                        @php
                            $kode = $kriteria->kode_kriteria;
                            $bobot = $calculationData['weights'][$kode] ?? 0;
                            $isBenefit = $kriteria->jenis_kriteria == 'Benefit';
                        @endphp
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 transition duration-300 hover:shadow-md relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="fas {{ $isBenefit ? 'fa-arrow-up text-green-500' : 'fa-arrow-down text-red-500' }} text-3xl"></i>
                            </div>
                            <div class="relative z-10">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $isBenefit ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $kode }}
                                    </span>
                                </div>
                                @php
                                    $namaDisplay = $kriteria->nama_kriteria;
                                    if ($kriteria->sumber_data !== 'Manual' && isset($columnMap[$kriteria->sumber_data][$namaDisplay])) {
                                        $namaDisplay = $columnMap[$kriteria->sumber_data][$namaDisplay] . " (" . $kriteria->sumber_data . ")";
                                    }
                                @endphp
                                <p class="text-xs text-gray-500 h-8 line-clamp-2 leading-tight mb-1" title="{{ $namaDisplay }}">
                                    {{ $namaDisplay }}
                                </p>
                                <p class="text-xl font-bold text-gray-800">
                                    {{ number_format($bobot, 4) }}
                                </p>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        
        {{-- Tabel Matriks Keputusan Awal (Xij) --}}
        <h4 class="text-lg font-bold text-gray-800 mb-3">1. Matriks Keputusan Mentah (Xij)</h4>
        <p class="text-sm text-gray-600 mb-3">Nilai mentah yang diinput: Alternatif vs Kriteria.</p>
        <div class="overflow-x-auto border rounded-lg mb-8">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border-r">Alternatif</th>
                        @foreach ($calculationData['criteria_metadata'] as $k)
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-600 uppercase tracking-wider" title="Jenis: {{ $k->jenis_kriteria }}">
                                {{ $k->kode_kriteria }}
                                <br><span class="font-normal text-xs italic">({{ $k->jenis_kriteria }})</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($calculationData['raw_data'] as $rawAlt)
                        <tr>
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50 border-r">{{ $rawAlt['nama'] }}</td>
                            @foreach ($calculationData['criteria_metadata'] as $k)
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-mono">
                                    {{ $rawAlt[$k->kode_kriteria] }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tabel Matriks Ternormalisasi (Rij) --}}
        <h4 class="text-lg font-bold text-gray-800 mb-3">2. Matriks Normalisasi (Rij)</h4>
        <p class="text-sm text-gray-600 mb-3">Matriks yang sudah dinormalisasi berdasarkan jenis kriteria (Benefit/Cost).</p>
        <div class="overflow-x-auto border rounded-lg mb-8">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider border-r">Alternatif</th>
                        @foreach ($calculationData['criteria_metadata'] as $k)
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                {{ $k->kode_kriteria }}
                            </th>
                        @endforeach
                        <th class="px-3 py-2 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-l">Vi (Total Preferensi)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($calculationData['ranking_results'] as $rankResult)
                        @php
                            $isBest = $rankResult['rank'] == 1;
                        @endphp
                        <tr class="{{ $isBest ? 'bg-yellow-50' : '' }}">
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 {{ $isBest ? 'bg-yellow-100 font-bold' : 'bg-gray-50' }} border-r">
                                {{ $rankResult['nama'] }}
                            </td>
                            @foreach ($calculationData['criteria_metadata'] as $k)
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-mono">
                                    {{ number_format($rankResult['rij_data'][$k->kode_kriteria] ?? 0, 4) }}
                                </td>
                            @endforeach
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-bold font-mono {{ $isBest ? 'text-indigo-800' : 'text-gray-800' }} border-l">
                                {{ number_format($rankResult['final_score'], 4) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

</div>

@endsection