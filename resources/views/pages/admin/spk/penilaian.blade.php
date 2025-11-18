@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
<header class="bg-white shadow rounded-t-xl mb-6 p-5">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">Matriks Penilaian (Xij): {{ $keputusan->nama_keputusan }}</h1>
<p class="mt-1 text-sm text-gray-500">Nilai mentah setiap alternatif terhadap kriteria. Data ini digunakan untuk normalisasi.</p>
</header>

<div class="bg-white shadow-xl overflow-x-auto sm:rounded-b-lg p-6">
    
    {{-- Tombol Aksi --}}
    <div class="mb-4">
        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
            <i class="fas fa-edit mr-1"></i> Edit Semua Penilaian
        </a>
    </div>

    {{-- Tabel Matriks Penilaian --}}
    <div class="min-w-full inline-block align-middle">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="sticky left-0 bg-gray-100 px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r">Alternatif (A)</th>
                    @foreach($kriteria as $k)
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                        {{ $k->kode_kriteria }} ({{ $k->nama_kriteria }})
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($alternatif as $alt)
                <tr class="hover:bg-gray-50">
                    <td class="sticky left-0 bg-white px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r">
                        {{ $alt->nama_alternatif }}
                    </td>
                    
                    {{-- Loop untuk setiap Kriteria --}}
                    @foreach($kriteria as $k)
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        @php
                            // Mencari nilai penilaian untuk Alternatif dan Kriteria saat ini
                            $nilai = $penilaianMatrix[$alt->id_alternatif]
                                ->where('id_kriteria', $k->id_kriteria)
                                ->first();
                        @endphp
                        
                        <span class="font-bold text-blue-600">
                            {{ $nilai->nilai ?? '0' }}
                        </span>
                    </td>
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $kriteria->count() + 1 }}" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-500 bg-gray-50">
                        Tidak ada data penilaian yang ditemukan. Pastikan Alternatif dan Kriteria sudah dibuat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>


</div>
@endsection