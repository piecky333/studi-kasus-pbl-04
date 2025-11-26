@extends('layouts.admin')

@section('title', 'Tambah Prestasi Mahasiswa')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Tambah Prestasi Mahasiswa
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Isi formulir berikut untuk menambahkan data prestasi mahasiswa.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.prestasi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] overflow-hidden sm:rounded-lg border border-gray-200 max-w-3xl mx-auto">
        <div class="px-4 py-4 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Formulir Prestasi Mahasiswa
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Pastikan data yang dimasukkan sudah benar.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            
            <form action="{{ route('admin.prestasi.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    {{-- Cari Mahasiswa (NIM) --}}
                    <div class="sm:col-span-6">
                        <label for="nim" class="block text-sm font-medium text-gray-700">
                            Cari Mahasiswa (NIM) <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="text" name="nim" id="nim" value="{{ old('nim') }}" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300 px-3" placeholder="Masukkan NIM mahasiswa..." required>
                            <button type="button" id="btnCari" class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                <i class="fas fa-search"></i>
                                <span>Cari</span>
                            </button>
                        </div>
                        <p id="hasilMahasiswa" class="mt-2 text-sm text-gray-500"></p>
                        @error('id_mahasiswa')
                            <p class="mt-2 text-sm text-red-600">Mahasiswa dengan NIM tersebut tidak ditemukan atau belum dipilih.</p>
                        @enderror
                    </div>

                    {{-- Hidden ID Mahasiswa --}}
                    <input type="hidden" name="id_mahasiswa" id="id_mahasiswa" value="{{ old('id_mahasiswa') }}">

                    {{-- Judul Prestasi --}}
                    <div class="sm:col-span-6">
                        <label for="judul_prestasi" class="block text-sm font-medium text-gray-700">
                            Judul Prestasi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="judul_prestasi" id="judul_prestasi" value="{{ old('judul_prestasi') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('judul_prestasi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" placeholder="Masukkan judul prestasi..." required>
                        </div>
                        @error('judul_prestasi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tingkat Prestasi --}}
                    <div class="sm:col-span-3">
                        <label for="tingkat" class="block text-sm font-medium text-gray-700">
                            Tingkat Prestasi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="tingkat" name="tingkat" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('tingkat') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="Lokal" {{ old('tingkat') == 'Lokal' ? 'selected' : '' }}>Lokal</option>
                                <option value="Provinsi" {{ old('tingkat') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                <option value="Nasional" {{ old('tingkat') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="Internasional" {{ old('tingkat') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                            </select>
                        </div>
                        @error('tingkat')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Prestasi --}}
                    <div class="sm:col-span-3">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700">
                            Tanggal Prestasi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('tanggal') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" required>
                        </div>
                        @error('tanggal')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi / Keterangan --}}
                    <div class="sm:col-span-6">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                            Deskripsi / Keterangan
                        </label>
                        <div class="mt-1">
                            <textarea id="deskripsi" name="deskripsi" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('deskripsi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" placeholder="Tuliskan keterangan prestasi...">{{ old('deskripsi') }}</textarea>
                        </div>
                        @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('btnCari').addEventListener('click', function() {
    const nim = document.getElementById('nim').value.trim();
    const hasil = document.getElementById('hasilMahasiswa');
    const idInput = document.getElementById('id_mahasiswa');

    if (!nim) {
        hasil.textContent = 'Masukkan NIM terlebih dahulu.';
        hasil.className = 'mt-2 text-sm text-red-600';
        return;
    }

    hasil.textContent = 'Mencari data mahasiswa...';
    hasil.className = 'mt-2 text-sm text-gray-500';

    fetch(`/admin/prestasi/cari-mahasiswa?nim=${nim}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                hasil.innerHTML = `<strong>${data.mahasiswa.nama}</strong> (${data.mahasiswa.nim}) ditemukan `;
                hasil.className = 'mt-2 text-sm text-green-600';
                idInput.value = data.mahasiswa.id_mahasiswa;
            } else {
                hasil.textContent = 'Mahasiswa tidak ditemukan ';
                hasil.className = 'mt-2 text-sm text-red-600';
                idInput.value = '';
            }
        })
        .catch(() => {
            hasil.textContent = 'Terjadi kesalahan saat mencari data.';
            hasil.className = 'mt-2 text-sm text-red-600';
        });
});
</script>
@endsection
