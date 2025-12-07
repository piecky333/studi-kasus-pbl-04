@extends('layouts.admin')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid px-4 mt-6">
    
    {{-- Header Section --}}
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Data Mahasiswa
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola data mahasiswa universitas dengan mudah.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.datamahasiswa.create') }}" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 border border-transparent rounded-md shadow-sm text-xs lg:text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Tambah Mahasiswa
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mb-6 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center mb-4">
            <i class="fas fa-filter text-indigo-600 mr-2"></i>
            <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian Data</h3>
        </div>
        <form action="{{ route('admin.datamahasiswa.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                {{-- Filter Semester --}}
                <div class="md:col-span-4">
                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Filter Semester</label>
                    <div class="relative">
                        <select name="semester" id="semester" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm" onchange="this.form.submit()">
                            <option value="">Semua Semester</option>
                            @for ($i = 1; $i <= 14; $i++)
                                <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Filter NIM --}}
                <div class="md:col-span-5">
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">Cari NIM</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="nim" id="nim" value="{{ request('nim') }}" placeholder="Masukkan NIM..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-3 flex space-x-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cari
                    </button>
                    <a href="{{ route('admin.datamahasiswa.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center" role="alert">
            <i class="fas fa-check-circle mr-2 text-lg"></i>
            <span>{{ session('success') }}</span>
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
                            NIM
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            Nama Mahasiswa
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            Semester
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-center font-medium uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($mahasiswa as $index => $mhs)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 text-xs lg:text-sm">
                            {{-- No --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3 text-left text-gray-500 font-medium">
                                {{ $loop->iteration + ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() }}
                            </td>
                            
                            {{-- NIM --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3">
                                <span class="inline-block px-2 py-1 font-bold rounded-md ">
                                    {{ $mhs->nim }}
                                </span>
                            </td>
                            
                            {{-- Nama --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-800">{{ $mhs->nama }}</span>
                                </div>
                            </td>
                            
                            {{-- Semester --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3 text-center">
                                <span class="inline-flex items-center justify-center h-5 w-5 lg:h-6 lg:w-6 rounded-full bg-gray-100 text-gray-600 text-[10px] lg:text-xs font-bold border border-gray-200">
                                    {{ $mhs->semester }}
                                </span>
                            </td>
                            
                            {{-- Email --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3 text-gray-600">
                                {{ $mhs->email }}
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3 text-center">
                                <div class="flex justify-center space-x-1 lg:space-x-2">
                                    {{-- View --}}
                                    <a href="{{ route('admin.datamahasiswa.show', $mhs->id_mahasiswa) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white" title="Detail">
                                        <i class="fas fa-eye mr-1 lg:mr-2"></i> Detail
                                    </a>
                                    
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.datamahasiswa.edit', $mhs->id_mahasiswa) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit">
                                        <i class="fas fa-pencil-alt mr-1 lg:mr-2"></i> Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.datamahasiswa.destroy', $mhs->id_mahasiswa) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
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
                                    <i class="fas fa-user-graduate text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium">Belum ada data mahasiswa</p>
                                    <p class="text-sm">Silakan tambahkan data mahasiswa baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($mahasiswa) && $mahasiswa->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $mahasiswa->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection