@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
<header class="bg-white shadow-lg rounded-t-xl mb-6">
<div class="px-4 py-5 sm:px-6">
<h1 class="text-3xl font-extrabold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">Isi detail keputusan yang akan dievaluasi. Langkah selanjutnya adalah menambahkan Kriteria.</p>
</div>
</header>

<div class="bg-white shadow-xl overflow-hidden sm:rounded-b-lg p-8">
    
    {{-- Menampilkan Error Validasi --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <strong class="font-bold">Oops!</strong> Ada masalah dengan input Anda:
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.spk.store') }}" method="POST">
        @csrf

        <div class="space-y-6">
            
            {{-- Nama Keputusan --}}
            <div>
                <label for="nama_keputusan" class="block text-sm font-medium text-gray-700">Nama Keputusan</label>
                <div class="mt-1">
                    <input type="text" name="nama_keputusan" id="nama_keputusan" 
                           value="{{ old('nama_keputusan') }}" 
                           placeholder="Contoh: Seleksi Penerima Beasiswa Tahun 2025"
                           required
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('nama_keputusan') border-red-500 @enderror">
                </div>
                @error('nama_keputusan')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Metode SPK --}}
            <div>
                <label for="metode_yang_digunakan" class="block text-sm font-medium text-gray-700">Metode SPK</label>
                <div class="mt-1">
                    <select id="metode_yang_digunakan" name="metode_yang_digunakan" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border @error('metode_yang_digunakan') border-red-500 @enderror">
                        <option value="">-- Pilih Metode --</option>
                        <option value="SAW" {{ old('metode_yang_digunakan') == 'SAW' ? 'selected' : '' }}>SAW (Simple Additive Weighting)</option>
                        <option value="TOPSIS" {{ old('metode_yang_digunakan') == 'TOPSIS' ? 'selected' : '' }}>TOPSIS</option>
                        <option value="AHP" {{ old('metode_yang_digunakan') == 'AHP' ? 'selected' : '' }}>AHP (Analytic Hierarchy Process)</option>
                        <option value="AHP-SAW" {{ old('metode_yang_digunakan') == 'AHP-SAW' ? 'selected' : '' }}>AHP + SAW (Kombinasi)</option>
                    </select>
                </div>
                @error('metode_yang_digunakan')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
        </div>

        <div class="mt-8 flex justify-end space-x-3">
            <a href="{{ route('admin.spk.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
            Simpan & Lanjutkan
            </button>
        </div>
    </form>
</div>


</div>
@endsection