@extends('layouts.public')

@section('title', 'Divisi HIMA-TI')

@section('content')
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-blue-900 mb-4">Divisi HIMA-TI</h1>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Mengenal lebih dekat divisi-divisi yang menjadi pilar pergerakan Himpunan Mahasiswa Teknologi Informasi.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($semuaDivisi as $div)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden group">
                        <div class="relative h-48 overflow-hidden bg-gray-100">
                            @if($div->foto_divisi)
                                <img src="{{ asset('storage/' . $div->foto_divisi) }}" 
                                     alt="{{ $div->nama_divisi }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="bi bi-image text-4xl"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                                <span class="text-white font-semibold">Lihat Detail</span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                                {{ $div->nama_divisi }}
                            </h3>
                            <p class="text-gray-600 line-clamp-3 mb-4">
                                {{ $div->isi_divisi }}
                            </p>
                            <a href="{{ route('divisi.show', $div->id_divisi) }}" class="inline-flex items-center text-blue-600 font-medium hover:text-blue-800">
                                Selengkapnya
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="inline-block p-4 rounded-full bg-blue-50 text-blue-500 mb-4">
                            <i class="bi bi-info-circle text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada data divisi</h3>
                        <p class="text-gray-500 mt-2">Data divisi akan segera ditambahkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
