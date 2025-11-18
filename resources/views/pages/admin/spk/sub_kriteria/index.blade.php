@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">Keputusan: {{ $keputusan->nama_keputusan }} | Kriteria Induk: <span class="font-medium text-indigo-600">{{ $kriteria->nama_kriteria }} ({{ $kriteria->kode_kriteria }})</span></p>
</header>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        {{-- Tombol untuk menambah sub kriteria baru --}}
        <a href="{{ route('admin.spk.manage.subkriteria.create', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
            <i class="fas fa-plus mr-1"></i> Tambah Sub Kriteria
        </a>
        <a href="{{ route('admin.spk.manage.kriteria', $keputusan->id_keputusan) }}" class="text-sm text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Kriteria
        </a>
    </div>

    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-700 p-4 border-b bg-gray-50">Daftar Skala Nilai untuk {{ $kriteria->nama_kriteria }}</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-7/12">Nama Sub Kriteria / Deskripsi</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">Nilai</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($subKriteriaData as $index => $sub)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $sub->nama_subkriteria }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-indigo-600 font-medium text-lg">{{ $sub->nilai }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                        {{-- Tombol Edit --}}
                        <a href="{{ route('admin.spk.manage.subkriteria.edit', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria, 'idSubKriteria' => $sub->id_subkriteria]) }}" 
                           class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                        
                        {{-- Tombol Hapus --}}
                        <form action="{{ route('admin.spk.manage.subkriteria.destroy', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria, 'idSubKriteria' => $sub->id_subkriteria]) }}" 
                              method="POST" 
                              class="inline" 
                              onsubmit="return confirm('APAKAH ANDA YAKIN? Menghapus sub kriteria ini akan mempengaruhi semua penilaian yang menggunakan skala ini!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm font-medium text-gray-500 bg-gray-50">
                        Kriteria ini belum memiliki Sub Kriteria. Silakan tambahkan skala nilai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


</div>
@endsection