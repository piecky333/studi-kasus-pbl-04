@extends('layouts.admin')

@section('title', 'Manajemen Sanksi')

@section('content')
<div class="container-fluid px-4 mt-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Manajemen Sanksi
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola data sanksi mahasiswa.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.sanksi.create') }}" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 border border-transparent rounded-md shadow-sm text-xs lg:text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Tambah Sanksi Baru
            </a>
        </div>
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
                                    Nama Mahasiswa
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Jenis Sanksi
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Jenis Hukuman
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Keterangan
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-center font-medium uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($sanksi as $index => $item)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out text-xs lg:text-sm">
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                                        {{ $sanksi->firstItem() + $index }}
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-6 w-6 lg:h-8 lg:w-8">
                                                <img class="h-6 w-6 lg:h-8 lg:w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($item->mahasiswa->nama ?? 'M') }}&background=random" alt="">
                                            </div>
                                            <div class="ml-2 lg:ml-3">
                                                <div class="font-medium text-gray-900">
                                                    {{ $item->mahasiswa->nama ?? '[Mahasiswa Dihapus]' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap">
                                        @if($item->jenis_sanksi == 'Ringan')
                                            <span class="px-2 inline-flex text-[10px] lg:text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $item->jenis_sanksi }}
                                            </span>
                                        @elseif($item->jenis_sanksi == 'Sedang')
                                            <span class="px-2 inline-flex text-[10px] lg:text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $item->jenis_sanksi }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-[10px] lg:text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $item->jenis_sanksi ?? 'Berat' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 text-gray-500">
                                        {{ $item->jenis_hukuman ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 text-gray-500 whitespace-normal">
                                        {{ Str::limit($item->keterangan ?? '-', 50) }}
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                                        {{ $item->tanggal_sanksi ? \Carbon\Carbon::parse($item->tanggal_sanksi)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-center font-medium">
                                        <div class="flex justify-center space-x-1 lg:space-x-2">
                                            {{-- Edit --}}
                                            <a href="{{ route('admin.sanksi.edit', $item->id_sanksi) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit">
                                                <i class="fas fa-pencil-alt mr-1 lg:mr-2"></i> Edit
                                            </a>
                                            
                                            {{-- Delete --}}
                                            <form action="{{ route('admin.sanksi.destroy', $item->id_sanksi) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
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
                                            <i class="bi bi-clipboard-x text-4xl mb-2 text-gray-300"></i>
                                            <p class="text-lg font-medium">Tidak ada data sanksi untuk saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $sanksi->links() }}
    </div>
</div>
@endsection
