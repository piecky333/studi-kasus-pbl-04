@extends('layouts.admin')

@section('content')

<div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">Keputusan: {{ $keputusan->nama_keputusan }} | Nilai mentah ($X\_{ij}$) setiap alternatif terhadap kriteria.</p>
</header>

    <div class="flex justify-between items-center mb-4">
        {{-- Tautan EDIT MATRIKS --}}
        <a href="{{ route('admin.spk.manage.penilaian.edit', $keputusan->id_keputusan) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
            <i class="fas fa-edit mr-1"></i> Edit Semua Penilaian
        </a>
        <a href="{{ route('admin.spk.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Keputusan
        </a>
    </div>

    {{-- Catatan Penting --}}
    @if ($kriteria->isEmpty() || $alternatif->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p class="font-bold">Perhatian!</p>
            <p>Data Kriteria ({{ $kriteria->count() }}) dan/atau Alternatif ({{ $alternatif->count() }}) masih kosong. Harap lengkapi terlebih dahulu.</p>
        </div>
    @endif

    {{-- Tabel Matriks Penilaian --}}
    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    {{-- Sticky header untuk Alternatif --}}
                    <th class="sticky left-0 bg-gray-100 px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r w-1/4">Alternatif (A)</th>
                    @foreach($kriteria as $k)
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider" 
                        title="Bobot: {{ number_format($k->bobot_kriteria, 4) }}">
                        {{ $k->kode_kriteria }}
                        <span class="block font-normal text-gray-500">({{ $k->jenis_kriteria }})</span>
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
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                        @php
                            $nilai = null;
                            // Cek apakah ID alternatif ada di $penilaianMatrix dan ambil nilai kriteria yang sesuai
                            if (isset($penilaianMatrix[$alt->id_alternatif])) {
                                $nilai = $penilaianMatrix[$alt->id_alternatif]
                                    ->where('id_kriteria', $k->id_kriteria)
                                    ->first();
                            }
                        @endphp
                        
                        <span class="font-bold text-lg {{ $nilai ? 'text-blue-600' : 'text-red-400' }}">
                            {{ $nilai->nilai ?? '0' }}
                        </span>
                    </td>
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $kriteria->count() + 1 }}" class="px-6 py-4 text-center text-sm font-medium text-gray-500 bg-gray-50">
                        Tidak ada Alternatif yang tersedia. Harap tambahkan data Alternatif terlebih dahulu.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>


</div>
@endsection