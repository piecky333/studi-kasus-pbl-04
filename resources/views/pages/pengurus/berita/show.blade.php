@extends('layouts.pengurus')

@section('title', 'Detail Berita')

@section('content')
<div class="container-fluid px-4 mt-6 pb-10">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h4 class="text-2xl font-bold text-gray-800">Detail Berita</h4>
            <p class="text-sm text-gray-500 mt-1">Status dan konten berita yang Anda kirimkan.</p>
        </div>
        <a href="{{ route('pengurus.berita.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
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
                <h5 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Status Publikasi</h5>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Saat Ini</label>
                        <div class="mt-1">
                            @if($berita->status == 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 inline-flex items-center">
                                    <i class="fas fa-clock mr-1.5"></i> Menunggu Verifikasi
                                </span>
                            @elseif($berita->status == 'verified')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 inline-flex items-center">
                                    <i class="fas fa-check-circle mr-1.5"></i> Terbit (Verified)
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 inline-flex items-center">
                                    <i class="fas fa-times-circle mr-1.5"></i> Ditolak Admin
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($berita->status == 'pending')
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <p class="text-xs text-blue-700">
                            Berita Anda sedang dalam antrean verifikasi oleh Administrator. Anda akan menerima notifikasi setelah status berubah.
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="mt-8 pt-6 border-t border-gray-100 space-y-3">
                    <a href="{{ route('pengurus.berita.edit', $berita) }}" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center justify-center shadow-md shadow-amber-100">
                        <i class="fas fa-edit mr-2"></i> Edit Konten
                    </a>

                    <form action="{{ route('pengurus.berita.destroy', $berita) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i> Tarik / Hapus Berita
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
