<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->nama }}!</h2>
                    <p class="mt-2 text-gray-600">
                        Ini adalah halaman utama Anda. Anda dapat melihat status pengaduan dan berita terbaru di sini.
                    </p>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center space-x-4">
                        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18V7.875c0-.621.504-1.125 1.125-1.125H6.75" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pengaduan Anda</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $totalPengaduan }}
                            </p>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center space-x-4">
                        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pengaduan Diproses</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $totalPengaduan }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center space-x-4">
                        <div class="bg-green-100 text-green-600 p-3 rounded-full">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pengaduan Selesai</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $pengaduanSelesai }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaduan Terakhir Anda</h3>
                        <a href="{{ route('pengaduan.create') }}"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            Buat Pengaduan Baru
                        </a>
                    </div>

                    {{-- pengaduan --}}
                    <div class="p-6">
                        @if($pengaduan->isEmpty())
                            <p class="text-gray-500">Anda belum membuat pengaduan.</p>
                        @else
                            {{-- status pengaduan --}}
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Judul</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal Dibuat</th>
                                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span>
                                            </th>
                                        </tr>
                                    </thead>


                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($pengaduan as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ Str::limit($item->judul, 40) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{-- Logika Status Badge (Sudah Benar) --}}
                                                    @if($item->status == 'Diproses')
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Diproses
                                                        </span>
                                                    @elseif($item->status == 'Selesai')
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Selesai
                                                        </span>
                                                    @elseif($item->status == 'Ditolak')
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Ditolak
                                                        </span>
                                                    @else {{-- Statusnya 'Terkirim' --}}
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Terkirim
                                                        </span>
                                                    @endif

                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $item->created_at->format('d M Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                                    <!-- Selalu ada link Detail -->
                                                    <a href="{{ route('user.pengaduan.show', $item->id_pengaduan) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 mr-4">Detail</a>

                                                    {{-- Tombol Hapus HANYA muncul jika statusnya masih 'Terkirim' --}}
                                                    @if ($item->status == 'Terkirim')
                                                        <form action="{{ route('user.pengaduan.destroy', $item->id_pengaduan) }}"
                                                            method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini? Tindakan ini tidak dapat dibatalkan.')">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-200 text-right">
                    <a href="{{ route('pengaduan.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">
                        Lihat Semua Riwayat Pengaduan &rarr;
                    </a>
                </div>

                </div>

                {{-- title berita --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Berita & Pengumuman</h3>
                    </div>

                    {{-- berita  list--}}
                    <div class="p-6 space-y-4">
                        @if($berita->isEmpty())
                            <p class="text-gray-500">Belum ada berita terbaru.</p>
                        @else
                            @foreach($berita as $item)
                                <div class="border-b pb-4">
                                    <a href="{{ route('berita.show', $item->id_berita) }}"
                                        class="font-semibold text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">
                                        {{ $item->judul }}
                                    </a>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($item->isi, 100) }}</p>
                                    <span
                                        class="text-xs text-gray-400 block mt-2">{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>