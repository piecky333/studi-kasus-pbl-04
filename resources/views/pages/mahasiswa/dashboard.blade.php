<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Sanksi (Specific to Mahasiswa) --}}
            @if(isset($sanksi) && $sanksi->count() > 0)
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 shadow-sm rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            {{-- Heroicon: Exclamation Triangle --}}
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
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
                                                        <a href="{{ asset('storage/' . $item->file_pendukung) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                                            Lihat Bukti
                                                        </a>
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

            {{-- Welcome Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div>
                        <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->nama }}!</h2>
                        <p class="mt-2 text-gray-600">
                            Ini adalah halaman utama mahasiswa. Anda dapat melakukan pengajuan sertifikat, melihat status pengaduan, dan berita terbaru.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('mahasiswa.sertifikat.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kelola Sertifikat
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Pengaduan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center space-x-4">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                            {{-- Heroicon: Clipboard Document List --}}
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75c0-.231-.035-.454-.1-.664M6.75 7.5H18a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18V9.75A2.25 2.25 0 015.25 7.5H6.75" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pengaduan</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $totalPengaduan ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pengaduan Diproses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center space-x-4">
                        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                            {{-- Heroicon: Clock --}}
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pengaduan Diproses</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $pengaduanDiproses ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pengaduan Selesai -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center space-x-4">
                        <div class="bg-green-100 text-green-600 p-3 rounded-full">
                            {{-- Heroicon: Check Circle --}}
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pengaduan Selesai</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $pengaduanSelesai ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content (Table & News) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Pengaduan List -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaduan Terakhir Anda</h3>
                        <a href="{{ route('mahasiswa.pengaduan.create') }}"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            Buat Pengaduan Baru
                        </a>
                    </div>

                    <div class="p-6">
                        @if($pengaduan->isEmpty())
                            <p class="text-gray-500">Anda belum membuat pengaduan.</p>
                        @else
                            <div class="overflow-x-auto">   
                            <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-blue-100">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider">
                                                Judul</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($pengaduan as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ Str::limit($item->judul, 40) }}
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @php
                                                        $statusBersih = trim($item->status);
                                                    @endphp

                                                    @if(strcasecmp($statusBersih, 'Diproses') == 0)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Diproses</span>
                                                    @elseif(strcasecmp($statusBersih, 'Selesai') == 0)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                                    @elseif(strcasecmp($statusBersih, 'Ditolak') == 0)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                                    @elseif(strcasecmp($statusBersih, 'Terkirim') == 0)
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Terkirim</span>
                                                    @else
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $statusBersih ?: 'N/A' }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $item->created_at->format('d M Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    @php
                                                        // Cek apakah ada balasan terakhir dari admin
                                                        // Note: Assuming 'tanggapan' relationship exists in Pengaduan model
                                                        $lastTanggapan = $item->tanggapan ? $item->tanggapan->sortBy('created_at')->last() : null;
                                                        $hasReply = $lastTanggapan && $lastTanggapan->id_admin;
                                                    @endphp
                                                    <a href="{{ route('mahasiswa.pengaduan.show', $item->id_pengaduan) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                        {{ $hasReply ? 'Lihat balasan' : 'Detail' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 bg-gray-50 border-t border-gray-200 text-right">
                        <a href="{{ route('mahasiswa.pengaduan.index') }}"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out ">
                            Lihat Semua Riwayat Pengaduan &rarr;
                        </a>
                    </div>
                </div>

                <!-- Berita & Pengumuman -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Berita & Pengumuman</h3>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @if($beritaTerbaru->isEmpty())
                            <p class="text-gray-500 p-6">Belum ada berita terbaru.</p>
                        @else
                            @foreach($beritaTerbaru as $item)
                                <a href="{{ route('berita.show', $item) }}"
                                   class="block p-6 hover:bg-gray-50 transition duration-150 ease-in-out">
                                    
                                    <div class="flex items-center space-x-4">
                                        
                                        <div class="flex-shrink-0">
                                            @if($item->gambar_berita)
                                                <img class="h-16 w-16 rounded-lg object-cover shadow-sm" 
                                                     src="{{ asset('storage/' . $item->gambar_berita) }}" 
                                                     alt="{{ $item->judul_berita }}"> 
                                            @else
                                                <div class="h-16 w-16 rounded-lg bg-gray-200 flex items-center justify-center text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-blue-600 hover:text-blue-800 truncate">
                                                {{ $item->judul_berita }} 
                                            </h4>
                                            
                                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                                {{ Str::limit($item->isi, 100) }}
                                            </p>
                                            
                                            <span class="text-xs text-gray-400 block mt-2">
                                                {{ \Carbon\Carbon::parse($item->created_at)->locale('id')->diffForHumans() }}
                                            </span>
                                        </div>

                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-200 text-right">
                        <a href="{{ route('berita.index') }}"
                           class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                            Lihat Semua Berita &rarr;
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
