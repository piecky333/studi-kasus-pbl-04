@extends('layouts.admin')

@section('content')

    <div class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-8">
            <header class="mb-8 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
                <p class="text-sm text-gray-600">Anda sedang mengedit kriteria: 
                    <span class="font-medium text-indigo-600">{{ $kriteria->kode_kriteria }} - {{ $kriteria->nama_kriteria }}</span> 
                    untuk Keputusan: <span class="font-medium text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span>
                </p>
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

            {{-- Form untuk memperbarui data kriteria --}}
            <form action="{{ route('admin.spk.kriteria.update', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    
                    {{-- Input Teks: Mengubah kode kriteria --}}
                    <div>
                        <label for="kode_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Kode Kriteria
                        </label>
                        <input type="text" name="kode_kriteria" id="kode_kriteria" 
                               {{-- Mengambil nilai lama jika ada error, atau nilai dari database --}}
                               value="{{ old('kode_kriteria', $kriteria->kode_kriteria) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm 
                               @error('kode_kriteria') border-red-500 @enderror"
                               placeholder="Contoh: C1, C2" required>
                        @error('kode_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilihan Dropdown: Menentukan sumber data --}}
                    <div>
                        <label for="sumber_data" class="block text-sm font-medium text-gray-700 mb-1">
                            Sumber Data
                        </label>
                        <select name="sumber_data" id="sumber_data" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                    @error('sumber_data') border-red-500 @enderror" required>
                            <option value="Manual" {{ old('sumber_data', $kriteria->sumber_data) == 'Manual' ? 'selected' : '' }}>Manual</option>
                            <option value="Prestasi" {{ old('sumber_data', $kriteria->sumber_data) == 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                            <option value="Sanksi" {{ old('sumber_data', $kriteria->sumber_data) == 'Sanksi' ? 'selected' : '' }}>Sanksi</option>
                            <option value="Pengaduan" {{ old('sumber_data', $kriteria->sumber_data) == 'Pengaduan' ? 'selected' : '' }}>Pengaduan</option>
                            <option value="Berita" {{ old('sumber_data', $kriteria->sumber_data) == 'Berita' ? 'selected' : '' }}>Berita</option>
                        </select>
                        @error('sumber_data')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Teks/Dropdown: Nama kriteria --}}
                    <div>
                        <label for="nama_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Kriteria
                        </label>
                        
                        {{-- Container untuk Input Teks (Manual) --}}
                        <div id="nama_kriteria_text_container">
                            <input type="text" name="nama_kriteria" id="nama_kriteria_text" 
                                   value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                   @error('nama_kriteria') border-red-500 @enderror"
                                   placeholder="Contoh: Nilai Akademik, Tes Potensi">
                        </div>

                        {{-- Container untuk Dropdown (Otomatis) --}}
                        <div id="nama_kriteria_select_container" class="hidden">
                            <select name="nama_kriteria_select" id="nama_kriteria_select" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <!-- Options will be populated via JS -->
                            </select>
                        </div>

                        @error('nama_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const sumberDataSelect = document.getElementById('sumber_data');
                            const textContainer = document.getElementById('nama_kriteria_text_container');
                            const textInput = document.getElementById('nama_kriteria_text');
                            const selectContainer = document.getElementById('nama_kriteria_select_container');
                            const selectInput = document.getElementById('nama_kriteria_select');
                            
                            // Data columns dari controller
                            const tableColumns = @json($tableColumns);
                            const currentNamaKriteria = "{{ $kriteria->nama_kriteria }}";

                            function updateInputType() {
                                const selectedSource = sumberDataSelect.value;

                                if (selectedSource === 'Manual') {
                                    textContainer.classList.remove('hidden');
                                    selectContainer.classList.add('hidden');
                                    textInput.disabled = false;
                                    selectInput.disabled = true; // Disable select agar tidak terkirim
                                    
                                    // Set name attribute agar textInput yang dikirim sebagai 'nama_kriteria'
                                    textInput.setAttribute('name', 'nama_kriteria');
                                    selectInput.removeAttribute('name');
                                } else {
                                    textContainer.classList.add('hidden');
                                    selectContainer.classList.remove('hidden');
                                    textInput.disabled = true;
                                    selectInput.disabled = false;

                                    // Set name attribute agar selectInput yang dikirim sebagai 'nama_kriteria'
                                    selectInput.setAttribute('name', 'nama_kriteria');
                                    textInput.removeAttribute('name');

                                    // Populate options
                                    selectInput.innerHTML = '';
                                    if (tableColumns[selectedSource]) {
                                        // tableColumns[selectedSource] is now an object {colName: label}
                                        Object.entries(tableColumns[selectedSource]).forEach(([colName, label]) => {
                                            const option = document.createElement('option');
                                            option.value = colName;
                                            option.textContent = label; 
                                            
                                            // Select current value if matches
                                            if (colName === currentNamaKriteria) {
                                                option.selected = true;
                                            }
                                            selectInput.appendChild(option);
                                        });
                                    }
                                }
                            }

                            sumberDataSelect.addEventListener('change', updateInputType);
                            
                            // Initialize on load
                            updateInputType();
                        });
                    </script>

                    {{-- Pilihan Dropdown: Mengubah jenis kriteria --}}
                    <div>
                        <label for="jenis_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Kriteria (Type)
                        </label>
                        <select name="jenis_kriteria" id="jenis_kriteria"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                                @error('jenis_kriteria') border-red-500 @enderror" required>
                            <option value="Benefit" {{ old('jenis_kriteria', $kriteria->jenis_kriteria) == 'Benefit' ? 'selected' : '' }}>Benefit (Semakin besar semakin baik)</option>
                            <option value="Cost" {{ old('jenis_kriteria', $kriteria->jenis_kriteria) == 'Cost' ? 'selected' : '' }}>Cost (Semakin kecil semakin baik)</option>
                        </select>
                        @error('jenis_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Angka: Bobot Kriteria (Dapat diedit jika perlu penyesuaian manual) --}}
                    <div>
                        <label for="bobot_kriteria" class="block text-sm font-medium text-gray-700 mb-1">
                            Bobot Kriteria (Wj)
                        </label>
                        <input type="number" name="bobot_kriteria" id="bobot_kriteria" 
                               {{-- Memformat nilai bobot menjadi 4 digit desimal --}}
                               value="{{ old('bobot_kriteria', number_format($kriteria->bobot_kriteria, 4, '.', '')) }}"
                               step="0.0001" min="0" max="1"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                               @error('bobot_kriteria') border-red-500 @enderror"
                               placeholder="Masukkan bobot antara 0.0000 hingga 1.0000" required>
                        <p class="mt-1 text-xs text-gray-500">
                            *Nilai ini dapat diperbarui otomatis setelah perhitungan AHP.
                        </p>
                        @error('bobot_kriteria')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                </div>

                {{-- Bagian Tombol: Simpan perubahan atau Batal --}}
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.spk.kriteria.index', $keputusan->id_keputusan) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150 shadow-sm">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        <i class="fas fa-edit mr-2"></i> Perbarui Kriteria
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection