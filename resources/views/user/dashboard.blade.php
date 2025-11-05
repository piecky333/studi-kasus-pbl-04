<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert untuk menampilkan pesan sukses setelah update profile --}}
            @if (session('status') === 'profile-updated')
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>Profil Anda berhasil diperbarui.</p>
                </div>
            @endif
             {{-- Alert untuk menampilkan pesan sukses setelah kirim pengaduan --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif


            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    {{-- 
                        Koreksi:
                        Pastikan kolom nama di tabel 'user' Anda memang 'nama'.
                        Jika 'name', ganti Auth::user()->nama menjadi Auth::user()->name
                    --}}
                    <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->nama }}!</h2>
                    <p class="mt-2 text-gray-600">
                        Ini adalah halaman utama Anda. Anda dapat melihat status pengaduan dan berita terbaru di sini.
                    </p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Pengaduan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center space-x-4">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                            {{-- Heroicon: Clipboard Document List --}}
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75c0-.231-.035-.454-.1-.664M6.75 7.5H18a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18V9.75A2.25 2.25 0 015.25 7.5H6.75" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pengaduan Anda</p>
                            {{-- Dinamis: Menampilkan $totalPengaduan --}}
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
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pengaduan Diproses</p>
                            {{-- Dinamis: Menampilkan $pengaduanDiproses --}}
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
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pengaduan Selesai</p>
                            {{-- Dinamis: Menampilkan $pengaduanSelesai --}}
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $pengaduanSelesai ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid (Pengaduan & Berita) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kolom Kiri: Pengaduan Terakhir -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaduan Terakhir Anda</h3>
                        {{-- 
                            Keputusan Tepat:
                            Tombol mengarah ke route 'user.pengaduan.create'
                        --}}
                        <a href="{{ route('user.pengaduan.create') }}"
                           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            Buat Pengaduan Baru
                        </a>
                    </div>

                    <div class="p-6">
                        {{-- Dinamis: Menampilkan $pengaduanTerakhir --}}
                        @if($pengaduanTerakhir->isEmpty())
                            <p class="text-gray-500">Anda belum membuat pengaduan.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($pengaduanTerakhir as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ Str::limit($item->judul, 40) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{-- Logika Status Badge (Gunakan Huruf Besar) --}}
                                                    @if($item->status == 'Diproses')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Diproses</span>
                                                    @elseif($item->status == 'Selesai')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                                    @elseif($item->status == 'Ditolak')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                                    @else {{-- Statusnya 'Terkirim' --}}
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Terkirim</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $item->created_at->format('d M Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    {{-- Link ke Detail Pengaduan User --}}
                                                    <a href="{{ route('user.pengaduan.show', $item->id_pengaduan) }}"
                                                       class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                     {{-- Link ke Halaman Index Pengaduan User --}}
                    <div class="p-6 bg-gray-50 border-t border-gray-200 text-right">
                        <a href="{{ route('user.pengaduan.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                            Lihat Semua Riwayat Pengaduan &rarr;
                        </a>
                    </div>
                </div>

                <!-- Kolom Kanan: Berita Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Berita & Pengumuman</h3>
                    </div>

                    <div class="p-6 space-y-4">
                        {{-- Dinamis: Menampilkan $beritaTerbaru --}}
                        @if($beritaTerbaru->isEmpty())
                            <p class="text-gray-500">Belum ada berita terbaru.</p>
                        @else
                            @foreach($beritaTerbaru as $item)
                                <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                    {{-- 
                                        Keputusan Tepat:
                                        Link berita di dashboard User mengarah ke route publik
                                        atau route khusus user (jika ada).
                                        Saya asumsikan Anda punya route 'berita.show' yang publik.
                                        Jika tidak, Anda perlu membuatnya atau mengarahkannya
                                        ke route lain yang sesuai.
                                    --}}
                                    <a href="#" {{-- Ganti # dengan route detail berita Anda --}}
                                       class="font-semibold text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">
                                        {{ $item->judul }}
                                    </a>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($item->isi, 100) }}</p>
                                    <span class="text-xs text-gray-400 block mt-2">{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                     {{-- Link ke Halaman Index Berita (jika ada) --}}
                     <div class="p-6 bg-gray-50 border-t border-gray-200 text-right">
                        <a href="#" {{-- Ganti # dengan route index berita Anda --}}
                           class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                            Lihat Semua Berita &rarr;
                        </a>
                    </div>
                </div>
            </div> <!-- End Content Grid -->
        </div>
    </div>
</x-app-layout>
