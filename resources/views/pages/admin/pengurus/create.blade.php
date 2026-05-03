@extends('layouts.admin')

@section('title', 'Tambah Pengurus')

@section('content')
<div class="container-fluid px-4 mt-6">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Tambah Pengurus Baru</h2>
            <p class="mt-1 text-sm text-gray-500">Buat akun pengurus baru dan tentukan tugasnya.</p>
        </div>

        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <form action="{{ route('admin.pengurus.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama --}}
                    <div class="col-span-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('username') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password Confirmation --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    {{-- Divisi --}}
                    <div>
                        <label for="id_divisi" class="block text-sm font-medium text-gray-700">Divisi</label>
                        <select name="id_divisi" id="id_divisi" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Divisi</option>
                            @foreach($divisis as $div)
                                <option value="{{ $div->id_divisi }}" {{ old('id_divisi') == $div->id_divisi ? 'selected' : '' }}>{{ $div->nama_divisi }}</option>
                            @endforeach
                        </select>
                        @error('id_divisi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label for="id_jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                        <select name="id_jabatan" id="id_jabatan" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatans as $jab)
                                <option value="{{ $jab->id_jabatan }}" {{ old('id_jabatan') == $jab->id_jabatan ? 'selected' : '' }}>{{ $jab->nama_jabatan }}</option>
                            @endforeach
                        </select>
                        @error('id_jabatan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- No Telpon --}}
                    <div class="col-span-2">
                        <label for="no_telpon" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input type="text" name="no_telpon" id="no_telpon" value="{{ old('no_telpon') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('no_telpon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.pengurus.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Simpan Pengurus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
