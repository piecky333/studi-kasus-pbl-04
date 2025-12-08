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
            
            <form action="{{ route('admin.prestasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    {{-- Cari Mahasiswa (Multi-Select) --}}
                    <div class="sm:col-span-6">
                        <label for="id_mahasiswa" class="block text-sm font-medium text-gray-700">
                            Pilih Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Anda dapat memilih lebih dari satu mahasiswa (Untuk prestasi tim/kelompok).</p>
                        
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
                                <div x-show="open && filteredOptions.length > 0" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm" style="display: none;">
                                    <ul>
                                        <template x-for="option in filteredOptions" :key="option.id">
                                            <li @click="add(option)" class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white">
                                                <span x-text="option.label" class="font-normal block truncate"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                                
                                <div x-show="open && filteredOptions.length === 0" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md py-2 px-3 text-sm text-gray-500" style="display: none;">
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
                            </div>
                        </div>
                        @error('id_mahasiswa')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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
                    {{-- Jenis Prestasi --}}
                    <div class="sm:col-span-3">
                        <label for="jenis_prestasi" class="block text-sm font-medium text-gray-700">
                            Jenis Prestasi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select id="jenis_prestasi" name="jenis_prestasi" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('jenis_prestasi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Akademik" {{ old('jenis_prestasi') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                <option value="Non-Akademik" {{ old('jenis_prestasi') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                            </select>
                        </div>
                        @error('jenis_prestasi')
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
                    {{-- Bukti Prestasi (File) --}}
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Bukti Prestasi (File, Opsional)
                        </label>

                        <label id="dropzone-label" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 
                                    border-dashed rounded-md hover:border-indigo-500 transition-colors 
                                    duration-200 cursor-pointer">

                            <div class="space-y-1 text-center">
                                <svg id="upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" 
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <div class="text-sm text-gray-600">
                                    <span id="file-name" class="font-medium text-indigo-600">Upload file</span> <span id="drag-text">atau drag and drop</span>
                                </div>

                                <p class="text-xs text-gray-500">
                                    PDF, JPG, PNG, DOCX up to 2MB
                                </p>
                            </div>

                            <input id="bukti_file" name="bukti_file" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        </label>

                        @error('bukti_file')
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
                            Simpan 
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
    document.getElementById('bukti_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const label = document.getElementById('dropzone-label');
        const icon = document.getElementById('upload-icon');
        const fileName = document.getElementById('file-name');
        const dragText = document.getElementById('drag-text');

        if (file) {
            // Change styles when file is selected
            label.classList.remove('border-gray-300');
            label.classList.add('border-indigo-500', 'bg-indigo-50');
            
            icon.classList.remove('text-gray-400');
            icon.classList.add('text-indigo-500');
            
            fileName.textContent = file.name;
            dragText.textContent = 'Terpilih';
        } else {
            // Revert styles if canceled
            label.classList.add('border-gray-300');
            label.classList.remove('border-indigo-500', 'bg-indigo-50');
            
            icon.classList.add('text-gray-400');
            icon.classList.remove('text-indigo-500');
            
            fileName.textContent = 'Upload file';
            dragText.textContent = 'atau drag and drop';
        }
    });

document.getElementById('btnCari').addEventListener('click', function() {
    const nim = document.getElementById('nim').value.trim();
    const hasil = document.getElementById('hasilMahasiswa');
    const idInput = document.getElementById('id_mahasiswa');
    const studentCard = document.getElementById('studentCard');
    
    // Elements in card
    const cardName = document.getElementById('cardName');
    const cardNim = document.getElementById('cardNim');
    const cardEmail = document.getElementById('cardEmail');
    const cardSemester = document.getElementById('cardSemester');

    if (!nim) {
        hasil.textContent = 'Masukkan NIM terlebih dahulu.';
        hasil.className = 'mt-2 text-sm text-red-600';
        studentCard.classList.add('hidden');
        return;
    }

    hasil.textContent = 'Mencari data mahasiswa...';
    hasil.className = 'mt-2 text-sm text-gray-500';
    studentCard.classList.add('hidden');

    fetch(`/admin/prestasi/cari-mahasiswa?nim=${nim}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Populate card
                cardName.textContent = data.mahasiswa.nama;
                cardNim.textContent = `NIM: ${data.mahasiswa.nim}`;
                cardEmail.textContent = `Email: ${data.mahasiswa.email}`;
                cardSemester.textContent = `Semester: ${data.mahasiswa.semester}`;
                
                // Show card and hide text message
                studentCard.classList.remove('hidden');
                hasil.textContent = ''; 
                
                idInput.value = data.mahasiswa.id_mahasiswa;
            } else {
                hasil.textContent = 'Mahasiswa tidak ditemukan.';
                hasil.className = 'mt-2 text-sm text-red-600';
                studentCard.classList.add('hidden');
                idInput.value = '';
            }
        })
        .catch(() => {
            hasil.textContent = 'Terjadi kesalahan saat mencari data.';
            hasil.className = 'mt-2 text-sm text-red-600';
            studentCard.classList.add('hidden');
        });
});
</script>
@endsection
