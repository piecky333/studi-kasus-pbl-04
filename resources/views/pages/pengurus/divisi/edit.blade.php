@extends('layouts.pengurus')

@section('title', 'Edit Divisi')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Edit Divisi
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi divisi organisasi.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('pengurus.divisi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] overflow-hidden sm:rounded-lg border border-gray-200 max-w-3xl mx-auto">
        <div class="px-4 py-4 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Formulir Edit Divisi
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Perbarui detail divisi di bawah ini.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-6 border-l-4 border-red-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Terdapat beberapa kesalahan pada pengisian formulir:
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('pengurus.divisi.update', $divisi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    {{-- Nama Divisi --}}
                    <div class="sm:col-span-6">
                        <label for="nama_divisi" class="block text-sm font-medium text-gray-700">
                            Nama Divisi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="nama_divisi" id="nama_divisi" value="{{ old('nama_divisi', $divisi->nama_divisi) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('nama_divisi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" required placeholder="Contoh: Divisi Kominfo">
                        </div>
                        @error('nama_divisi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Foto Divisi --}}
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Foto Saat Ini
                        </label>
                        <div class="mt-2 flex items-center">
                            @if($divisi->foto_divisi)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $divisi->foto_divisi) }}" alt="Foto Divisi" class="h-32 w-auto object-cover rounded-md border border-gray-300 shadow-sm">
                                </div>
                            @else
                                <div class="h-32 w-32 bg-gray-100 border border-gray-300 rounded-md flex items-center justify-center text-gray-400">
                                    <span class="text-xs">Tidak ada foto</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Ganti Foto (Opsional)
                        </label>
                        
                        <label class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 
                                    border-dashed rounded-md hover:border-indigo-500 cursor-pointer transition-colors duration-200">

                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <div class="text-sm text-gray-600">
                                    <span class="font-medium text-indigo-600">Upload file baru</span> atau drag and drop
                                </div>

                                <p class="text-xs text-gray-500">
                                    PNG, JPG, GIF up to 2MB
                                </p>
                            </div>

                            <input name="foto_divisi" type="file" class="sr-only">
                        </label>

                        @error('foto_divisi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi Divisi --}}
                    <div class="sm:col-span-6">
                        <label for="isi_divisi" class="block text-sm font-medium text-gray-700">
                            Deskripsi Divisi
                        </label>
                        <div class="mt-1">
                            <textarea id="isi_divisi" name="isi_divisi" rows="8" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('isi_divisi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" placeholder="Jelaskan tugas dan tanggung jawab divisi ini...">{{ old('isi_divisi', $divisi->isi_divisi) }}</textarea>
                        </div>
                        @error('isi_divisi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
