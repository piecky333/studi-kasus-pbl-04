@extends('layouts.public')

@section('title', $divisi->nama_divisi . ' - HIMA-TI')

@section('content')
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <a href="{{ route('home') }}#divisi" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="relative h-64 sm:h-80 md:h-96 bg-gray-100">
                        @if($divisi->foto_divisi)
                            <img src="{{ asset('storage/' . $divisi->foto_divisi) }}" 
                                 alt="{{ $divisi->nama_divisi }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="bi bi-image text-6xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-8 sm:p-12">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">
                            {{ $divisi->nama_divisi }}
                        </h1>

                        <div class="prose prose-lg text-gray-600 max-w-none">
                            {!! $divisi->isi_divisi !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
