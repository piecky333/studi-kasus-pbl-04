@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg p-8">
        <header class="mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
            <p class="text-sm text-gray-600">Mengedit Alternatif: <span class="font-semibold text-indigo-600">{{ $alternatif->nama_alternatif }}</span> untuk Keputusan: <span class="font-semibold text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span></p>
        </header>

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

        <form action="{{ route('admin.spk.alternatif.update', [$keputusan->id_keputusan, $alternatif->id_alternatif]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                
                {{-- Input Teks: ID Mahasiswa atau NIM (Opsional, jika terintegrasi) --}}
                <div>
                    <label for="id_mahasiswa" class="block text-sm font-medium text-gray-700 mb-1">ID Mahasiswa (NIM/ID)</label>
                    <input type="text" name="id_mahasiswa" id="id_mahasiswa" 
                           value="{{ old('id_mahasiswa', $alternatif->id_mahasiswa) }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm 
                           @error('id_mahasiswa') border-red-500 @enderror"
                           placeholder="Opsional, jika ada integrasi data mahasiswa">
                    @error('id_mahasiswa')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Input Teks: Mengubah nama alternatif --}}
                <div>
                    <label for="nama_alternatif" class="block text-sm font-medium text-gray-700 mb-1">Nama Alternatif (Mahasiswa)</label>
                    <input type="text" name="nama_alternatif" id="nama_alternatif" 
                           value="{{ old('nama_alternatif', $alternatif->nama_alternatif) }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                           @error('nama_alternatif') border-red-500 @enderror"
                           placeholder="Contoh: Budi Santoso" required>
                    @error('nama_alternatif')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Area: Mengubah keterangan tambahan --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                           @error('keterangan') border-red-500 @enderror"
                           placeholder="Contoh: Semester 6, Aktif BEM">{{ old('keterangan', $alternatif->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
            </div>

            {{-- Bagian Tombol: Simpan perubahan atau Batal --}}
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.spk.alternatif.index', $keputusan->id_keputusan) }}" 
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