@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">Mengedit data: <span class="font-medium text-blue-600">{{ $alternatif->nama_alternatif }}</span> untuk Keputusan: {{ $keputusan->nama_keputusan }}</p>
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

    {{-- Form action menunjuk ke route update Alternatif --}}
    <form action="{{ route('admin.spk.manage.alternatif.update', ['idKeputusan' => $keputusan->id_keputusan, 'idAlternatif' => $alternatif->id_alternatif]) }}" method="POST">
        @csrf
        @method('PUT') {{-- PENTING: Gunakan method PUT/PATCH untuk update --}}

        <div class="space-y-4">
            
            {{-- 1. Nama Alternatif --}}
            <div>
                <label for="nama_alternatif" class="block text-sm font-medium text-gray-700">Nama Alternatif / Kandidat</label>
                <input type="text" name="nama_alternatif" id="nama_alternatif" 
                       {{-- Mengisi nilai dari data yang sudah ada --}}
                       value="{{ old('nama_alternatif', $alternatif->nama_alternatif) }}" 
                       placeholder="Contoh: Alternatif A, Mahasiswa B"
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border @error('nama_alternatif') border-red-500 @enderror">
                @error('nama_alternatif')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- 2. Keterangan (Opsional) --}}
            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan Tambahan (Opsional)</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                       placeholder="Tambahkan informasi penting mengenai alternatif ini."
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $alternatif->keterangan) }}</textarea>
                @error('keterangan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.spk.manage.alternatif', $keputusan->id_keputusan) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            {{-- Tombol Submit, fokus biru --}}
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                <i class="fas fa-sync-alt mr-2"></i> Perbarui Alternatif
            </button>
        </div>
    </form>
</div>


</div>
@endsection