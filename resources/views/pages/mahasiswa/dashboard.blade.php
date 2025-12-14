<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->nama }}!</h2>
                    <p class="mt-2 text-gray-600">
                        Ini adalah halaman utama mahasiswa. Anda dapat melakukan pengajuan sertifikat dan melihat informasi terbaru.
                    </p>
                </div>
            </div>

            {{-- Sanksi Alert --}}
            @if(isset($sanksi) && $sanksi->count() > 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3 w-full">
                            <h3 class="text-lg font-medium text-red-800">Peringatan: Anda Memiliki Catatan Sanksi</h3>
                            <div class="mt-4 overflow-x-auto">
                                <table class="min-w-full divide-y divide-red-200">
                                    <thead class="bg-red-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Jenis Pelanggaran</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Hukuman</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Bukti</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-red-100">
                                        @foreach($sanksi as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_sanksi)->format('d M Y') }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $item->jenis_sanksi }} - {{ $item->keterangan }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900 font-semibold text-red-600">
                                                    {{ $item->jenis_hukuman }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($item->file_pendukung)
                                                        @php
                                                            $ext = pathinfo($item->file_pendukung, PATHINFO_EXTENSION);
                                                            $icon = 'fa-file';
                                                            $color = 'text-gray-500';
                                                            
                                                            if (in_array(strtolower($ext), ['pdf'])) {
                                                                $icon = 'fa-file-pdf';
                                                                $color = 'text-red-500';
                                                            } elseif (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                                                $icon = 'fa-file-image';
                                                                $color = 'text-purple-600';
                                                            }
                                                        @endphp
                                                        <div class="flex items-center justify-between p-2 bg-blue-50 rounded-lg border border-blue-100 max-w-xs">
                                                            {{-- Icon & Filename --}}
                                                            <a href="{{ asset('storage/' . $item->file_pendukung) }}" target="_blank" class="flex items-center flex-1 min-w-0 group cursor-pointer mr-2">
                                                                <div class="flex-shrink-0 mr-2">
                                                                    <i class="fas {{ $icon }} {{ $color }} text-xl group-hover:scale-110 transition-transform"></i>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-xs font-medium text-blue-900 truncate group-hover:text-blue-700 transition-colors" title="{{ basename($item->file_pendukung) }}">
                                                                        {{ Str::limit(basename($item->file_pendukung), 20) }}
                                                                    </p>
                                                                </div>
                                                            </a>
                                                            {{-- Download Action --}}
                                                            <div class="flex-shrink-0">
                                                                <a href="{{ asset('storage/' . $item->file_pendukung) }}" download class="text-xs font-bold text-green-600 hover:text-green-800 transition-colors">
                                                                    Unduh
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400 text-xs">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                {{-- Quick Actions - Pengajuan Sertifikat --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                                <i class="fas fa-certificate text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Pengajuan Sertifikat</h3>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm">
                            Ajukan sertifikat prestasi atau kegiatan Anda untuk divalidasi.
                        </p>
                        <a href="{{ route('mahasiswa.sertifikat.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Kelola Sertifikat &rarr;
                        </a>
                    </div>
                </div>

                {{-- Quick Actions - Buat Pengaduan --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="bg-red-100 text-red-600 p-3 rounded-full">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Lapor / Pengaduan</h3>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm">
                            Laporkan masalah atau sampaikan aspirasi Anda kepada pengurus.
                        </p>
                        <a href="{{ route('mahasiswa.pengaduan.create') }}" class="inline-flex items-center text-red-600 hover:text-red-800 font-medium text-sm">
                            Buat Pengaduan &rarr;
                        </a>
                    </div>
                </div>

                {{-- Quick Actions - Lihat Berita --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="bg-green-100 text-green-600 p-3 rounded-full">
                                <i class="fas fa-newspaper text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Berita & Info</h3>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm">
                            Lihat informasi terbaru seputar kegiatan kemahasiswaan.
                        </p>
                        <a href="{{ route('berita.index') }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-medium text-sm">
                            Baca Berita &rarr;
                        </a>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Riwayat Pengaduan --}}
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaduan Terakhir</h3>
                        <a href="{{ route('mahasiswa.pengaduan.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Judul</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($pengaduan as $item)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ Str::limit($item->judul, 30) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->status == 'Terkirim')
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Terkirim</span>
                                            @elseif($item->status == 'Diproses')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Diproses</span>
                                            @elseif($item->status == 'Selesai')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Selesai</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $item->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('mahasiswa.pengaduan.show', $item->id_pengaduan) }}" class="font-medium text-blue-600 hover:underline">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada pengaduan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Berita Terbaru (Vertical List) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-fit">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Berita Terbaru</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($beritaTerbaru as $item)
                            <a href="{{ route('berita.show', $item) }}" class="block p-4 hover:bg-gray-50 transition">
                                <h4 class="font-semibold text-blue-600 text-sm hover:text-blue-800 line-clamp-2">
                                    {{ $item->judul_berita }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->diffForHumans() }}
                                </p>
                            </a>
                        @empty
                            <p class="text-gray-500 p-4 text-sm">Belum ada berita.</p>
                        @endforelse
                    </div>
                </div>

            </div>



        </div>
    </div>
</x-app-layout>
