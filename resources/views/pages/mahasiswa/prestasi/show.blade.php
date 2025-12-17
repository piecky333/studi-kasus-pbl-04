<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Prestasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $prestasi->nama_kegiatan }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            {{ $prestasi->jenis_prestasi }} &bull; {{ $prestasi->tahun }}
                        </p>
                    </div>
                    <div>
                         @if($prestasi->status_validasi == 'disetujui')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Disetujui
                            </span>
                        @elseif($prestasi->status_validasi == 'ditolak')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Ditolak
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu Validasi
                            </span>
                        @endif
                    </div>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Tingkat Prestasi</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $prestasi->tingkat_prestasi }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Peringkat / Juara</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold text-indigo-600">{{ $prestasi->juara }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $prestasi->deskripsi ?? '-' }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Bukti Lampiran</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($prestasi->bukti_path)
                                    <div class="border border-gray-200 rounded-md p-4 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-2 text-sm truncate">Bukti Prestasi</span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="{{ asset('storage/' . $prestasi->bukti_path) }}" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">Lihat / Unduh</a>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada bukti diupload.</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="px-4 py-4 sm:px-6 bg-gray-50 flex justify-between">
                    <a href="{{ route('mahasiswa.prestasi.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                        &larr; Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
