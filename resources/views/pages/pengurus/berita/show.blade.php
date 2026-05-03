@extends('layouts.pengurus')

@section('title', 'Detail Berita')

@section('content')
@php
    $statusLabel = match($berita->status) {
        'verified' => 'Terverifikasi',
        'rejected' => 'Ditolak',
        default    => 'Menunggu Verifikasi',
    };
    $statusClass = match($berita->status) {
        'verified' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800',
        default    => 'bg-yellow-100 text-yellow-800',
    };
    $statusIcon = match($berita->status) {
        'verified' => 'fa-check-circle',
        'rejected' => 'fa-times-circle',
        default    => 'fa-clock',
    };
@endphp
<div class="container-fluid px-4 mt-6 pb-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('pengurus.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a></li>
                    <li><i class="fas fa-chevron-right text-gray-300 text-xs"></i></li>
                    <li><a href="{{ route('pengurus.berita.index') }}" class="hover:text-indigo-600 transition-colors">Berita</a></li>
                    <li><i class="fas fa-chevron-right text-gray-300 text-xs"></i></li>
                    <li class="text-gray-900 font-medium truncate max-w-xs">{{ Str::limit($berita->judul_berita, 30) }}</li>
                </ol>
            </nav>
            <h2 class="text-2xl font-bold text-gray-900">Detail Berita</h2>
            <p class="text-sm text-gray-500 mt-1">Lihat konten dan status publikasi berita.</p>
        </div>
        <a href="{{ route('pengurus.berita.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2 text-gray-400"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== KONTEN UTAMA ===== --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Gambar --}}
                @if($berita->gambar_berita)
                    <img src="{{ asset('storage/' . $berita->gambar_berita) }}"
                         alt="{{ $berita->judul_berita }}"
                         class="w-full h-72 sm:h-96 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center gap-2">
                        <i class="fas fa-newspaper text-gray-300 text-5xl"></i>
                        <span class="text-xs text-gray-400 font-medium">Tidak ada gambar</span>
                    </div>
                @endif

                {{-- Isi Berita --}}
                <div class="p-6 sm:p-8">
                    {{-- Meta --}}
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 uppercase tracking-wide">
                            {{ $berita->kategori }}
                        </span>

                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }} inline-flex items-center gap-1">
                            <i class="fas {{ $statusIcon }}"></i> {{ $statusLabel }}
                        </span>

                        <span class="text-gray-400 text-xs ml-auto">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $berita->created_at?->format('d M Y, H:i') ?? '-' }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 leading-tight">
                        {{ $berita->judul_berita }}
                    </h1>

                    <div class="prose prose-indigo max-w-none text-gray-700 leading-relaxed text-sm sm:text-base">
                        {!! $berita->isi_berita !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== SIDEBAR ===== --}}
        <div class="space-y-5">

            {{-- Status Publikasi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h5 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Status Publikasi</h5>

                <div class="flex flex-col items-center justify-center py-5 rounded-xl border
                    {{ $berita->status === 'verified' ? 'bg-green-50 border-green-200' :
                       ($berita->status === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200') }}">
                    <i class="fas {{ $statusIcon }} text-3xl mb-2
                        {{ $berita->status === 'verified' ? 'text-green-500' :
                           ($berita->status === 'rejected' ? 'text-red-500' : 'text-yellow-500') }}"></i>
                    <span class="text-base font-bold
                        {{ $berita->status === 'verified' ? 'text-green-700' :
                           ($berita->status === 'rejected' ? 'text-red-700' : 'text-yellow-700') }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                @if($berita->status === 'pending')
                    <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-lg">
                        <p class="text-xs text-blue-700 leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i>
                            Berita sedang dalam antrean verifikasi oleh Administrator.
                        </p>
                    </div>
                @elseif($berita->status === 'rejected')
                    <div class="mt-4 bg-red-50 border-l-4 border-red-400 p-3 rounded-r-lg">
                        <p class="text-xs text-red-700 leading-relaxed">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Berita ini ditolak oleh Administrator. Silakan edit dan kirim ulang.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Info Penulis --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h5 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Penulis</h5>
                <div class="flex items-center gap-3">
                    <img src="{{ $berita->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($berita->user->nama ?? 'P') . '&color=7F9CF5&background=EBF4FF' }}"
                         class="h-10 w-10 rounded-full object-cover border-2 border-gray-100" alt="">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $berita->user->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $berita->user->email ?? '-' }}</p>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500 space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-gray-300 w-3"></i>
                        <span>Dibuat: {{ $berita->created_at?->format('d M Y, H:i') ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-history text-gray-300 w-3"></i>
                        <span>Diperbarui: {{ $berita->updated_at?->format('d M Y, H:i') ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Aksi — hanya tampil untuk pemilik berita --}}
            @if($berita->id_user === auth()->user()->id_user)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h5 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Aksi</h5>
                <div class="space-y-3">
                    <a href="{{ route('pengurus.berita.edit', $berita) }}"
                       class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2.5 px-4 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-edit"></i> Edit Berita
                    </a>
                    <form action="{{ route('pengurus.berita.destroy', $berita) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus berita ini? Tindakan tidak dapat dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 px-4 rounded-xl transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i> Hapus Berita
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-5 text-center">
                <i class="fas fa-eye text-gray-300 text-2xl mb-2"></i>
                <p class="text-xs text-gray-400 font-medium">Anda melihat berita milik pengurus lain.<br>Edit & hapus hanya tersedia untuk penulis.</p>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
