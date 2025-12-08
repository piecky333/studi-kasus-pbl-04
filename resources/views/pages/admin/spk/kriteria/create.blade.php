@extends('layouts.admin')

@section('content')

    <div class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-8">
            <header class="mb-8 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
                <p class="text-sm text-gray-600">Keputusan: <span
                        class="font-medium text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span></p>
            </header>

            {{-- Menampilkan pesan error jika validasi input gagal --}}
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

            <form action="{{ route('admin.spk.kriteria.store', $keputusan->id_keputusan) }}" method="POST">
                @csrf

                {{-- Grup Input Form --}}
                <div class="space-y-6">

                    {{-- Input Teks: Kode Kriteria (misal: C1, C2) --}}
                    <div>
                        <label for="kode_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Kriteria
                        </label>
                        <input type="text" name="kode_kriteria" oninput="this.value = this.value.toUpperCase()" id="kode_kriteria" value="{{ old('kode_kriteria') }}" class="mt-1 block w-full uppercase border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm 
                                   @error('kode_kriteria') border-red-500 @enderror" placeholder="Contoh: C1, C2" required>
                        @error('kode_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Teks: Nama lengkap kriteria --}}
                    <div>
                        <label for="nama_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Kriteria
                        </label>
                        <input type="text" name="nama_kriteria" id="nama_kriteria" value="{{ old('nama_kriteria') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                   @error('nama_kriteria') border-red-500 @enderror"
                            placeholder="Contoh: Nilai Akademik, Tes Potensi" required>
                        @error('nama_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilihan Dropdown: Menentukan jenis kriteria (Benefit atau Cost) --}}
                    <div>
                        <label for="jenis_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Kriteria (Type)
                        </label>
                        <select name="jenis_kriteria" id="jenis_kriteria" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                    @error('jenis_kriteria') border-red-500 @enderror" required>
                            <option value="" disabled selected>-- Pilih Jenis Kriteria --</option>
                            <option value="Benefit" {{ old('jenis_kriteria') == 'Benefit' ? 'selected' : '' }}>Benefit
                                (Semakin besar semakin baik)</option>
                            <option value="Cost" {{ old('jenis_kriteria') == 'Cost' ? 'selected' : '' }}>Cost (Semakin kecil
                                semakin baik)</option>
                        </select>
                        @error('jenis_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Angka: Bobot Kriteria (Readonly, dihitung via AHP) --}}
                    <div>
                        <label for="bobot_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Bobot Kriteria (Wj)
                        </label>
                        {{-- Field ini diset readonly karena bobot akan dihasilkan dari proses AHP --}}
                        <input type="number" name="bobot_kriteria" id="bobot_kriteria"
                            value="{{ old('bobot_kriteria', 0.0000) }}" step="0.0001" min="0" max="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
            @error('bobot_kriteria') border-red-500 @enderror bg-gray-100 cursor-not-allowed"
                            placeholder="Nilai akan diisi otomatis setelah AHP" required **readonly**>
                        <p class="mt-1 text-xs text-red-500">
                            **Bobot awal harus 0.** Nilai ini akan dihitung dan diperbarui otomatis setelah Anda melakukan
                            **Perbandingan Berpasangan (AHP)** di menu selanjutnya.
                        </p>
                        @error('bobot_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Bagian Tombol: Simpan atau Batal --}}
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.spk.kriteria.index', $keputusan->id_keputusan) }}"
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