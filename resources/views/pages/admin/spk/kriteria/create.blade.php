@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">Untuk Keputusan: <span class="font-medium text-indigo-600">{{ $keputusan->nama_keputusan }}</span></p>
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

    {{-- Form action menunjuk ke route store yang baru --}}
    <form action="{{ route('admin.spk.manage.kriteria.store', $keputusan->id_keputusan) }}" method="POST">
        @csrf

        <div class="space-y-4">
            
            {{-- 1. Kode Kriteria --}}
            <div>
                <label for="kode_kriteria" class="block text-sm font-medium text-gray-700">Kode Kriteria (Contoh: C1)</label>
                <input type="text" name="kode_kriteria" id="kode_kriteria" 
                       value="{{ old('kode_kriteria') }}" 
                       placeholder="C1, C2, dst."
                       required
                       class="mt-1 block w-full rounded-md border-gray-300  focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border @error('kode_kriteria') border-red-500 @enderror">
                @error('kode_kriteria')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- 2. Nama Kriteria --}}
            <div>
                <label for="nama_kriteria" class="block text-sm font-medium text-gray-700">Nama Kriteria</label>
                <input type="text" name="nama_kriteria" id="nama_kriteria" 
                       value="{{ old('nama_kriteria') }}" 
                       placeholder="Contoh: Nilai Akademik"
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border @error('nama_kriteria') border-red-500 @enderror">
                @error('nama_kriteria')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- 3. Jenis Kriteria --}}
            <div>
                <label for="jenis_kriteria" class="block text-sm font-medium text-gray-700">Jenis Kriteria</label>
                <select id="jenis_kriteria" name="jenis_kriteria" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border @error('jenis_kriteria') border-red-500 @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Benefit" {{ old('jenis_kriteria') == 'Benefit' ? 'selected' : '' }}>Benefit (Makin Besar Makin Baik)</option>
                    <option value="Cost" {{ old('jenis_kriteria') == 'Cost' ? 'selected' : '' }}>Cost (Makin Kecil Makin Baik)</option>
                </select>
                @error('jenis_kriteria')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- 4. Bobot Kriteria --}}
            <div>
                <label for="bobot_kriteria" class="block text-sm font-medium text-gray-700">Bobot Awal (Wj)</label>
                <input type="number" step="0.0001" name="bobot_kriteria" id="bobot_kriteria" 
                       value="{{ old('bobot_kriteria', 0.00) }}" 
                       placeholder="Contoh: 0.25 (Gunakan titik desimal)"
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border @error('bobot_kriteria') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">Catatan: Bobot total semua kriteria harus mendekati 1.00. Bobot ini bisa dihitung ulang menggunakan AHP.</p>
                @error('bobot_kriteria')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.spk.manage.kriteria', $keputusan->id_keputusan) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                Kembali
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                <i class="fas fa-save mr-2"></i> Simpan Kriteria
            </button>
        </div>
    </form>
</div>


</div>
@endsection