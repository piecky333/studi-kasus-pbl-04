<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Prestasi Mahasiswa') }}
            </h2>
            <div class="flex space-x-2">
                 @if($prestasi->status_validasi == 'disetujui')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 uppercase tracking-wider">
                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                    </span>
                @elseif($prestasi->status_validasi == 'ditolak')
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 uppercase tracking-wider">
                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-amber-100 text-amber-800 uppercase tracking-wider">
                        <i class="fas fa-clock mr-1"></i> Menunggu Validasi
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('mahasiswa.prestasi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 hover:text-blue-600 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Info Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600 mr-4">
                                        <i class="fas fa-trophy text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900 leading-tight">{{ $prestasi->nama_kegiatan }}</h3>
                                        <p class="text-sm text-gray-500 font-medium mt-1">{{ $prestasi->jenis_prestasi }} &bull; {{ $prestasi->tahun }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-8">
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Peringkat / Juara</label>
                                        <div class="flex items-center text-indigo-700 font-bold text-lg">
                                            <i class="fas fa-medal mr-2 text-amber-400"></i>
                                            {{ $prestasi->juara }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tingkat Prestasi</label>
                                        <div class="flex items-center text-gray-700 font-semibold">
                                            <i class="fas fa-globe-asia mr-2 text-blue-400"></i>
                                            {{ $prestasi->tingkat_prestasi }}
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tahun Perolehan</label>
                                        <div class="flex items-center text-gray-700 font-semibold">
                                            <i class="fas fa-calendar-check mr-2 text-green-400"></i>
                                            {{ $prestasi->tahun }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-10 pt-8 border-t border-gray-100">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Deskripsi Kegiatan</label>
                                <div class="bg-gray-50 rounded-xl p-5 text-gray-700 leading-relaxed text-sm">
                                    {{ $prestasi->deskripsi ?? 'Tidak ada deskripsi yang ditambahkan.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar / Proof -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                            <h4 class="text-sm font-bold text-gray-700 flex items-center">
                                <i class="fas fa-paperclip mr-2 text-gray-400"></i> Bukti Lampiran
                            </h4>
                        </div>
                        <div class="p-6">
                            @if($prestasi->bukti_path)
                                @php
                                    $extension = pathinfo($prestasi->bukti_path, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp

                                    @if($isImage)
                                        <div class="group relative rounded-xl overflow-hidden border border-gray-200 shadow-sm transition-all duration-300 hover:shadow-md">
                                            <img src="{{ asset('storage/' . $prestasi->bukti_path) }}" alt="Bukti Prestasi" class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <a href="{{ asset('storage/' . $prestasi->bukti_path) }}" target="_blank" class="bg-white text-gray-900 p-3 rounded-full shadow-lg mx-1">
                                                    <i class="fas fa-search-plus"></i>
                                                </a>
                                                <a href="{{ asset('storage/' . $prestasi->bukti_path) }}" download class="bg-blue-600 text-white p-3 rounded-full shadow-lg mx-1">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <p class="mt-3 text-xs text-center text-gray-400 italic">Klik ikon untuk memperbesar atau mengunduh</p>
                                    @else
                                        <div class="bg-red-50 border border-red-100 rounded-xl p-6 text-center">
                                            <i class="fas fa-file-pdf text-4xl text-red-400 mb-3"></i>
                                            <p class="text-sm font-bold text-gray-900 mb-1">Dokumen Lampiran</p>
                                            <p class="text-xs text-gray-500 mb-4">Bukti prestasi tersimpan dalam format file.</p>
                                            <div class="flex flex-col space-y-2">
                                                <a href="{{ asset('storage/' . $prestasi->bukti_path) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                                    <i class="fas fa-external-link-alt mr-2"></i> Buka File
                                                </a>
                                                <a href="{{ asset('storage/' . $prestasi->bukti_path) }}" download class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-bold text-white hover:bg-blue-700 transition-all duration-200">
                                                    <i class="fas fa-download mr-2"></i> Unduh File
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                            @else
                                <div class="text-center py-10">
                                    <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                        <i class="fas fa-file-excel text-2xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">Tidak ada file yang diunggah</p>
                                </div>
                            @endif

                            @if($prestasi->status_validasi != 'disetujui')
                                <div class="mt-8 pt-6 border-t border-gray-100">
                                    <a href="{{ route('mahasiswa.prestasi.edit', $prestasi) }}" class="flex items-center justify-center w-full px-4 py-3 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition-colors shadow-sm">
                                        <i class="fas fa-edit mr-2"></i> Edit Data Prestasi
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</x-app-layout>



