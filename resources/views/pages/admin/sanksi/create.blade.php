@extends('layouts.admin')

@section('title', 'Tambah Sanksi')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ pdfOpen: false }">
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
        <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
            <button @click="pdfOpen = true" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-book mr-2"></i> Lihat Kode Etik
            </button>
            <a href="{{ route('admin.sanksi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali
            </a>
        </div>
    </div>

    <!-- PDF Modal -->
    <div x-show="pdfOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="pdfOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="pdfOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Kode Etik Mahasiswa
                        </h3>
                        <button @click="pdfOpen = false" type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2 h-[70vh]">
                        <iframe src="{{ asset('pdf/PD_KODE-ETIK-MAHASISWA-POLITALA.pdf') }}" class="w-full h-full rounded-md border border-gray-200">
                            <p>Browser Anda tidak mendukung iframe PDF. Silakan <a href="{{ asset('pdf/PD_KODE-ETIK-MAHASISWA-POLITALA.pdf') }}">unduh file PDF</a>.</p>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] overflow-hidden sm:rounded-lg border border-gray-200 max-w-3xl mx-auto">
        <div class="px-4 py-4 sm:px-6 bg-gray-50 border-b border-gray-200">
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

            <form action="{{ route('admin.sanksi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    <!-- Mahasiswa -->
                    <div class="sm:col-span-6">
                        <label for="id_mahasiswa" class="block text-sm font-medium text-gray-700">
                            Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                        <div x-data="{
                            search: '',
                            open: false,
                            selected: [],
                            options: [
                                @foreach($mahasiswa as $mhs)
                                    { 
                                        id: '{{ $mhs->id_mahasiswa }}', 
                                        nama: '{{ addslashes($mhs->nama) }}', 
                                        nim: '{{ $mhs->nim ?? '' }}',
                                        label: '{{ addslashes($mhs->nama) }} ({{ $mhs->nim ?? 'NIM Tidak Ada' }})' 
                                    },
                                @endforeach
                            ],
                            get filteredOptions() {
                                if (this.search === '') {
                                    return this.options.filter(i => !this.selected.some(s => s.id === i.id));
                                }
                                const lowerSearch = this.search.toLowerCase();
                                return this.options.filter(i => {
                                    // Logic: 
                                    // 1. Check if NIM contains search term
                                    // 2. Check if any word in Name starts with search term
                                    const nimMatch = i.nim && i.nim.toLowerCase().includes(lowerSearch);
                                    const nameMatch = i.nama.toLowerCase().split(' ').some(word => word.startsWith(lowerSearch));
                                    
                                    return (nimMatch || nameMatch) && !this.selected.some(s => s.id === i.id);
                                });
                            },
                            add(option) {
                                this.selected.push(option);
                                this.search = '';
                                this.open = false;
                            },
                            remove(id) {
                                this.selected = this.selected.filter(item => item.id !== id);
                            },
                            init() {
                                // Optional: Handle old input functionality here if needed
                            }
                        }" class="relative">
                            
                            <!-- Search & Select Input -->
                            <div class="relative">
                                <input 
                                    type="text" 
                                    x-model="search" 
                                    @focus="open = true" 
                                    @click.away="open = false" 
                                    placeholder="Ketik nama atau NIM mahasiswa..." 
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2"
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Dropdown List -->
                            <div x-show="open && filteredOptions.length > 0" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                <ul>
                                    <template x-for="option in filteredOptions" :key="option.id">
                                        <li @click="add(option)" class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white">
                                            <span x-text="option.label" class="font-normal block truncate"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            
                            <div x-show="open && filteredOptions.length === 0" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md py-2 px-3 text-sm text-gray-500">
                                Tidak ada data yang cocok.
                            </div>

                            {{-- Selected Chips --}}
                            <div class="mt-2 flex flex-wrap gap-2">
                                <template x-for="item in selected" :key="item.id">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        <span x-text="item.label"></span>
                                        <button type="button" @click="remove(item.id)" class="flex-shrink-0 ml-1.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-indigo-400 hover:bg-indigo-200 hover:text-indigo-500 focus:outline-none focus:bg-indigo-500 focus:text-white">
                                            <span class="sr-only">Remove</span>
                                            <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                                <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                            </svg>
                                        </button>
                                        {{-- Hidden Input for Form Submission --}}
                                        <input type="hidden" name="id_mahasiswa[]" :value="item.id">
                                    </span>
                                </template>
                            </div>
                            
                            {{-- Validation Error --}}
                            @error('id_mahasiswa')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        </div>
                        @error('id_mahasiswa')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Sanksi & Hukuman (Dependent Dropdown) -->
                    <div class="sm:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{
                        jenisSanksi: '{{ old('jenis_sanksi') }}',
                        jenisHukuman: '{{ old('jenis_hukuman') }}',
                        hukumanOptions: {
                            'Ringan': ['Teguran Lisan', 'Teguran Tertulis'],
                            'Sedang': [
                                'Penangguhan ujian akhir atau tugas akhir', 
                                'Tidak diperbolehkan mengikuti wisuda', 
                                'Penahanan ijazah dan transkrip nilai', 
                                'Membayar ganti kerugian', 
                                'Pembatalan kelulusan mata kuliah', 
                                'Skorsing minimal 1 (satu) semester dan maksimal 2 (dua) semester'
                            ],
                            'Berat': [
                                'Pemberhentian secara hormat sebagai mahasiswa Politala', 
                                'Pemberhentian secara tidak hormat sebagai mahasiswa Politala', 
                                'Pencabutan gelar dan ijazah', 
                                'Penghentian beasiswa'
                            ]
                        }
                    }">
                        <!-- Jenis Sanksi -->
                        <div>
                            <label for="jenis_sanksi" class="block text-sm font-medium text-gray-700">
                                Jenis Sanksi <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <select id="jenis_sanksi" name="jenis_sanksi" x-model="jenisSanksi" @change="jenisHukuman = ''" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('jenis_sanksi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" required>
                                    <option value="">-- Pilih Jenis Sanksi --</option>
                                    <option value="Ringan">Ringan</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Berat">Berat</option>
                                </select>
                            </div>
                            @error('jenis_sanksi')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Hukuman -->
                        <div>
                            <label for="jenis_hukuman" class="block text-sm font-medium text-gray-700">
                                Jenis Hukuman <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <select id="jenis_hukuman" name="jenis_hukuman" x-model="jenisHukuman" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('jenis_hukuman') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" required :disabled="!jenisSanksi">
                                    <option value="">-- Pilih Hukuman --</option>
                                    <template x-if="jenisSanksi && hukumanOptions[jenisSanksi]">
                                        <template x-for="option in hukumanOptions[jenisSanksi]" :key="option">
                                            <option :value="option" x-text="option" :selected="option === jenisHukuman"></option>
                                        </template>
                                    </template>
                                </select>
                            </div>
                            @error('jenis_hukuman')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanggal Sanksi -->
                    <div class="sm:col-span-3">
                        <label for="tanggal_sanksi" class="block text-sm font-medium text-gray-700">
                            Tanggal Pemberian Sanksi
                        </label>
                        <div class="mt-1">
                            <input type="date" name="tanggal_sanksi" id="tanggal_sanksi" value="{{ old('tanggal_sanksi') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('tanggal_sanksi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2">
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
                            <textarea id="keterangan" name="keterangan" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('keterangan') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2 " placeholder="Masukkan keterangan / detail pelanggaran">{{ old('keterangan') }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Jelaskan secara singkat mengenai pelanggaran yang dilakukan.</p>
                        @error('keterangan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Pendukung -->
                    <div class="sm:col-span-6">
                        <label for="file_pendukung" class="block text-sm font-medium text-gray-700">
                            File Pendukung (PDF / Foto)
                        </label>
                        <div class="mt-1">
                            <input type="file" name="file_pendukung" id="file_pendukung" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md px-3 py-2">
                            <p class="mt-1 text-sm text-gray-500">
                                Format: PDF, JPG, PNG. Maks: 2MB.
                            </p>
                        </div>
                        @error('file_pendukung')
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
@endsection
