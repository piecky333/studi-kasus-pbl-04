@extends('layouts.admin')

@section('title', 'Edit Data Prestasi')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Edit Data Prestasi
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Perbarui data prestasi mahasiswa.
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
                Formulir Edit Prestasi
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Perbarui informasi prestasi di bawah ini.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            
            <form action="{{ route('admin.prestasi.update', $prestasi) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    <!-- NIM Mahasiswa -->
                    <div class="sm:col-span-3 relative">
                        <label for="nim" class="block text-sm font-medium text-gray-700">
                            NIM Mahasiswa
                        </label>
                        <div class="mt-1">
                            <input type="text" id="nim" name="nim" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-2 py-3" placeholder="Cari NIM Mahasiswa..." value="{{ $prestasi->mahasiswa->nim ?? '' }}" autocomplete="off">
                        </div>
                        <ul id="nimList" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm hidden">
                            <!-- AJAX Results -->
                        </ul>
                    </div>

                    <!-- Nama Mahasiswa -->
                    <div class="sm:col-span-3">
                        <label for="nama" class="block text-sm font-medium text-gray-700">
                            Nama Mahasiswa
                        </label>
                        <div class="mt-1">
                            <input type="text" id="nama" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md bg-white px-2 py-3" value="{{ $prestasi->mahasiswa->nama ?? '' }}" readonly>
                        </div>
                    </div>

                    <!-- Nama Kegiatan -->
                    <div class="sm:col-span-6">
                        <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700">
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan" value="{{ old('nama_kegiatan', $prestasi->nama_kegiatan) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('nama_kegiatan') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-2 py-3" required>
                        </div>
                        @error('nama_kegiatan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tingkat Prestasi -->
                    <div class="sm:col-span-3">
                        <label for="tingkat_prestasi" class="block text-sm font-medium text-gray-700">
                            Tingkat Prestasi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select name="tingkat_prestasi" id="tingkat_prestasi" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('tingkat_prestasi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-2 py-3" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="Universitas" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                                <option value="Provinsi" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                <option value="Nasional" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="Internasional" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                            </select>
                        </div>
                        @error('tingkat_prestasi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun -->
                    <div class="sm:col-span-3">
                        <label for="tahun" class="block text-sm font-medium text-gray-700">
                            Tahun <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="number" name="tahun" id="tahun" value="{{ old('tahun', $prestasi->tahun) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('tahun') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-2 py-3" required>
                        </div>
                        @error('tahun')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Validasi -->
                    <div class="sm:col-span-6">
                        <label for="status_validasi" class="block text-sm font-medium text-gray-700">
                            Status Validasi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative">
                            <select name="status_validasi" id="status_validasi" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('status_validasi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-2 py-3" required>
                                <option value="menunggu" {{ old('status_validasi', $prestasi->status_validasi) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ old('status_validasi', $prestasi->status_validasi) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ old('status_validasi', $prestasi->status_validasi) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        @error('status_validasi')
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

{{-- AJAX Pencarian NIM --}}
<script>
document.getElementById('nim').addEventListener('keyup', function() {
    const query = this.value;
    const list = document.getElementById('nimList');
    list.innerHTML = '';

    if (query.length > 1) {
        fetch(`/admin/prestasi/cari-mahasiswa?query=${query}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    list.classList.remove('hidden');
                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white text-gray-900';
                        li.textContent = `${item.nim} - ${item.nama}`;
                        li.addEventListener('click', function() {
                            document.getElementById('nim').value = item.nim;
                            document.getElementById('nama').value = item.nama;
                            list.innerHTML = '';
                            list.classList.add('hidden');
                        });
                        list.appendChild(li);
                    });
                } else {
                    list.classList.add('hidden');
                }
            })
            .catch(() => {
                list.classList.add('hidden');
            });
    } else {
        list.classList.add('hidden');
    }
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const list = document.getElementById('nimList');
    const input = document.getElementById('nim');
    if (e.target !== input && e.target !== list) {
        list.classList.add('hidden');
    }
});
</script>
@endsection
