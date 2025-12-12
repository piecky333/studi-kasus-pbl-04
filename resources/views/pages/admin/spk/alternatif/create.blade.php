@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto py-8 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg p-8">
        <header class="mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
            <p class="text-sm text-gray-600">Untuk Keputusan: <span class="font-semibold text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span></p>
        </header>

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

        {{-- Filter Section --}}
        <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-filter mr-2"></i> Filter Data Mahasiswa
            </h3>
            <form action="{{ route('admin.spk.alternatif.create', $keputusan->id_keputusan) }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                {{-- Filter Semester --}}
                <div>
                    <label for="semester" class="block text-xs font-medium text-gray-700 mb-1">Semester</label>
                    <select name="semester" id="semester" class="block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 px-2 py-2">
                        <option value="">Semua Semester</option>
                        @foreach(range(1, 14) as $sem)
                            <option value="{{ $sem }}" {{ request('semester') == $sem ? 'selected' : '' }}>Semester {{ $sem }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Prestasi --}}
                <div>
                    <label for="filter_prestasi" class="block text-xs font-medium text-gray-700 mb-1">Status Prestasi</label>
                    <select name="filter_prestasi" id="filter_prestasi" class="block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 px-2 py-2">
                        <option value="">Semua</option>
                        <option value="ada" {{ request('filter_prestasi') == 'ada' ? 'selected' : '' }}>Memiliki Prestasi</option>
                        <option value="tidak_ada" {{ request('filter_prestasi') == 'tidak_ada' ? 'selected' : '' }}>Tidak Ada Prestasi</option>
                    </select>
                </div>

                {{-- Filter Sanksi --}}
                <div>
                    <label for="filter_sanksi" class="block text-xs font-medium text-gray-700 mb-1">Status Sanksi</label>
                    <select name="filter_sanksi" id="filter_sanksi" class="block w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 px-2 py-2">
                        <option value="">Semua</option>
                        <option value="ada" {{ request('filter_sanksi') == 'ada' ? 'selected' : '' }}>Memiliki Sanksi</option>
                        <option value="tidak_ada" {{ request('filter_sanksi') == 'tidak_ada' ? 'selected' : '' }}>Tidak Ada Sanksi</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-sm transition duration-150">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        <form action="{{ route('admin.spk.alternatif.store', $keputusan->id_keputusan) }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                
                {{-- Input Pilihan: Memilih Mahasiswa untuk dijadikan Alternatif (Bisa pilih banyak) --}}
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="id_mahasiswa" class="block text-sm font-medium text-gray-700">Mahasiswa <span class="text-red-500">*</span></label>
                        <button type="button" id="btn-toggle-select" data-action="select" data-count="{{ count($mahasiswaList) }}" 
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium focus:outline-none transition-colors duration-200">
                            <i class="fas fa-check-double mr-1"></i> Pilih Semua ({{ count($mahasiswaList) }})
                        </button>
                    </div>
                    
                    {{-- Elemen Select2 untuk pencarian dan pemilihan data mahasiswa --}}
                    <select name="id_mahasiswa[]" id="id_mahasiswa" multiple="multiple" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach($mahasiswaList as $mhs)
                            @php
                                $isExisting = in_array($mhs->id_mahasiswa, $existingMahasiswaIds ?? []);
                            @endphp
                            <option value="{{ $mhs->id_mahasiswa }}" 
                                {{ (collect(old('id_mahasiswa'))->contains($mhs->id_mahasiswa)) ? 'selected' : '' }}
                                {{ $isExisting ? 'disabled' : '' }}>
                                {{ $mhs->nama }} ({{ $mhs->nim }}) {{ $isExisting ? '(Sudah Ditambahkan)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    
                    {{-- Wadah untuk menampilkan chip/tag mahasiswa yang telah dipilih --}}
                    <div id="selected-mahasiswa-container" class="mt-3 flex flex-wrap gap-2">
                        {{-- Item mahasiswa yang dipilih akan dirender di sini menggunakan JavaScript --}}
                    </div>

                    <p class="mt-2 text-xs text-gray-500">Ketik Nama atau NIM untuk mencari.</p>
                    @error('id_mahasiswa')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Input Teks: Menambahkan keterangan tambahan untuk alternatif --}}
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm
                           @error('keterangan') border-red-500 @enderror"
                           placeholder="Contoh: Semester 6, Aktif BEM">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
            </div>

            {{-- Bagian Tombol: Simpan data atau Batal kembali ke daftar --}}
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.spk.alternatif.index', $keputusan->id_keputusan) }}" 
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

@section('scripts')
{{-- Memuat library Select2 untuk fitur dropdown pencarian --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Custom CSS untuk Select2 */
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d5db; /* border-gray-300 */
        border-radius: 0.375rem; /* rounded-md */
        padding: 0.375rem 0.75rem;
        min-height: 42px;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #6366f1; /* border-indigo-500 */
        box-shadow: 0 0 0 1px #6366f1; /* ring-indigo-500 */
    }
    
    /* Sembunyikan chip default di dalam input Select2 */
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        display: none !important;
    }
    
    /* Pastikan input search mengambil lebar penuh agar terlihat seperti search box biasa */
    .select2-container--default .select2-search--inline .select2-search__field {
        margin-top: 0;
        margin-left: 0;
        width: 100% !important;
    }
</style>

<script>
    $(document).ready(function() {
        var $select = $('#id_mahasiswa');
        var $container = $('#selected-mahasiswa-container');

        $select.select2({
            placeholder: "Cari Mahasiswa berdasarkan Nama atau NIM...",
            allowClear: false, // Allow clear false karena kita handle remove sendiri
            width: '100%',
            closeOnSelect: true // Tutup setelah memilih agar rapi
        });

        // Fungsi untuk update tampilan list eksternal
        function updateSelectedList() {
            $container.empty();
            var data = $select.select2('data');
            
            if (data.length === 0) {
                // Opsional: Tampilkan pesan jika kosong
                // $container.html('<span class="text-sm text-gray-400 italic">Belum ada mahasiswa yang dipilih.</span>');
                return;
            }

            data.forEach(function(item) {
                // Skip jika placeholder
                if(item.id === '') return;

                var $tag = $(
                    '<div class="inline-flex items-center bg-indigo-100 text-indigo-700 rounded-full px-3 py-1 text-sm font-medium border border-indigo-200 shadow-sm transition-all hover:bg-indigo-200">' +
                        '<span>' + item.text + '</span>' +
                        '<button type="button" class="ml-2 text-indigo-400 hover:text-indigo-600 focus:outline-none remove-tag" data-id="' + item.id + '">' +
                            '<i class="fas fa-times-circle"></i>' + // Menggunakan FontAwesome jika tersedia, atau &times;
                        '</button>' +
                    '</div>'
                );
                $container.append($tag);
            });
        }

        // Event listener saat ada perubahan di Select2
        $select.on('change', updateSelectedList);
        $select.on('select2:select', function(e) {
             // Clear search input after selection if needed, but closeOnSelect: false handles flow well
        });

        // Initial update (untuk old input saat validasi gagal)
        updateSelectedList();

        // Handle klik tombol hapus pada chip eksternal
        $container.on('click', '.remove-tag', function() {
            var idToRemove = $(this).data('id');
            
            // Ambil array value saat ini
            var currentValues = $select.val();
            
            if (currentValues) {
                // Filter value yang akan dihapus
                var newValues = currentValues.filter(function(val) {
                    return val != idToRemove;
                });
                
                // Update Select2 dan trigger change
                $select.val(newValues).trigger('change');
            }
        });

        // Handle tombol Toggle Select (Pilih Semua / Batalkan)
        $('#btn-toggle-select').click(function() {
            var $btn = $(this);
            var action = $btn.data('action');
            var totalCount = $btn.data('count');

            if (action === 'select') {
                // Logic: Pilih Semua
                var allOptions = [];
                $('#id_mahasiswa option:not(:disabled)').each(function() {
                    allOptions.push($(this).val());
                });
                $select.val(allOptions).trigger('change');

                // Update UI ke state 'Batalkan'
                $btn.data('action', 'deselect');
                $btn.removeClass('text-indigo-600 hover:text-indigo-800').addClass('text-red-600 hover:text-red-800');
                $btn.html('<i class="fas fa-undo mr-1"></i> Batalkan Pilih Semua');
            } else {
                // Logic: Batalkan Semua
                $select.val([]).trigger('change');

                // Update UI ke state 'Pilih Semua'
                $btn.data('action', 'select');
                $btn.removeClass('text-red-600 hover:text-red-800').addClass('text-indigo-600 hover:text-indigo-800');
                $btn.html('<i class="fas fa-check-double mr-1"></i> Pilih Semua (' + totalCount + ')');
            }
        });
    });
</script>
@endsection