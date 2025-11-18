@extends('layouts.admin')

@section('content')

<div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">Keputusan: {{ $keputusan->nama_keputusan }} | Isi atau perbarui nilai mentah ($X\_{ij}$) setiap alternatif.</p>
</header>

    {{-- Pesan Error Global --}}
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <strong class="font-bold">Error:</strong> {{ session('error') }}
        </div>
    @endif
    
    {{-- Pesan Peringatan --}}
    @if ($kriteria->isEmpty() || $alternatif->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p class="font-bold">Perhatian!</p>
            <p>Data Kriteria ({{ $kriteria->count() }}) atau Alternatif ({{ $alternatif->count() }}) masih kosong. Anda tidak dapat mengisi matriks saat ini.</p>
        </div>
    @endif

    {{-- FORM MASS UPDATE --}}
    <form action="{{ route('admin.spk.manage.penilaian.update', $keputusan->id_keputusan) }}" method="POST">
        @csrf
        
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="sticky left-0 bg-gray-100 px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r w-1/4">Alternatif (A)</th>
                        @foreach($kriteria as $k)
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider" 
                            title="Kriteria: {{ $k->nama_kriteria }} ({{ $k->jenis_kriteria }})">
                            {{ $k->kode_kriteria }}
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
                        
                        {{-- Loop Input untuk setiap Kriteria --}}
                        @foreach($kriteria as $k)
                        <td class="px-4 py-2 whitespace-nowrap text-sm text-center">
                            @php
                                $key = $alt->id_alternatif . '_' . $k->id_kriteria;
                                $nilai_saat_ini = $penilaianData->has($key) ? $penilaianData->get($key)->nilai : '';
                            @endphp

                            <input type="number" 
                                   step="0.01" 
                                   name="penilaian[{{ $alt->id_alternatif }}][{{ $k->id_kriteria }}]"
                                   value="{{ old('penilaian.' . $alt->id_alternatif . '.' . $k->id_kriteria, $nilai_saat_ini) }}"
                                   placeholder="Nilai"
                                   required
                                   class="w-full text-center text-sm border border-gray-300 rounded-md p-1 focus:border-blue-500 focus:ring-blue-500 @error('penilaian.' . $alt->id_alternatif . '.' . $k->id_kriteria) border-red-500 @enderror">
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $kriteria->count() + 1 }}" class="px-6 py-4 text-center text-sm font-medium text-gray-500 bg-gray-50">
                            Tidak ada Alternatif yang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.spk.manage.penilaian', $keputusan->id_keputusan) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Batalkan Edit
            </a>
            {{-- Tombol Submit --}}
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                <i class="fas fa-save mr-2"></i> Simpan Matriks Penilaian
            </button>
        </div>
    </form>

</div>


</div>
@endsection