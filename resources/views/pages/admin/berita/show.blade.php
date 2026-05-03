@extends('layouts.admin')

@section('title', 'Detail Berita')

@section('content')
<div class="container-fluid px-4 mt-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h4 class="text-2xl font-bold text-gray-800">Detail Berita</h4>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap mengenai berita ini.</p>
        </div>
        <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($berita->gambar_berita)
                    <img src="{{ asset('storage/' . $berita->gambar_berita) }}" alt="{{ $berita->judul_berita }}" class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-image text-gray-300 text-6xl"></i>
                    </div>
                @endif
                
                <div class="p-8">
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 uppercase">
                            {{ $berita->kategori }}
                        </span>
                        <span class="text-gray-400 text-sm">•</span>
                        <span class="text-gray-500 text-sm">
                            <i class="far fa-calendar-alt mr-1"></i> {{ $berita->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">
                        {{ $berita->judul_berita }}
                    </h1>

                    <div class="prose prose-indigo max-w-none text-gray-700 leading-relaxed">
                        {!! $berita->isi_berita !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h5 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Status & Verifikasi</h5>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Saat Ini</label>
                        <div class="mt-1">
                            @if($berita->status == 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 inline-flex items-center">
                                    <i class="fas fa-clock mr-1.5"></i> Pending
                                </span>
                            @elseif($berita->status == 'verified')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 inline-flex items-center">
                                    <i class="fas fa-check-circle mr-1.5"></i> Terverifikasi
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 inline-flex items-center">
                                    <i class="fas fa-times-circle mr-1.5"></i> Ditolak
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Penulis</label>
                        <div class="mt-1 flex items-center">
                            <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-xs">
                                {{ strtoupper(substr($berita->user->nama ?? 'U', 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $berita->user->nama ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ $berita->user->role ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($berita->verifikator)
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Diverifikasi Oleh</label>
                        <div class="mt-1 text-sm font-medium text-green-700">
                            <i class="fas fa-user-check mr-1"></i> {{ $berita->verifikator->nama }}
                        </div>
                    </div>
                    @endif

                    @if($berita->penolak)
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Ditolak Oleh</label>
                        <div class="mt-1 text-sm font-medium text-red-700">
                            <i class="fas fa-user-times mr-1"></i> {{ $berita->penolak->nama }}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="mt-8 pt-6 border-t border-gray-100 space-y-3">
                    @if($berita->status == 'pending')
                    <form action="{{ route('admin.berita.verifikasi', $berita) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i> Verifikasi Berita
                        </button>
                    </form>
                    <form action="{{ route('admin.berita.tolak', $berita) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i> Tolak Berita
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.berita.edit', $berita) }}" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i> Edit Berita
                    </a>

                    <form action="{{ route('admin.berita.destroy', $berita) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-600 font-bold py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i> Hapus Berita
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
