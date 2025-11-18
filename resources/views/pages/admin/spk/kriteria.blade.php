@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">Keputusan: {{ $keputusan->nama_keputusan }} | Metode: {{ $keputusan->metode_yang_digunakan }}</p>
</header>

    <div class="flex justify-between items-center mb-4">
        <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
            <i class="fas fa-plus mr-1"></i> Tambah Kriteria Utama
        </a>
    </div>

    {{-- Tabel Kriteria Utama --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">Nama Kriteria</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Bobot (W)</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Jenis</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-4/12">Sub Kriteria / Skala</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($kriteriaData as $kriteria)
                <tr class="hover:bg-gray-100">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $kriteria->kode_kriteria }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $kriteria->nama_kriteria }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-indigo-600 font-medium">{{ number_format($kriteria->bobot_kriteria, 4) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ strtolower($kriteria->jenis_kriteria) == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $kriteria->jenis_kriteria }}
                        </span>
                    </td>
                    <td class="px-6 py-1 text-sm text-gray-700">
                        {{-- Tampilkan Sub Kriteria --}}
                        @if(isset($kriteria->subKriteria) && $kriteria->subKriteria->count() > 0)
                            <ul class="list-disc list-inside space-y-0.5 ml-2">
                                @foreach($kriteria->subKriteria as $sub)
                                    <li class="text-xs">[{{ $sub->nilai_skala }}] {{ $sub->deskripsi }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-xs text-gray-400">Nilai Langsung (Tidak berskala)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-2 text-xs">Edit</a>
                        <a href="#" class="text-red-600 hover:text-red-900 text-xs">Hapus</a>
                        <a href="#" class="text-blue-600 hover:text-blue-900 ml-2 text-xs">Atur Sub</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm font-medium text-gray-500">
                        Belum ada Kriteria yang ditambahkan untuk keputusan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


</div>
@endsection