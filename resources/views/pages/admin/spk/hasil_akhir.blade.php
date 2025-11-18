@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
<header class="bg-white shadow-lg rounded-t-xl mb-6 p-5">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">Hasil Akhir Ranking: {{ $keputusan->nama_keputusan }}</h1>
<p class="mt-1 text-sm text-gray-500">Hasil perhitungan final, diurutkan berdasarkan skor akhir preferensi ($V\_i$).</p>
</header>

<div class="bg-white shadow-xl overflow-hidden sm:rounded-b-lg p-6">
    
    {{-- Pesan Sukses (Misalnya setelah RUN CALCULATION) --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Hasil Akhir --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-red-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider w-1/6">Peringkat</th>
                    <th class="px-6 py-3 text-left text-sm font-bold uppercase tracking-wider">Nama Alternatif</th>
                    <th class="px-6 py-3 text-center text-sm font-bold uppercase tracking-wider w-1/4">Skor Akhir (V)</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($hasilData as $hasil)
                <tr class="hover:bg-yellow-50 {{ $hasil->rangking == 1 ? 'bg-yellow-100 font-bold' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-center text-xl text-red-600">
                        @if($hasil->rangking == 1)
                            <i class="fas fa-trophy text-yellow-500 mr-2"></i> 
                        @endif
                        {{ $hasil->rangking }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $hasil->alternatif->nama_alternatif ?? 'Alternatif Dihapus' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-lg text-gray-700">
                        {{ number_format($hasil->skor_akhir, 4) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-500 bg-gray-50">
                        Belum ada hasil akhir yang disimpan. Silakan jalankan perhitungan melalui menu Proses Perhitungan SAW.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6 flex justify-end">
        <a href="{{ route('admin.spk.calculate.proses', $keputusan->id_keputusan) }}" 
           class="text-sm text-indigo-600 hover:text-indigo-800">
            <i class="fas fa-eye mr-1"></i> Lihat Detail Proses Perhitungan
        </a>
    </div>
</div>


</div>
@endsection