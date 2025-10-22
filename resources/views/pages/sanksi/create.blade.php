@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-t-lg shadow-md text-white">
            <h2 class="text-2xl font-bold">Tambah Data Sanksi</h2>
            <p class="text-sm text-indigo-100 mt-1">Isi form di bawah untuk menambahkan data sanksi mahasiswa.</p>
        </div>

        <!-- Form -->
        <div class="bg-white p-8 rounded-b-lg shadow-lg">
            <form action="{{ route('admin.sanksi.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Mahasiswa -->
                <div>
                    <label for="id_mahasiswa" class="block text-sm font-medium text-gray-700 mb-1">
                        Mahasiswa
                    </label>
                    <select name="id_mahasiswa" id="id_mahasiswa" 
                            class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                            required>
                        <option value="">-- Pilih Mahasiswa --</option>
                        @foreach($mahasiswa as $mhs)
                            <option value="{{ $mhs->id_mahasiswa }}">{{ $mhs->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Sanksi -->
                <div>
                    <label for="jenis_sanksi" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Sanksi
                    </label>
                    <input type="text" id="jenis_sanksi" name="jenis_sanksi" 
                           class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                           required>
                </div>

                <!-- Tanggal Sanksi -->
                <div>
                    <label for="tanggal_sanksi" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Sanksi
                    </label>
                    <input type="date" id="tanggal_sanksi" name="tanggal_sanksi" 
                           class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.sanksi.index') }}"
                       class="px-5 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition">
                        <i class="bi bi-arrow-left-circle mr-1"></i> Kembali
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition">
                        <i class="bi bi-check2-circle mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
