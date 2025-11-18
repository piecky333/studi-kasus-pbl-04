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
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            {{-- Emojis opsional untuk tampilan modern/pro --}}
                            <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $pageTitle ?? 'Daftar Keputusan SPK' }}
                        </h2>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6">

                        {{-- Tombol Tambah Keputusan Baru (Modern Button) --}}
                        {{-- Ganti '#' dengan route yang benar, misalnya route('admin.spk.create') --}}
                        <a href="{{ route('admin.spk.create') ?? '#' }}" class="inline-flex items-center px-4 py-2 mb-6 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Keputusan Baru
                        </a>

                        {{-- Table Container --}}
                        <div class="overflow-hidden border border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Keputusan
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Metode
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Dibuat
                                        </th>
                                        <th scope="col" class="relative px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi & Kelola Data
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($keputusanList as $keputusan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $keputusan->id_keputusan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $keputusan->nama_keputusan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $keputusan->metode_yang_digunakan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- Penyesuaian Badge/Pill Tailwind --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $keputusan->status == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $keputusan->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $keputusan->tanggal_dibuat }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            
                                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                                <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="dropdownMenuButton_{{ $keputusan->id_keputusan }}" aria-expanded="true" aria-haspopup="true">
                                                    Kelola Data
                                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            
                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition:enter="transition ease-out duration-100" 
                                                     x-transition:enter-start="transform opacity-0 scale-95" 
                                                     x-transition:enter-end="transform opacity-100 scale-100" 
                                                     x-transition:leave="transition ease-in duration-75" 
                                                     x-transition:leave-start="transform opacity-100 scale-100" 
                                                     x-transition:leave-end="transform opacity-0 scale-95" 
                                                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10" 
                                                     role="menu" aria-orientation="vertical" aria-labelledby="dropdownMenuButton_{{ $keputusan->id_keputusan }}" tabindex="-1">
                                                    
                                                    <div class="py-1">
                                                        <span class="block px-4 py-2 text-xs text-gray-500">Manajemen Data Input:</span>
                                                        <a href="{{ route('admin.spk.manage.kriteria', $keputusan->id_keputusan) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                            Data Kriteria
                                                        </a>
                                                        <a href="{{ route('admin.spk.manage.alternatif', $keputusan->id_keputusan) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                            Data Alternatif
                                                        </a>
                                                        <a href="{{ route('admin.spk.manage.penilaian', $keputusan->id_keputusan) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                            Data Penilaian (Input)
                                                        </a>
                                                    </div>

                                                    <div class="py-1">
                                                        <span class="block px-4 py-2 text-xs text-gray-500">Perhitungan & Hasil:</span>
                                                        <a href="{{ route('admin.spk.calculate.proses', $keputusan->id_keputusan) }}" class="block px-4 py-2 text-sm text-indigo-600 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                            Proses Perhitungan SAW
                                                        </a>
                                                        <a href="{{ route('admin.spk.manage.hasil', $keputusan->id_keputusan) }}" class="block px-4 py-2 text-sm text-green-600 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                                            Hasil Akhir & Ranking
                                                        </a>
                                                    </div>
                                                    
                                                    @if($keputusan->status != 'Selesai')
                                                    <div class="py-1">
                                                        <a href="{{ route('admin.spk.calculate.run', $keputusan->id_keputusan) }}" 
                                                           class="block px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50" 
                                                           role="menuitem" tabindex="-1" 
                                                           onclick="return confirm('Yakin ingin MENJALANKAN PERHITUNGAN untuk keputusan ini? Proses akan diperbarui.')">
                                                            RUN CALCULATION!
                                                        </a>
                                                    </div>
                                                    @endif

                                                </div>
                                            </div>
                                            {{-- Catatan: Dropdown ini mengandalkan Alpine.js (x-data, x-show, @click.away, dll.) untuk fungsionalitas yang mudah diimplementasikan dengan Tailwind. --}}

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
