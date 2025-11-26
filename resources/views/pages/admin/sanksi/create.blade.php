@extends('layouts.admin')

@section('title', 'Tambah Sanksi')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Tambah Sanksi Baru
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Isi formulir berikut untuk menambahkan data sanksi mahasiswa.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.sanksi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200 max-w-3xl mx-auto">
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Formulir Sanksi Mahasiswa
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Pastikan data yang dimasukkan sudah benar.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            
            <!-- Error Validation -->
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-6 border-l-4 border-red-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada inputan Anda:</h3>
                            <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.sanksi.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    <!-- Mahasiswa -->
                    <div class="sm:col-span-6">
                        <label for="id_mahasiswa" class="block text-sm font-medium text-gray-700">
                            Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="id_mahasiswa" name="id_mahasiswa" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('id_mahasiswa') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswa as $mhs)
                                    <option value="{{ $mhs->id_mahasiswa }}" {{ old('id_mahasiswa') == $mhs->id_mahasiswa ? 'selected' : '' }}>
                                        {{ $mhs->nama }} ({{ $mhs->nim ?? 'NIM Tidak Ada' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_mahasiswa')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Sanksi -->
                    <div class="sm:col-span-3">
                        <label for="jenis_sanksi" class="block text-sm font-medium text-gray-700">
                            Jenis Sanksi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="jenis_sanksi" name="jenis_sanksi" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('jenis_sanksi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" required>
                                <option value="">-- Pilih Jenis Sanksi --</option>
                                <option value="Ringan" {{ old('jenis_sanksi') == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                                <option value="Sedang" {{ old('jenis_sanksi') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Berat" {{ old('jenis_sanksi') == 'Berat' ? 'selected' : '' }}>Berat</option>
                            </select>
                        </div>
                        @error('jenis_sanksi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Sanksi -->
                    <div class="sm:col-span-3">
                        <label for="tanggal_sanksi" class="block text-sm font-medium text-gray-700">
                            Tanggal Sanksi
                        </label>
                        <div class="mt-1">
                            <input type="date" name="tanggal_sanksi" id="tanggal_sanksi" value="{{ old('tanggal_sanksi') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('tanggal_sanksi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                        </div>
                        @error('tanggal_sanksi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="sm:col-span-6">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700">
                            Keterangan / Detail Pelanggaran
                        </label>
                        <div class="mt-1">
                            <textarea id="keterangan" name="keterangan" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('keterangan') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Jelaskan secara singkat mengenai pelanggaran yang dilakukan.</p>
                        @error('keterangan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('admin.sanksi.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-save mr-2"></i> Simpan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
