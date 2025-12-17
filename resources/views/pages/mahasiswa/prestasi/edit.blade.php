<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Prestasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('mahasiswa.prestasi.update', $prestasi->id_prestasi) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Judul -->
                        <div>
                            <x-input-label for="nama_kegiatan" :value="__('Nama Kegiatan / Lomba')" />
                            <x-text-input id="nama_kegiatan" class="block mt-1 w-full" type="text" name="nama_kegiatan" :value="old('nama_kegiatan', $prestasi->nama_kegiatan)" required />
                            <x-input-error :messages="$errors->get('nama_kegiatan')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Jenis -->
                            <div>
                                <x-input-label for="jenis_prestasi" :value="__('Jenis Prestasi')" />
                                <select id="jenis_prestasi" name="jenis_prestasi" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="Akademik" {{ old('jenis_prestasi', $prestasi->jenis_prestasi) == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                    <option value="Non-Akademik" {{ old('jenis_prestasi', $prestasi->jenis_prestasi) == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                                </select>
                                <x-input-error :messages="$errors->get('jenis_prestasi')" class="mt-2" />
                            </div>

                            <!-- Tahun -->
                            <div>
                                <x-input-label for="tahun" :value="__('Tahun Perolehan')" />
                                <x-text-input id="tahun" class="block mt-1 w-full" type="number" name="tahun" :value="old('tahun', $prestasi->tahun)" required />
                                <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Tingkat -->
                        <div>
                            <x-input-label for="tingkat_prestasi" :value="__('Tingkat Prestasi')" />
                            <select id="tingkat_prestasi" name="tingkat_prestasi" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach(['Internasional', 'Nasional', 'Provinsi', 'Kabupaten/Kota', 'Internal'] as $lvl)
                                    <option value="{{ $lvl }}" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('tingkat_prestasi')" class="mt-2" />
                        </div>

                        <!-- Juara -->
                        <div>
                            <x-input-label for="juara" :value="__('Peringkat / Juara')" />
                            <x-text-input id="juara" class="block mt-1 w-full" type="text" name="juara" :value="old('juara', $prestasi->juara)" required />
                            <x-input-error :messages="$errors->get('juara')" class="mt-2" />
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi Singkat')" />
                            <textarea id="deskripsi" name="deskripsi" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deskripsi', $prestasi->deskripsi) }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>

                        <!-- Bukti File -->
                        <div>
                            <x-input-label for="bukti_file" :value="__('Update Bukti (Biarkan kosong jika tidak diubah)')" />
                            
                            @if($prestasi->bukti_path)
                                <div class="text-sm text-gray-500 mb-2">
                                    File saat ini: <a href="{{ asset('storage/' . $prestasi->bukti_path) }}" target="_blank" class="text-indigo-600 underline">Lihat File</a>
                                </div>
                            @endif

                            <input id="bukti_file" type="file" name="bukti_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mt-1" accept=".pdf,.jpg,.jpeg,.png" />
                            <p class="mt-1 text-sm text-gray-500">Format: PDF, JPG, PNG (Max 2MB).</p>
                            <x-input-error :messages="$errors->get('bukti_file')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('mahasiswa.prestasi.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
