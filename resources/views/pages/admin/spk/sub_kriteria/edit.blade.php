@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">
Mengedit skala nilai untuk Kriteria: <span class="font-medium text-blue-600">{{ $kriteria->nama_kriteria }} ({{ $kriteria->kode_kriteria }})</span>
</p>
</header>

    {{-- Menampilkan Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm" role="alert">
            <strong class="font-bold">Validasi Gagal!</strong> Harap perbaiki kesalahan berikut:
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form action menunjuk ke route update Sub Kriteria --}}
    <form action="{{ route('admin.spk.manage.subkriteria.update', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria, 'idSubKriteria' => $subkriteria->id_subkriteria]) }}" method="POST">
        @csrf
        @method('PUT') {{-- PENTING: Gunakan method PUT/PATCH untuk update --}}

        <div class="space-y-4">
            
            {{-- 1. Nama Sub Kriteria / Deskripsi --}}
            <div>
                <label for="nama_subkriteria" class="block text-sm font-medium text-gray-700">Nama Sub Kriteria / Deskripsi</label>
                <input type="text" name="nama_subkriteria" id="nama_subkriteria" 
                       value="{{ old('nama_subkriteria', $subkriteria->nama_subkriteria) }}" 
                       placeholder="Contoh: Sangat Baik, Jarak < 5 km, Lulus Tes"
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border @error('nama_subkriteria') border-red-500 @enderror">
                @error('nama_subkriteria')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- 2. Nilai Skala --}}
            <div>
                <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai Skala (Numerik)</label>
                <input type="number" step="0.01" name="nilai" id="nilai" 
                       value="{{ old('nilai', $subkriteria->nilai) }}" 
                       placeholder="Contoh: 5, 4, 3, dst."
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border @error('nilai') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Nilai ini akan dikonversi dan digunakan dalam perhitungan SAW/TOPSIS.</p>
                @error('nilai')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.spk.manage.subkriteria', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            {{-- Tombol Submit, fokus biru --}}
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                <i class="fas fa-sync-alt mr-2"></i> Perbarui Sub Kriteria
            </button>
        </div>
    </form>
</div>


</div>
@endsection