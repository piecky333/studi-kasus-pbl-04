@extends('pages.admin.spk.detail_base', ['currentTab' => 'hasil', 'idKeputusan' => $idKeputusan, 'keputusan' => $keputusan])

@section('detail_content')

<div class="bg-white rounded-lg">
    
    {{-- ALERT STATUS --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm font-semibold" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm font-semibold" role="alert">
            {{ session('error') }}
            {{-- Pesan error di sini berasal dari Exception jika data tidak lengkap --}}
        </div>
    @endif

    <header class="flex justify-between items-center mb-6 border-b pb-4">
        <h3 class="text-xl font-bold text-gray-800">Hasil Akhir dan Perangkingan (SAW)</h3>
    </header>

    {{-- ========================================================== --}}
    {{-- BAGIAN 1: TOMBOL PERHITUNGAN ULANG --}}
    {{-- ========================================================== --}}
    <div class="p-4 rounded-lg mb-6 shadow-md border-t-4 {{ $isReady ? 'border-indigo-600 bg-indigo-50' : 'border-red-600 bg-red-50' }}">
        <div class="flex justify-between items-center">
            
            <div class="flex-1">
                @if ($isReady)
                    <p class="text-indigo-800 font-semibold mb-1">Status Data: <span class="text-indigo-600">Siap Dihitung</span></p>
                    <p class="text-sm text-indigo-700">Semua Kriteria, Bobot (AHP), dan Nilai Alternatif sudah lengkap. Anda dapat menjalankan perhitungan SAW.</p>
                @else
                    <p class="text-red-800 font-semibold mb-1">Status Data: <span class="text-red-600">Tidak Lengkap</span></p>
                    <p class="text-sm text-red-700">Harap periksa Tab Kriteria dan Alternatif. Perhitungan diblokir karena ada data yang hilang atau Bobot AHP belum konsisten.</p>
                @endif
            </div>

            {{-- Tombol Trigger Perhitungan --}}
            <form action="{{ route('admin.spk.hasil.run', $idKeputusan) }}" method="POST">
                @csrf
                <button type="submit" 
                    {{-- Tombol hanya aktif jika $isReady bernilai true --}}
                    {{ $isReady ? '' : 'disabled' }}
                    class="ml-4 inline-flex items-center font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg text-sm 
                        {{ $isReady ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                    <i class="fas fa-calculator mr-2"></i> Jalankan Perhitungan SAW
                </button>
            </form>
        </div>
    </div>


    {{-- ========================================================== --}}
    {{-- BAGIAN 2: HASIL AKHIR DAN RANKING --}}
    {{-- ========================================================== --}}
    <h3 class="text-xl font-bold text-gray-800 mb-4">Peringkat Akhir Alternatif</h3>

    @if ($rankingResults->isEmpty())
        <div class="p-6 text-center bg-gray-50 rounded-lg border border-gray-200 text-gray-600">
            <i class="fas fa-chart-line text-4xl mb-3 text-gray-400"></i>
            <p>Belum ada hasil perhitungan yang tersimpan. Jalankan perhitungan di atas.</p>
        </div>
    @else
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm mb-10">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-1/12">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider w-5/12">Nama Alternatif</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-3/12">Nilai Preferensi (Vi)</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider w-3/12">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($rankingResults as $result)
                        <tr class="{{ $result->ranking == 1 ? 'bg-yellow-50 font-semibold' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center {{ $result->ranking <= 3 ? 'text-indigo-600 text-lg font-extrabold' : 'text-gray-900' }}">
                                {{ $result->ranking }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                {{ $result->alternatif->nama_alternatif ?? 'Alternatif Dihapus' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-mono text-lg text-indigo-700">
                                {{ number_format($result->nilai_preferensi, 4) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                @if ($result->ranking == 1)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-400 text-gray-800">
                                        TERBAIK
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Terhitung
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    
    {{-- ========================================================== --}}
    {{-- BAGIAN 3: DETAIL PROSES (Matriks Xij dan Rij) --}}
    {{-- Hanya tampilkan jika $calculationData tersedia --}}
    {{-- ========================================================== --}}
    @if ($calculationData)
        <h3 class="text-xl font-bold text-gray-800 mb-4 mt-10 border-t pt-6">Detail Proses Perhitungan</h3>

        {{-- RINGKASAN BOBOT KRITERIA --}}
        <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
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
        
        {{-- MATRIKS MENTAH (Xij) --}}
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

        {{-- MATRIKS NORMALISASI (Rij) --}}
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