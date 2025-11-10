<x-app-layout>
    <!-- Slot Header (Judul Halaman) -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengaduan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">

                    <!-- Bagian Header Detail -->
                    <div class="border-b border-gray-200 pb-5 mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div>
                                <p class="text-sm font-medium text-indigo-600">{{ $pengaduan->jenis_kasus }}</p>
                                <h3 class="text-2xl leading-6 font-bold text-gray-900 mt-1">
                                    {{ $pengaduan->judul }}
                                </h3>
                                <p class="mt-2 max-w-4xl text-sm text-gray-500">
                                    Dilaporkan oleh: <strong>{{ $pengaduan->user->nama }}</strong>
                                </p>
                            </div>
                            <div class="mt-4 sm:mt-0">
                                @if($pengaduan->status == 'Diproses')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Diproses</span>
                                @elseif($pengaduan->status == 'Selesai')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                @elseif($pengaduan->status == 'Ditolak')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                @else {{-- Terkirim --}}
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Terkirim</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Konten -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <!-- Kolom Kiri (Deskripsi Lengkap) -->
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-medium text-gray-800 mb-2">Deskripsi Kronologi Kejadian</h4>
                            <div class="prose max-w-none text-gray-600">
                                {{-- nl2br() digunakan untuk mengubah baris baru (\n) menjadi tag <br> agar format paragraf terjaga --}}
                                {!! nl2br(e($pengaduan->deskripsi)) !!}
                            </div>
                        </div>
                        
                        <!-- Kolom Kanan (Metadata) -->
                        <div class="md:col-span-1">
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <h4 class="text-lg font-medium text-gray-800 mb-4">Informasi Laporan</h4>
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">ID Laporan</dt>
                                        <dd class="text-sm text-gray-900">#{{ $pengaduan->id_pengaduan }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                        <dd class="text-sm text-gray-900">{{ $pengaduan->created_at->format('d F Y, H:i') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="text-sm font-semibold text-gray-900">{{ $pengaduan->status }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                    </div>

                    <!-- Tombol Aksi di Bagian Bawah -->
                    <div class="border-t border-gray-200 mt-8 pt-5">
                        <div class="flex justify-end">
                            <a href="{{ url()->previous() }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Kembali
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
