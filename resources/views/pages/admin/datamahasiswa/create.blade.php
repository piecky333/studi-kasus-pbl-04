@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')

@section('content')
<div class="container-fluid px-4 mt-6">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h4 class="text-2xl font-bold text-gray-800">Tambah Mahasiswa</h4>
            <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah untuk menambahkan data mahasiswa baru.</p>
        </div>
        <a href="{{ route('admin.datamahasiswa.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden max-w-4xl mx-auto">
        <div class="p-8">
            <form action="{{ route('admin.datamahasiswa.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- NIM --}}
                    <div>
                        <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">NIM</label>
                        <input type="text" id="nim" name="nim"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('nim') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                               placeholder="Contoh: 2023001" value="{{ old('nim') }}" required>
                        @error('nim')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Mahasiswa --}}
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Mahasiswa</label>
                        <input type="text" id="nama" name="nama"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('nama') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap..." value="{{ old('nama') }}" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                               placeholder="nama@mahasiswa.ac.id" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Semester --}}
                    <div class="md:col-span-2">
                        <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                        <select id="semester" name="semester"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white @error('semester') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" required>
                            <option value="">-- Pilih Semester --</option>
                            @for($i = 1; $i <= 14; $i++)
                                <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                        @error('semester')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="flex justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200 flex items-center">
                        Simpan 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection