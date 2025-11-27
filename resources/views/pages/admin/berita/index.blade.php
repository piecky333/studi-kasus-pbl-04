@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@section('content')
<div class="container-fluid px-4 mt-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
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
    <div class="bg-white shadow-[4px_4px_10px_rgba(0,0,0,0.1)] rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
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
                            Tanggal Publikasi
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            Status
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
                            <td class="px-3 py-2 lg:px-4 lg:py-3 font-medium text-gray-900 whitespace-normal">
                                {{ $berita->judul_berita }}
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
                            <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-center font-medium space-x-1 lg:space-x-2">
                                {{-- Edit --}}
                                <a href="{{ route('admin.berita.edit', $berita->id_berita) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit">
                                    <i class="fas fa-pencil-alt mr-1 lg:mr-2"></i> Edit
                                </a>

                                @if($berita->status == 'pending')
                                    <form action="{{ route('admin.berita.verifikasi', $berita->id_berita) }}" method="POST" class="d-inline inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-green-100 text-green-600 hover:bg-green-600 hover:text-white" title="Verifikasi">
                                            <i class="fas fa-check mr-1 lg:mr-2"></i> Verif
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.berita.tolak', $berita->id_berita) }}" method="POST" class="d-inline inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white" title="Tolak">
                                            <i class="fas fa-times mr-1 lg:mr-2"></i> Tolak
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.berita.destroy', $berita->id_berita) }}" method="POST" class="d-inline inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
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
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
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
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $beritas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
