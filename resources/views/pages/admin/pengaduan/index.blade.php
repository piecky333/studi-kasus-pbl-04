@extends('layouts.admin')

@section('title', 'Manajemen Pengaduan')

@section('content')
<div class="container-fluid px-4 mt-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0 ">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate ">
                Manajemen Pengaduan
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola semua pengaduan yang masuk dari mahasiswa.
            </p>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mb-6 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center mb-4">
            <i class="fas fa-filter text-indigo-600 mr-2"></i>
            <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian Pengaduan</h3>
        </div>
        <form action="{{ route('admin.pengaduan.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                {{-- Sort By --}}
                <div class="md:col-span-4">
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select name="sort" id="sort" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>

                {{-- Filter Status --}}
                <div class="md:col-span-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                    <select name="status" id="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Terkirim" {{ request('status') == 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-4 flex space-x-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cari
                    </button>
                    <a href="{{ route('admin.pengaduan.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Alert/Pesan Sukses --}}
    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6 border-l-4 border-green-400">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white shadow-[4px_4px_10px_rgba(0,0,0,0.1)] rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#0d2149] text-white text-xs lg:text-sm uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Pelapor
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Judul Pengaduan
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-center font-medium uppercase tracking-wider ">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($daftarPengaduan as $item)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out text-xs lg:text-sm">
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                                        {{ $loop->iteration + $daftarPengaduan->firstItem() - 1 }}
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-6 w-6 lg:h-8 lg:w-8">
                                                <img class="h-6 w-6 lg:h-8 lg:w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($item->user->nama ?? 'User') }}&background=random" alt="">
                                            </div>
                                            <div class="ml-2 lg:ml-3">
                                                <div class="font-medium text-gray-900">
                                                    {{ $item->user->nama ?? '[User Dihapus]' }}
                                                </div>
                                                <div class="text-gray-500 text-[10px] lg:text-xs">
                                                    {{ $item->user->email ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-normal">
                                        <div class="text-gray-900 font-medium">{{ Str::limit($item->judul, 30) }}</div>
                                        <div class="text-[10px] lg:text-xs text-gray-500">{{ $item->jenis_kasus }}</div>
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap">
                                        @if ($item->status == 'Terkirim')
                                            <span class="px-2 inline-flex text-[10px] lg:text-xs leading-4 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Terkirim
                                            </span>
                                        @elseif ($item->status == 'Diproses')
                                            <span class="px-2 inline-flex text-[10px] lg:text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Diproses
                                            </span>
                                        @elseif ($item->status == 'Selesai')
                                            <span class="px-2 inline-flex text-[10px] lg:text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                                Selesai
                                            </span>
                                        @elseif ($item->status == 'Ditolak')
                                            <span class="px-2 inline-flex text-[10px] lg:text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                                        {{ $item->created_at->format('d M Y') }}
                                        <br>
                                        <span class="text-[10px] lg:text-xs">{{ $item->created_at->format('H:i') }}</span>
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap font-medium">
                                        <div class="flex justify-center space-x-1 lg:space-x-2">    
                                            {{-- View --}}
                                            <a href="{{ route('admin.pengaduan.show', $item->id_pengaduan) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white" title="Detail">
                                                <i class="fas fa-eye mr-1 lg:mr-2"></i> Detail
                                            </a>    

                                            {{-- Delete --}}
                                            <form action="{{ route('admin.pengaduan.destroy', $item->id_pengaduan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white" title="Hapus">
                                            <i class="fas fa-trash mr-1 lg:mr-2"></i> Hapus
                                        </button>
                                    </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-inbox text-4xl mb-2 text-gray-300"></i>
                                            <p class="text-lg font-medium">Belum ada data pengaduan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 mb-3">
        {{ $daftarPengaduan->withQueryString()->links() }}
    </div>
</div>
@endsection
