@extends('layouts.admin')

@section('content')

    <div class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-8">
            <header class="mb-8 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
                <p class="text-sm text-gray-600">Anda sedang mengedit kriteria: 
                    <span class="font-medium text-indigo-600">{{ $kriteria->kode_kriteria }} - {{ $kriteria->nama_kriteria }}</span> 
                    untuk Keputusan: <span class="font-medium text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span>
                </p>
            </header>

            {{-- Menampilkan pesan error validasi dari Laravel --}}
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

            {{-- Form dengan method PUT untuk update --}}
            <form action="{{ route('admin.spk.kriteria.update', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    
                    {{-- Input: Kode Kriteria --}}
                    <div>
                        <label for="kode_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Kriteria
                        </label>
                        <input type="text" name="kode_kriteria" id="kode_kriteria" 
                               {{-- Menggunakan old() atau nilai dari database --}}
                               value="{{ old('kode_kriteria', $kriteria->kode_kriteria) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm 
                               @error('kode_kriteria') border-red-500 @enderror"
                               placeholder="Contoh: C1, C2" required>
                        @error('kode_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Nama Kriteria --}}
                    <div>
                        <label for="nama_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Kriteria
                        </label>
                        <input type="text" name="nama_kriteria" id="nama_kriteria" 
                               value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                               @error('nama_kriteria') border-red-500 @enderror"
                               placeholder="Contoh: Nilai Akademik, Tes Potensi" required>
                        @error('nama_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Select: Jenis Kriteria (Benefit/Cost) --}}
                    <div>
                        <label for="jenis_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Kriteria (Type)
                        </label>
                        <select name="jenis_kriteria" id="jenis_kriteria"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                @error('jenis_kriteria') border-red-500 @enderror" required>
                            <option value="Benefit" {{ old('jenis_kriteria', $kriteria->jenis_kriteria) == 'Benefit' ? 'selected' : '' }}>Benefit (Semakin besar semakin baik)</option>
                            <option value="Cost" {{ old('jenis_kriteria', $kriteria->jenis_kriteria) == 'Cost' ? 'selected' : '' }}>Cost (Semakin kecil semakin baik)</option>
                        </select>
                        @error('jenis_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Bobot Kriteria --}}
                    <div>
                        <label for="bobot_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Bobot Kriteria (Wj)
                        </label>
                        <input type="number" name="bobot_kriteria" id="bobot_kriteria" 
                               {{-- Menggunakan number_format untuk menampilkan 4 desimal secara default --}}
                               value="{{ old('bobot_kriteria', number_format($kriteria->bobot_kriteria, 4, '.', '')) }}"
                               step="0.0001" min="0" max="1"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                               @error('bobot_kriteria') border-red-500 @enderror"
                               placeholder="Masukkan bobot antara 0.0000 hingga 1.0000" required>
                        <p class="mt-1 text-xs text-gray-500">
                            *Nilai ini dapat diperbarui otomatis setelah perhitungan AHP.
                        </p>
                        @error('bobot_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.spk.kriteria.index', $keputusan->id_keputusan) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150 shadow-sm">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        <i class="fas fa-edit mr-2"></i> Perbarui Kriteria
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection