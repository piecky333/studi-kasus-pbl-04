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
            <h4 class="font-semibold text-blue-800 mb-3">Bobot Kriteria Akhir (Wj)</h4>
            <div class="flex flex-wrap gap-4">
                @foreach ($calculationData['criteria_metadata'] as $kriteria)
                    @php
                        $kode = $kriteria->kode_kriteria;
                        $bobot = $calculationData['weights'][$kode] ?? 0;
                    @endphp
                    <div class="p-2 bg-white rounded-md shadow-sm border border-blue-300 text-center">
                        <p class="text-xs font-medium text-blue-600">{{ $kriteria->nama_kriteria }} ({{ $kode }})</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($bobot, 4) }}</p>
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
                        <th class="px-3 py-2 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider bg-indigo-100 border-l">Vi (Total Preferensi)</th>
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
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-bold font-mono {{ $isBest ? 'text-indigo-800' : 'text-indigo-600' }} bg-indigo-100 border-l">
                                {{ number_format($rankResult['final_score'], 4) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


</div>

@endsection