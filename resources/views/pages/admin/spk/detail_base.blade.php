@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    
    @php
        $keputusanId = $idKeputusan ?? null;
    @endphp

    <div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
        
        {{-- Navigasi Breadcrumb untuk menunjukkan lokasi halaman saat ini --}}
        <nav class="text-sm font-medium text-gray-500 mb-4" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('admin.spk.index') }}" class="text-gray-500 hover:text-gray-700">Manajemen SPK</a>
                    <i class="fas fa-chevron-right mx-2 text-gray-400 text-xs"></i>
                </li>
                <li class="flex items-center">
                    <span class="text-indigo-600 font-semibold">{{ $keputusan->nama_keputusan ?? 'Detail Keputusan'  }}</span>
                    
                    @php
                        $statusColor = 'bg-gray-100 text-gray-800';
                        if(($keputusan->status ?? '') == 'Selesai') $statusColor = 'bg-green-100 text-green-800';
                        if(($keputusan->status ?? '') == 'Aktif') $statusColor = 'bg-blue-100 text-blue-800';
                    @endphp
                    <span class="ml-3 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                        {{ $keputusan->status ?? 'Draft' }}
                    </span>
                </li>
            </ol>
        </nav>
        
        {{-- ========================================================== --}}
        {{-- Navigasi Tab Utama untuk berpindah antar langkah SPK (Kriteria, Alternatif, Hasil) --}}
        {{-- ========================================================== --}}
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                
                {{-- Tab 1: Menampilkan halaman pengelolaan Kriteria dan Perhitungan Bobot AHP --}}
                <a href="{{ route('admin.spk.kriteria.index', $keputusanId) }}" 
                    class="{{ ($currentTab ?? 'kriteria') == 'kriteria' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition duration-150">
                    1. Kriteria & Bobot (AHP)
                </a>

                {{-- Tab 2: Menampilkan halaman pengelolaan Alternatif dan Matriks Penilaian --}}
                <a href="{{ route('admin.spk.alternatif.index', $keputusanId) }}" 
                    class="{{ ($currentTab ?? '') == 'alternatif' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition duration-150">
                    2. Alternatif & Penilaian
                </a>
                
                {{-- Tab 3: Menampilkan halaman Hasil Akhir Perankingan menggunakan metode SAW --}}
                <a href="{{ route('admin.spk.hasil.index', $keputusanId) }}" 
                    class="{{ ($currentTab ?? '') == 'hasil' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition duration-150">
                    3. Hasil Akhir (SAW)
                </a>
            </nav>
        </div>
        
        {{-- ========================================================== --}}
        {{-- Area Konten Dinamis: Menampilkan isi dari masing-masing tab (Kriteria, Alternatif, atau Hasil) --}}
        {{-- ========================================================== --}}
        @yield('detail_content')

    </div>
</div>
@endsection