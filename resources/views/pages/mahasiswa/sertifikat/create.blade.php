<x-app-layout>
    @section('title', 'Ajukan Sertifikat Baru')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Modern Page Header with Breadcrumb-like feel -->
        <div class="max-w-4xl mx-auto mb-8 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Ajukan Sertifikat
                </h2>
                <p class="mt-2 text-sm text-gray-500 sm:text-base">
                    Lengkapi formulir di bawah ini untuk mengajukan prestasi baru Anda.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('mahasiswa.sertifikat.index') }}" class="group inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:text-indigo-600 focus:outline-none transition-all duration-200 ease-in-out">
                    
                    Kembali
                </a>
            </div>
        </div>

        <!-- Modern Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-4xl mx-auto border border-gray-100">
            
            <!-- Gradient Header -->
            <div class="relative px-6 py-6 bg-gradient-to-r from-indigo-600 to-blue-500 sm:px-10">
                <div class="absolute inset-0 bg-white/10 mix-blend-overlay"></div> <!-- Texture/Overlay effect -->
                <h3 class="text-xl leading-6 font-bold text-white relative z-10">
                    Formulir Pengajuan
                </h3>
                <p class="mt-2 max-w-2xl text-sm text-indigo-100 relative z-10">
                    Mohon isi data dengan teliti. Tanda bintang (<span class="text-red-300 font-bold">*</span>) wajib diisi.
                </p>
            </div>

            <div class="px-6 py-8 sm:px-10 bg-white">
                
                <form action="{{ route('mahasiswa.sertifikat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-6">
                        
                        {{-- Judul Prestasi --}}
                        <div class="sm:col-span-6">
                            <label for="nama_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Judul Prestasi <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <!-- KEEPING EXACT ADMIN STYLES FOR INPUTS as requested -->
                                <input type="text" name="nama_kegiatan" id="nama_kegiatan" value="{{ old('nama_kegiatan') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('nama_kegiatan') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" placeholder="Contoh: Juara 1 Lomba Web Design..." required>
                            </div>
                            @error('nama_kegiatan')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Jenis Prestasi --}}
                        <div class="sm:col-span-3">
                            <label for="jenis_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Prestasi <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <select id="jenis_kegiatan" name="jenis_kegiatan" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('jenis_kegiatan') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Akademik" {{ old('jenis_kegiatan') == 'Akademik' ? 'selected' : '' }}>Akademik (Lomba, Olimpiade)</option>
                                    <option value="Non-Akademik" {{ old('jenis_kegiatan') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik (Seni, Olahraga)</option>
                                    <option value="Pelatihan" {{ old('jenis_kegiatan') == 'Pelatihan' ? 'selected' : '' }}>Pelatihan / Workshop</option>
                                </select>
                            </div>
                            @error('jenis_kegiatan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Kegiatan --}}
                        <div class="sm:col-span-3">
                            <label for="tanggal_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Prestasi <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" value="{{ old('tanggal_kegiatan') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('tanggal_kegiatan') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" required>
                            </div>
                            @error('tanggal_kegiatan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- File Sertifikat --}}
                        <div class="sm:col-span-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload Sertifikat <span class="text-red-500">*</span>
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
                                        PNG, JPG, PDF up to 2MB
                                    </p>
                                </div>

                                <input id="file_sertifikat" name="file_sertifikat" type="file" class="sr-only" required>
                            </label>

                            @error('file_sertifikat')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="sm:col-span-6">
                            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi Tambahan
                            </label>
                            <div class="mt-1">
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('deskripsi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2" placeholder="Berikan keterangan tambahan jika diperlukan...">{{ old('deskripsi') }}</textarea>
                            </div>
                            @error('deskripsi')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="pt-8 mt-4 border-t border-gray-100">
                        <div class="flex justify-end">
                            <a href="{{ route('mahasiswa.sertifikat.index') }}" class="mr-3 bg-white py-2.5 px-5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-md text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('file_sertifikat').addEventListener('change', function(e) {
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
    </script>
    @endpush
</x-app-layout>
