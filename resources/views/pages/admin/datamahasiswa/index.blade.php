@extends('layouts.admin')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid px-4 mt-6">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Data Mahasiswa</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola data mahasiswa universitas dengan mudah.</p>
        </div>
        
        <a href="{{ route('admin.datamahasiswa.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i> Tambah Mahasiswa
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center" role="alert">
            <i class="fas fa-check-circle mr-2 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-white rounded-xl shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden">
        
        {{-- Table Container --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#0d2149] text-white text-sm uppercase tracking-wider">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIM
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Mahasiswa
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Semester
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($mahasiswa as $index => $mhs)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            {{-- No --}}
                            <td class="px-6 py-4 text-left text-gray-500 font-medium">
                                {{ $loop->iteration + ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() }}
                            </td>
                            
                            {{-- NIM --}}
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-1  text-md font-bold rounded-md ">
                                    {{ $mhs->nim }}
                                </span>
                            </td>
                            
                            {{-- Nama --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-800">{{ $mhs->nama }}</span>
                                </div>
                            </td>
                            
                            {{-- Semester --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-100 text-gray-600 text-xs font-bold border border-gray-200">
                                    {{ $mhs->semester }}
                                </span>
                            </td>
                            
                            {{-- Email --}}
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ $mhs->email }}
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    {{-- View --}}
                                    <a href="{{ route('admin.datamahasiswa.show', $mhs->id_mahasiswa) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white" title="Detail">
                                        <i class="fas fa-eye text-sm mr-2"></i> Detail
                                    </a>
                                    
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.datamahasiswa.edit', $mhs->id_mahasiswa) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit">
                                        <i class="fas fa-pencil-alt text-sm mr-2"></i> Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.datamahasiswa.destroy', $mhs->id_mahasiswa) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white" title="Hapus">
                                            <i class="fas fa-trash text-sm mr-2"></i> Hapus
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
                {{ $mahasiswa->links() }}
            </div>
        @endif
    </div>
</div>
@endsection