@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@section('content')
<div class="container-fluid px-4 mt-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                Manajemen Berita
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola daftar berita dan informasi kampus.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 border border-transparent rounded-md shadow-sm text-xs lg:text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Tambah Berita Baru
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mb-6 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route('admin.berita.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                {{-- Cari Penulis --}}
                <div class="md:col-span-3">
                    <label for="penulis" class="block text-sm font-medium text-gray-700 mb-1">Cari Penulis</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-sm">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="penulis" id="penulis" value="{{ request('penulis') }}" placeholder="Penulis..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border border-gray-300 rounded-md py-2 text-gray-900">
                    </div>
                </div>

                {{-- Filter Status --}}
                <div class="md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="block w-full pl-2 pr-8 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm text-gray-900" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                {{-- Filter Kategori --}}
                <div class="md:col-span-2">
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" id="kategori" class="block w-full pl-2 pr-8 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm text-gray-900" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="kegiatan" {{ request('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="prestasi" {{ request('kategori') == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                    </select>
                </div>

                {{-- Filter Tanggal --}}
                <div class="md:col-span-3">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md py-2 text-gray-900 px-2 shadow-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md py-2 text-gray-900 px-2 shadow-sm">
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-2 flex space-x-2">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Filter
                    </button>
                    <a href="{{ route('admin.berita.index') }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                        Penulis
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                        Verifikator
                    </th>
                    <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-center font-medium uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($beritas as $index => $berita)
                    <tr class="hover:bg-gray-50 transition-colors duration-150 text-xs lg:text-sm cursor-pointer" onclick="window.location='{{ route('admin.berita.show', $berita) }}'">
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 font-semibold text-indigo-600 whitespace-normal">
                            {{ Str::limit($berita->judul_berita, 50) }}
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                            {{ $berita->user->nama ?? 'Unknown' }}
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                            {{ $berita->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap">
                            @if($berita->status == 'pending')
                                <span class="px-2 py-0.5 rounded-full text-[10px] lg:text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($berita->status == 'verified')
                                <span class="px-2 py-0.5 rounded-full text-[10px] lg:text-xs font-medium bg-green-100 text-green-800">Verified</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] lg:text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                            {{ $berita->verifikator->nama ?? ($berita->penolak->nama ?? '-') }}
                        </td>
                        <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-center" onclick="event.stopPropagation()">
                            <div class="flex justify-center space-x-2 lg:space-x-3">
                                {{-- Edit --}}
                                <a href="{{ route('admin.berita.edit', $berita) }}" class="text-amber-600 hover:text-amber-900 transition-colors" title="Edit Berita">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if($berita->status == 'pending')
                                    {{-- Verifikasi --}}
                                    <form action="{{ route('admin.berita.verifikasi', $berita) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="text-green-600 hover:text-green-900 transition-colors" title="Verifikasi">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    {{-- Tolak --}}
                                    <form action="{{ route('admin.berita.tolak', $berita) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="text-gray-500 hover:text-gray-700 transition-colors" title="Tolak Berita">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Delete --}}
                                <form action="{{ route('admin.berita.destroy', $berita) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Hapus Berita">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
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



