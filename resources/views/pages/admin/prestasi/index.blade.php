@extends('layouts.admin')

@section('title', 'Data Prestasi Mahasiswa')

@section('content')
<div class="container-fluid px-4 mt-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                Data Prestasi Mahasiswa
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola data prestasi yang diraih oleh mahasiswa.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.prestasi.create') }}" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 border border-transparent rounded-md shadow-sm text-xs lg:text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Tambah Prestasi
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mb-6 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center mb-4">
            <i class="fas fa-filter text-indigo-600 mr-2"></i>
            <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian Prestasi</h3>
        </div>
        <form action="{{ route('admin.prestasi.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                {{-- Cari NIM --}}
                <div class="md:col-span-3">
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">Cari NIM</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="nim" id="nim" value="{{ request('nim') }}" placeholder="NIM..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 text-gray-900">
                    </div>
                </div>

                {{-- Filter Tahun --}}
                <div class="md:col-span-2">
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" id="tahun" class="block w-full pl-2 pr-8 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm {{ request('tahun') ? 'text-gray-900' : 'text-gray-400' }}" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Filter Jenis Prestasi --}}
                <div class="md:col-span-2">
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                    <select name="jenis" id="jenis" class="block w-full pl-2 pr-8 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm {{ request('jenis') ? 'text-gray-900' : 'text-gray-400' }}" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="Akademik" {{ request('jenis') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="Non-Akademik" {{ request('jenis') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                    </select>
                </div>

                {{-- Filter Tingkat --}}
                <div class="md:col-span-3">
                    <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
                    <select name="tingkat" id="tingkat" class="block w-full pl-2 pr-8 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm {{ request('tingkat') ? 'text-gray-900' : 'text-gray-400' }}" onchange="this.form.submit()">
                        <option value="">Semua Tingkat</option>
                        <option value="Internasional" {{ request('tingkat') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                        <option value="Nasional" {{ request('tingkat') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="Provinsi" {{ request('tingkat') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                        <option value="Kabupaten/Kota" {{ request('tingkat') == 'Kabupaten/Kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                        <option value="Universitas" {{ request('tingkat') == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-2 flex space-x-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cari
                    </button>
                    <a href="{{ route('admin.prestasi.index') }}" class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Alert Sukses --}}
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

    {{-- Tabel Prestasi --}}
    <div class="bg-white shadow-[4px_4px_10px_rgba(0,0,0,0.1)] rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#0d2149] text-white text-xs lg:text-sm uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    NIM
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Nama Mahasiswa
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Nama Kegiatan
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Tingkat
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                                    Tahun
                                </th>
                                <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-center font-medium uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($prestasi as $item)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out text-xs lg:text-sm">
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap font-bold">
                                        {{ $item->mahasiswa->nim ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-normal">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-6 w-6 lg:h-8 lg:w-8">
                                                <img class="h-6 w-6 lg:h-8 lg:w-8 rounded-full object-cover" src="{{ $item->mahasiswa->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($item->mahasiswa->nama ?? 'M') . '&color=7F9CF5&background=EBF4FF' }}" alt="">
                                            </div>
                                            <div class="ml-2 lg:ml-3">
                                                <div class="font-medium text-gray-900">
                                                    {{ $item->mahasiswa->nama ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 text-gray-500 whitespace-normal">
                                        {{ $item->nama_kegiatan }}
                                        <br>
                                        <span class="text-[10px] text-gray-400">{{ $item->jenis_prestasi ?? '-' }}</span>
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap">
                                        @php
                                            $badgeColor = match($item->tingkat_prestasi) {
                                                'Internasional' => 'bg-purple-100 text-purple-800',
                                                'Nasional' => 'bg-rose-100 text-rose-800',
                                                'Provinsi' => 'bg-orange-100 text-orange-800',
                                                'Kabupaten/Kota' => 'bg-yellow-100 text-yellow-800',
                                                'Universitas' => 'bg-blue-100 text-blue-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-2 inline-flex text-[10px] lg:text-xs leading-4 font-semibold rounded-full {{ $badgeColor }}">
                                            {{ ucfirst($item->tingkat_prestasi) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-gray-500">
                                        {{ $item->tahun }}
                                    </td>
                                    <td class="px-3 py-2 lg:px-4 lg:py-3 whitespace-nowrap text-center font-medium">
                                        <div class="flex justify-center space-x-1 lg:space-x-2">
                                            {{-- View --}}
                                            <a href="{{ route('admin.prestasi.show', $item->id_prestasi) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white" title="Detail">
                                                <i class="fas fa-eye mr-1 lg:mr-2"></i> Detail
                                            </a>
                                            
                                            {{-- Edit --}}
                                            <a href="{{ route('admin.prestasi.edit', $item->id_prestasi) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit">
                                                <i class="fas fa-pencil-alt mr-1 lg:mr-2"></i> Edit
                                            </a>
                                            
                                            {{-- Delete --}}
                                            <form action="{{ route('admin.prestasi.destroy', $item->id_prestasi) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
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
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-trophy text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-lg font-medium">Belum ada data prestasi.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200 sm:px-6 mb-3">
            {{ $prestasi->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
