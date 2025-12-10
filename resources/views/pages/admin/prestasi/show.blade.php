@extends('layouts.admin')

@section('title', 'Detail Prestasi Mahasiswa')

@section('content')
@section('content')
<div class="container-fluid px-4 mt-6" x-data="{ showModal: false, imgUrl: '' }">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('admin.prestasi.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">Data Prestasi</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Detail Prestasi</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Column: Student Profile Card -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                    <h3 class="text-white font-semibold text-lg flex items-center">
                        <i class="fas fa-user-graduate mr-3"></i> Profil Mahasiswa
                    </h3>
                </div>
                <div class="p-6 text-center">
                    <div class="inline-block relative mb-4">
                        <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md mx-auto" 
                             src="{{ $prestasi->mahasiswa->user?->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($prestasi->mahasiswa->nama ?? 'M') . '&color=7F9CF5&background=EBF4FF&size=128' }}" 
                             alt="{{ $prestasi->mahasiswa->nama }}"
                             referrerpolicy="no-referrer">
                    </div>
                    
                    <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $prestasi->mahasiswa->nama ?? 'Unknown' }}</h2>
                    <p class="text-sm text-gray-500 font-medium mb-4">{{ $prestasi->mahasiswa->nim ?? '-' }}</p>
                    
                    <div class="border-t border-gray-100 pt-4 text-left space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600"><i class="fas fa-graduation-cap w-5 text-gray-400"></i> Prodi</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $prestasi->mahasiswa->prodi->nama_prodi ?? 'TI' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600"><i class="fas fa-layer-group w-5 text-gray-400"></i> Semester</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $prestasi->mahasiswa->semester ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600"><i class="fas fa-envelope w-5 text-gray-400"></i> Email</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $prestasi->mahasiswa->email ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100">
                         <a href="{{ route('admin.datamahasiswa.show', $prestasi->mahasiswa->id_mahasiswa) }}" class="block w-full text-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Lihat Profil Lengkap
                         </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Prestasi Details -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden h-full">
                <!-- Header Card -->    
                <div class="bg-white px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-bold text-gray-900">
                            Detail Prestasi
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Informasi lengkap mengenai prestasi yang diraih.
                        </p>
                    </div>
                    @php
                        $badgeColor = match($prestasi->tingkat_prestasi) {
                            'Internasional' => 'bg-purple-100 text-purple-800',
                            'Nasional' => 'bg-rose-100 text-rose-800',
                            'Provinsi' => 'bg-orange-100 text-orange-800',
                            'Kabupaten/Kota' => 'bg-yellow-100 text-yellow-800',
                            'Universitas' => 'bg-blue-100 text-blue-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badgeColor }}">
                        {{ ucfirst($prestasi->tingkat_prestasi) }}
                    </span>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul Kegiatan -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Nama Kegiatan/Prestasi</label>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1 mr-3">
                                    <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600">
                                        <i class="fas fa-trophy text-xl"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-gray-900">{{ $prestasi->nama_kegiatan }}</p>
                                    <span class="text-sm text-gray-500">{{ $prestasi->jenis_prestasi ?? 'Umum' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tahun -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Tahun Perolehan</label>
                            <p class="text-base font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i> {{ $prestasi->tahun }}
                            </p>
                        </div>

                        <!-- Status Validasi -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status Validasi</label>
                            @if($prestasi->status_validasi == 'disetujui')
                                <span class="inline-flex items-center text-green-700 font-medium">
                                    <i class="fas fa-check-circle mr-2"></i> Disetujui
                                </span>
                            @elseif($prestasi->status_validasi == 'ditolak')
                                <span class="inline-flex items-center text-red-700 font-medium">
                                    <i class="fas fa-times-circle mr-2"></i> Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center text-yellow-700 font-medium">
                                    <i class="fas fa-clock mr-2"></i> Menunggu
                                </span>
                            @endif
                        </div>

                        <!-- Deskripsi -->
                        <div class="md:col-span-2 bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Deskripsi/Keterangan</label>
                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ $prestasi->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                        <!-- Bukti File -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Bukti Prestasi</label>
                            @if($prestasi->bukti_path)
                                @php
                                    $extension = pathinfo($prestasi->bukti_path, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                                @endphp

                                @if($isImage)
                                    <div class="mt-2">
                                        <a href="#" @click.prevent="showModal = true; imgUrl = '{{ asset('storage/' . $prestasi->bukti_path) }}'" class="group block w-fit">
                                            <div class="relative rounded-lg overflow-hidden border border-gray-200 shadow-sm transition-transform duration-300 transform group-hover:scale-105">
                                                <img src="{{ asset('storage/' . $prestasi->bukti_path) }}" alt="Bukti Prestasi" class="h-48 object-cover rounded-lg">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity flex items-center justify-center">
                                                    <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 text-2xl drop-shadow-md"></i>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> Klik gambar untuk memperbesar</p>
                                        </a>
                                    </div>
                                @else
                                    <div class="flex items-center p-4 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-file-alt text-red-500 text-2xl mr-4"></i>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">File Bukti Prestasi</p>
                                            <p class="text-xs text-gray-500">Klik untuk melihat atau mengunduh</p>
                                        </div>
                                        <a href="{{ asset('storage/' . $prestasi->bukti_path) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-download mr-2"></i> Unduh
                                        </a>
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada file bukti yang diunggah.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-gray-200">
                    <a href="{{ route('admin.prestasi.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kembali
                    </a>
                    
                    <a href="{{ route('admin.prestasi.edit', $prestasi->id_prestasi) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-amber-500 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                        <i class="fas fa-pencil-alt mr-2"></i> Edit 
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75" @click="showModal = false"></div>
            </div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Bukti Prestasi</h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="flex justify-center items-center bg-gray-100 rounded-lg p-2">
                        <img :src="imgUrl" class="max-h-[60vh] max-w-full object-contain rounded" alt="Bukti Prestasi Full">
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:justify-end gap-3 transition-colors">
                    <button type="button" class="w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm order-2 sm:order-1" @click="showModal = false">
                        Tutup
                    </button>
                    <a :href="imgUrl" download class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm order-1 sm:order-2">
                        <i class="fas fa-download mr-2"></i> Unduh Gambar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
