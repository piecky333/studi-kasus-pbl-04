@extends('layouts.pengurus')

@section('title', 'Manajemen Berita')

@section('content')
<div class="container-fluid px-4 mt-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                Manajemen Berita Saya
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola daftar berita yang Anda buat.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('pengurus.berita.create') }}" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 border border-transparent rounded-md shadow-sm text-xs lg:text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Tambah Berita Baru
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mb-6 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center mb-4">
            <i class="fas fa-filter text-indigo-600 mr-2"></i>
            <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian</h3>
        </div>
        <form action="{{ route('pengurus.berita.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                {{-- Cari Judul --}}
                <div class="md:col-span-3">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Judul</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 right-0 pl-3 flex items-center pointer-events-none text-sm">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Masukkan Judul..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border border-gray-300 rounded-md py-2 text-gray-900 pr-2">
                    </div>
                </div>

                {{-- Filter Status --}}
                <div class="md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
                    <select name="status" id="status" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:text-gray-900 sm:text-sm rounded-md shadow-sm {{ request('status') ? 'text-gray-900' : 'text-gray-400' }} px-2" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                {{-- Filter Kategori --}}
                <div class="md:col-span-3">
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Filter Kategori</label>
                    <select name="kategori" id="kategori" class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:text-gray-900 sm:text-sm rounded-md shadow-sm {{ request('kategori') ? 'text-gray-900' : 'text-gray-400' }}" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        <option value="kegiatan" {{ request('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="prestasi" {{ request('kategori') == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                    </select>
                </div>

                {{-- Filter Tanggal --}}
                <div class="md:col-span-4 grid grid-cols-2 gap-2">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="focus:ring-indigo-500 focus:border-indigo-500 focus:text-gray-900 block w-full sm:text-sm border border-gray-300 rounded-md py-2 {{ request('start_date') ? 'text-gray-900' : 'text-gray-400' }} px-2 shadow-sm">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="focus:ring-indigo-500 focus:border-indigo-500 focus:text-gray-900 block w-full sm:text-sm border border-gray-300 rounded-md py-2 {{ request('end_date') ? 'text-gray-900' : 'text-gray-400' }} px-2 shadow-sm">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-2 flex space-x-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cari
                    </button>
                    <a href="{{ route('pengurus.berita.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
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
    <div class="overflow-x-auto bg-white shadow-[4px_4px_10px_rgba(0,0,0,0.1)] rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0d2149] text-white text-xs lg:text-sm uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                        No
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                        Judul
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                        Kategori
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                        Tanggal Publikasi
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                        Diproses Oleh
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-center font-medium uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($beritas as $index => $berita)
                    <tr class="hover:bg-gray-50 transition-colors duration-150 text-xs lg:text-sm">
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 font-medium text-gray-900 whitespace-normal" title="{{ $berita->judul_berita }}">
                            {{ Str::limit($berita->judul_berita, 50) }}
                        </td>
                         <td class="px-3 py-2 lg:px-6 lg:py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-[10px] lg:text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($berita->kategori) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                            {{ $berita->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap">
                            @if($berita->status == 'pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] lg:text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @elseif($berita->status == 'verified')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] lg:text-xs font-medium bg-green-100 text-green-800">
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] lg:text-xs font-medium bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                            @if($berita->status == 'verified')
                                <span class="text-green-600 font-medium" title="Disetujui"><i class="fas fa-check-circle mr-1"></i> {{ $berita->verifikator->nama ?? '-' }}</span>
                            @elseif($berita->status == 'rejected')
                                <span class="text-red-600 font-medium" title="Ditolak"><i class="fas fa-times-circle mr-1"></i> {{ $berita->penolak->nama ?? '-' }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-center font-medium space-x-1 lg:space-x-2">
                            {{-- Edit --}}
                            <a href="{{ route('pengurus.berita.edit', $berita->id_berita) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit">
                                <i class="fas fa-pencil-alt mr-1 lg:mr-2"></i> Edit
                            </a>

                            <form action="{{ route('pengurus.berita.destroy', $berita->id_berita) }}" method="POST" class="d-inline inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white" title="Hapus">
                                    <i class="fas fa-trash mr-1 lg:mr-2"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bi bi-newspaper text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-medium">Belum ada berita.</p>
                                <p class="text-sm">Silakan tambahkan berita baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination (if applicable) -->
    @if(method_exists($beritas, 'links'))
        <div class="px-4 py-3 border-t border-gray-200 sm:px-6 mb-3">
            {{ $beritas->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
