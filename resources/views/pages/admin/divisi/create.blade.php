@extends('layouts.admin')

@section('title', 'Tambah Divisi')

@section('content')
<div class="container-fluid px-4 mt-6">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Tambah Divisi Baru</h2>
            <p class="mt-1 text-sm text-gray-500">Gunakan nama divisi yang unik dan deskriptif.</p>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <form action="{{ route('admin.divisi.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div>
                    <label for="nama_divisi" class="block text-sm font-medium text-gray-700">Nama Divisi</label>
                    <input type="text" name="nama_divisi" id="nama_divisi" value="{{ old('nama_divisi') }}" required autofocus class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: Divisi Humas">
                    @error('nama_divisi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.divisi.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Simpan Divisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
