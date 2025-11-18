@extends('layouts.admin')

@section('content')

<div class="container mx-auto p-4 sm:p-6 lg:p-8">
<div class="flex flex-col">
<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

            {{-- Card/Panel utama --}}
            <div class="bg-white shadow-xl overflow-hidden rounded-lg">
                
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-fw bi bi-calculator-fill text-indigo-600 mr-2"></i>
                        {{ $pageTitle ?? 'Daftar Keputusan SPK' }}
                    </h2>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    
                    {{-- Tombol Tambah Keputusan Baru (DIPINDAHKAN KE KANAN) --}}
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('admin.spk.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Keputusan Baru
                        </a>
                    </div>
                    
                    {{-- Pesan Status (Success/Error) --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Table Container --}}
                    <div class="overflow-x-auto border border-gray-200 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Keputusan</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                                    <th scope="col" class="relative px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Aksi & Kelola Detail</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($keputusanList as $keputusan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $keputusan->id_keputusan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $keputusan->nama_keputusan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $keputusan->metode_yang_digunakan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $keputusan->status == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $keputusan->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $keputusan->tanggal_dibuat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        
                                        <div class="flex flex-wrap justify-center gap-1.5">
                                            
                                            {{-- 1. Tombol Kelola Detail (Masuk ke Kriteria/Alternatif) --}}
                                            <a href="{{ route('admin.spk.manage.kriteria', $keputusan->id_keputusan) }}" 
                                                class="px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                                Kelola Data
                                            </a>

                                            {{-- 2. Tombol Edit Keputusan --}}
                                            <a href="{{ route('admin.spk.edit', $keputusan->id_keputusan) }}" 
                                                class="px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 transition">
                                                Edit
                                            </a>

                                            {{-- 3. Tombol Delete Keputusan --}}
                                            <form action="{{ route('admin.spk.destroy', $keputusan->id_keputusan) }}" 
                                                  method="POST" 
                                                  class="inline" 
                                                  onsubmit="return confirm('APAKAH ANDA YAKIN? Menghapus keputusan ini akan menghapus SEMUA data terkait: Kriteria, Alternatif, dan Hasil!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 transition">
                                                    Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center bg-gray-50">
                                        Belum ada data Keputusan SPK yang dibuat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</div>


</div>

@endsection