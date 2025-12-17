@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg p-8">
        
        {{-- Header Halaman Form --}}
        <header class="mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
            <p class="text-sm text-gray-600">Untuk Kriteria: <span class="font-semibold text-indigo-600">{{ $kriteria->kode_kriteria }} - {{ $kriteria->nama_kriteria }}</span></p>
        </header>

        {{-- Menampilkan pesan error jika validasi input gagal --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                <strong class="font-bold">Oops! Ada masalah dengan input Anda:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form untuk menyimpan sub kriteria baru --}}
        <form action="{{ route('admin.spk.kriteria.subkriteria.store', [$keputusan->id_keputusan, $kriteria->id_kriteria]) }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                
                {{-- Input Teks: Nama Sub Kriteria --}}
                <div>
                    <label for="nama_subkriteria" class="block text-sm font-medium text-gray-700 mb-1">Nama Sub Kriteria</label>
                    <input type="text" name="nama_subkriteria" id="nama_subkriteria" 
                           value="{{ old('nama_subkriteria') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm 
                           @error('nama_subkriteria') border-red-500 @enderror"
                           placeholder="Contoh: Sangat Baik, Cukup, Kurang" required>
                    @error('nama_subkriteria')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Number: Nilai --}}
                <div>
                    <label for="nilai" class="block text-sm font-medium text-gray-700 mb-1">Nilai Bobot</label>
                    <input type="number" name="nilai" id="nilai" step="any"
                           value="{{ old('nilai') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm 
                           @error('nilai') border-red-500 @enderror"
                           placeholder="Contoh: 5, 4, 3..." required>
                    <p class="mt-1 text-xs text-gray-500">
                        *Nilai ini akan digunakan sebagai nilai aktual ($X_{ij}$) dalam Matriks Penilaian.
                    </p>
                    @error('nilai')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Bagian Tombol: Simpan atau Batal --}}
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.spk.kriteria.subkriteria.index', [$keputusan->id_keputusan, $kriteria->id_kriteria]) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150 shadow-sm">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection