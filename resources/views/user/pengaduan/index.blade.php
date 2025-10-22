{{-- Halaman ini menggunakan layout aplikasi utama --}}
<x-app-layout>
    <!-- Slot Header (Judul Halaman) -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Pengaduan Saya') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                &larr;
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Header Konten: Judul dan Tombol Aksi Utama -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Semua Pengaduan Anda
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Kelola dan lihat riwayat semua pengaduan yang pernah Anda kirimkan.
                            </p>
                        </div>

                        <!-- ===== HANYA TOMBOL AKSI UTAMA YANG TERSISA DI SINI ===== -->
                        <div class="mt-4 sm:mt-0">
                            <a href="{{ route('pengaduan.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                + Buat Pengaduan Baru
                            </a>
                        </div>
                    </div>

                    <!-- Fitur Pencarian -->
                    <div class="mb-4">
                        <form action="{{ route('pengaduan.index') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="search" placeholder="Cari berdasarkan judul pengaduan..."
                                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pl-10"
                                    value="{{ request('search') }}">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ... (Sisa kode tabel dan paginasi biarkan sama) ... -->

                    <!-- Tabel Daftar Pengaduan -->
                    <div class="overflow-x-auto border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Judul</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pengaduan as $p)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $loop->iteration + ($pengaduan->currentPage() - 1) * $pengaduan->perPage() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ Str::limit($p->judul, 40) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $p->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($p->status == 'Diproses')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Diproses</span>
                                            @elseif($p->status == 'Selesai')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                            @elseif($p->status == 'Ditolak')
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Terkirim</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <a href="{{ route('pengaduan.show', $p->id_pengaduan) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                            @if($p->status == 'Terkirim')
                                                <form action="{{ route('pengaduan.destroy', $p->id_pengaduan) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            Tidak ada pengaduan yang cocok dengan pencarian Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Link Paginasi -->
                    <div class="mt-4">
                        {{ $pengaduan->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>