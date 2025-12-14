{{-- Halaman ini menggunakan layout aplikasi utama --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Pengaduan Saya') }}
            </h2>
            <a href="{{ route('user.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                &larr;
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <div>
                            <h3 class="text-lg leading-6 font-semibold text-Black-900">
                                Semua Pengaduan Anda
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Kelola dan lihat riwayat semua pengaduan yang pernah Anda kirimkan.
                            </p>
                        </div>

                        <div class="mt-4 sm:mt-0">
                            <a href="{{ Auth::user()->role === 'mahasiswa' ? route('mahasiswa.pengaduan.create') : route('user.pengaduan.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                 Buat Pengaduan Baru 
                            </a>
                        </div>
                    </div>

                    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <form action="{{ route('user.pengaduan.index') }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                {{-- Search --}}
                                <div class="md:col-span-6">
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                                    <div class="relative">
                                        <input type="text" name="search" id="search" placeholder="Cari berdasarkan judul..."
                                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pl-10 text-sm"
                                            value="{{ request('search') }}">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i class="bi bi-search text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- Filter Status --}}
                                <div class="md:col-span-3">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" id="status" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="Terkirim" {{ request('status') == 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>

                                {{-- Sort --}}
                                <div class="md:col-span-3">
                                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                                    <select name="sort" id="sort" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider">
                                        No</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider">
                                        Judul</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-blue-700 uppercase tracking-wider">
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
                                            {{ Str::limit($p->judul, 40) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $p->created_at->format('d M Y') }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                // Bersihkan nilai status untuk perbandingan yang aman
                                                $statusBersih = trim($p->status);
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
                                                {{-- Sekarang 'Terkirim' dicek secara eksplisit --}}
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Terkirim</span>
                                            @else
                                                {{-- Ini untuk menangani data kosong atau tidak valid --}}
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $statusBersih ?: 'N/A' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <a href="{{ route('user.pengaduan.show', $p->id_pengaduan) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Detail</a>

                                            @if(strcasecmp(trim($p->status), 'Terkirim') == 0)
                                                <form action="{{ route('user.pengaduan.destroy', $p->id_pengaduan) }}"
                                                    method="POST" class="inline-block">
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
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="bi bi-inbox text-4xl mb-3 text-gray-300"></i>
                                                <p class="text-lg font-medium text-gray-900">Belum ada pengaduan</p>
                                                <p class="text-sm text-gray-500 mb-4">Anda belum pernah membuat pengaduan apapun.</p>
                                                <a href="{{ route('user.pengaduan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                    <i class="bi bi-plus-lg mr-2"></i> Buat Pengaduan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pengaduan->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>