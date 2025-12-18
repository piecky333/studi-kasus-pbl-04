@extends('layouts.pengurus')

@section('title', 'Tambah Jabatan')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Tambah Jabatan Baru
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Buat jabatan baru untuk struktur organisasi.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('pengurus.jabatan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] overflow-hidden sm:rounded-lg border border-gray-200 max-w-3xl mx-auto">
        <div class="px-4 py-4 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Formulir Tambah Jabatan
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Isi detail jabatan dengan lengkap.
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

            <form action="{{ route('pengurus.jabatan.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    {{-- Nama Jabatan --}}
                    <div class="sm:col-span-6">
                        <label for="nama_jabatan" class="block text-sm font-medium text-gray-700">
                            Nama Jabatan <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="nama_jabatan" id="nama_jabatan" value="{{ old('nama_jabatan') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('nama_jabatan') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" required placeholder="Contoh: Ketua Divisi">
                        </div>
                        @error('nama_jabatan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi Jabatan --}}
                    <div class="sm:col-span-6">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                            Deskripsi Jabatan <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <div class="mt-1">
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2">{{ old('deskripsi') }}</textarea>
                        </div>
                        @error('deskripsi')
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
