@extends('layouts.pengurus')

@section('title', 'Kelola Pengurus')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Kelola Pengurus
            </h1>
            <p class="mt-1 text-sm text-gray-500">Manajemen data pengurus organisasi.</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('pengurus.pengurus.create') }}" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs lg:text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                <i class="fas fa-plus mr-2"></i> Tambah Pengurus
            </a>
        </div>
    </div>

    <!-- Search Section -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <form action="{{ route('pengurus.pengurus.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            
            {{-- Filter Divisi --}}
            <div class="relative w-full lg:w-1/4">
                <select name="id_divisi" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Semua Divisi</option>
                    @foreach($divisi as $d)
                        <option value="{{ $d->id_divisi }}" {{ request('id_divisi') == $d->id_divisi ? 'selected' : '' }}>
                            {{ $d->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Semester --}}
            <div class="relative w-full lg:w-1/4">
                <select name="semester" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Semua Semester</option>
                    @foreach($semesters as $smt)
                        <option value="{{ $smt }}" {{ request('semester') == $smt ? 'selected' : '' }}>
                            Semester {{ $smt }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="relative w-full lg:w-1/2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Cari nama, divisi, atau jabatan...">
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Cari
                </button>
                @if(request('search') || request('id_divisi') || request('semester'))
                <a href="{{ route('pengurus.pengurus.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded-r" role="alert">
        <div class="flex">
            <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#0d2149]">
                    <tr>
                        <th scope="col" class="px-3 py-2 lg:px-6 lg:py-3 text-left text-xs lg:text-sm font-medium text-white uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-6 lg:py-3 text-left text-xs lg:text-sm font-medium text-white uppercase tracking-wider">
                            Nama
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-6 lg:py-3 text-left text-xs lg:text-sm font-medium text-white uppercase tracking-wider">
                            Divisi
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-6 lg:py-3 text-left text-xs lg:text-sm font-medium text-white uppercase tracking-wider">
                            Semester
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-6 lg:py-3 text-left text-xs lg:text-sm font-medium text-white uppercase tracking-wider">
                            Posisi Jabatan
                        </th>

                        <th scope="col" class="px-3 py-2 lg:px-6 lg:py-3 text-center text-xs lg:text-sm font-medium text-white uppercase tracking-wider w-48">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengurus as $p)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-3 py-2 lg:px-6 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-3 py-2 lg:px-6 lg:py-4 whitespace-nowrap text-xs lg:text-sm font-medium text-gray-900">
                                @if($p->user && $p->user->mahasiswa)
                                    <div>{{ $p->user->mahasiswa->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $p->user->mahasiswa->nim }}</div>
                                @else
                                    {{ $p->user->nama ?? '-' }}
                                @endif
                            </td>
                            <td class="px-3 py-2 lg:px-6 lg:py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-[10px] lg:text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $p->divisi->nama_divisi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 lg:px-6 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-500">
                                {{ $p->user->mahasiswa->semester ?? '-' }}
                            </td>
                            <td class="px-3 py-2 lg:px-6 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-500">
                                {{ $p->jabatan->nama_jabatan ?? '-' }}
                            </td>

                            <td class="px-3 py-2 lg:px-6 lg:py-4 whitespace-nowrap text-center text-xs lg:text-sm font-medium">
                                <div class="flex justify-center space-x-1 lg:space-x-2">
                                    {{-- Edit --}}
                                    <a href="{{ route('pengurus.pengurus.edit', $p->id_pengurus) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-lg transition-colors duration-200 bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit">
                                        <i class="fas fa-pencil-alt text-xs lg:text-sm mr-1 lg:mr-2"></i> Edit
                                    </a>
                                    
                                    {{-- Delete --}}
                                    <form action="{{ route('pengurus.pengurus.destroy', $p->id_pengurus) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-lg transition-colors duration-200 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white" title="Hapus">
                                            <i class="fas fa-trash text-xs lg:text-sm mr-1 lg:mr-2"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users-slash text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium">Belum ada data pengurus.</p>
                                    <p class="text-sm">Silakan tambahkan data pengurus baru.</p>
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
        {{ $pengurus->links() }}
    </div>
</div>
@endsection