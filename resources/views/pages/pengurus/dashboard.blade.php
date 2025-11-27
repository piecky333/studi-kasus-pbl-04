@extends('layouts.pengurus')

@section('title', 'Dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50/50 pt-8">
        {{-- Welcome Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0d2149] to-[#1a3a75] p-8 shadow-xl mb-8">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 py-3">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang, Pengurus! 👋</h1>
                    <p class="text-blue-100 text-lg opacity-90">Kelola data organisasi dan pantau perkembangan dengan mudah.</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/20 shadow-inner">
                        <div class="flex items-center gap-3 text-white">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <i class="bi bi-calendar-check text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-blue-200">Hari ini</p>
                                <p class="font-semibold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Decorative Elements --}}
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-2xl"></div>
        </div>

        {{-- Stats Grid (3 Columns) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total Divisi --}}
            <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                            <i class="bi bi-diagram-3 text-2xl"></i>
                        </div>
                        <span class="flex items-center text-xs font-medium text-green-600 bg-green-50 px-2.5 py-1 rounded-full border border-green-100">
                            <i class="bi bi-check-circle text-sm mr-1"></i> Aktif
                        </span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalDivisi }}</h3>
                    <p class="text-gray-500 text-sm font-medium">Total Divisi</p>
                </div>
            </div>

            {{-- Total Jabatan --}}
            <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-cyan-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-cyan-50 text-cyan-600 rounded-xl group-hover:bg-cyan-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                            <i class="bi bi-person-badge text-2xl"></i>
                        </div>
                        <span class="flex items-center text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full border border-gray-200">
                            Terstruktur
                        </span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalJabatan }}</h3>
                    <p class="text-gray-500 text-sm font-medium">Total Jabatan</p>
                </div>
            </div>

            {{-- Total Pengurus --}}
            <div class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                            <i class="bi bi-people-fill text-2xl"></i>
                        </div>
                        <div class="flex -space-x-2">
                            @foreach(range(1,3) as $i)
                                <div class="w-7 h-7 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-[10px] text-gray-400">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalPengurus }}</h3>
                    <p class="text-gray-500 text-sm font-medium">Total Pengurus</p>
                </div>
            </div>
        </div>

        {{-- Quick Actions & Info Section --}}
        {{-- Quick Actions & Recent News Section --}}
        <div class="space-y-8">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h5 class="font-bold text-gray-800 flex items-center text-lg">
                        Aksi Cepat
                    </h5>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('pengurus.berita.create') }}" class="flex items-center p-4 rounded-xl bg-gray-50 hover:bg-blue-50 hover:text-blue-600 transition-all duration-300 border border-transparent hover:border-blue-100 group cursor-pointer hover:[text-decoration:none!important]">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-sm">
                            <i class="bi bi-newspaper text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-gray-700 group-hover:text-blue-700">Buat Berita</span>
                            <span class="text-xs text-gray-400">Publikasi informasi baru</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('pengurus.pengurus.create') }}" class="flex items-center p-4 rounded-xl bg-gray-50 hover:bg-emerald-50 hover:text-emerald-600 transition-all duration-300 border border-transparent hover:border-emerald-100 group cursor-pointer hover:[text-decoration:none!important]">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-sm">
                            <i class="bi bi-person-plus-fill text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-gray-700 group-hover:text-emerald-700">Tambah Pengurus</span>
                            <span class="text-xs text-gray-400">Manajemen anggota tim</span>
                        </div>
                    </a>

                    <a href="{{ route('pengurus.divisi.index') }}" class="flex items-center p-4 rounded-xl bg-gray-50 hover:bg-purple-50 hover:text-purple-600 transition-all duration-300 border border-transparent hover:border-purple-100 group cursor-pointer hover:[text-decoration:none!important]">
                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform shadow-sm">
                            <i class="bi bi-diagram-2-fill text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-gray-700 group-hover:text-purple-700">Kelola Divisi</span>
                            <span class="text-xs text-gray-400">Struktur organisasi</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Recent News --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h5 class="font-bold text-gray-800 flex items-center text-lg">
                        Berita Terbaru Anda
                    </h5>
                    <a href="{{ route('pengurus.berita.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua <i class="bi bi-arrow-right ml-1"></i></a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-l-lg">Judul Berita</th>
                                <th scope="col" class="px-6 py-3">Kategori</th>
                                <th scope="col" class="px-6 py-3">Tanggal Upload</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3 rounded-r-lg text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBerita as $berita)
                                <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ Str::limit($berita->judul_berita, 40) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">
                                            {{ $berita->kategori }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y, H:i') }} WITA
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($berita->status == 'aktif')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Aktif</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('pengurus.berita.edit', $berita->id_berita) }}" class="text-yellow-500 hover:text-yellow-600 font-medium text-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-journal-x text-3xl text-gray-300 mb-2"></i>
                                            <p>Belum ada berita yang Anda upload.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
