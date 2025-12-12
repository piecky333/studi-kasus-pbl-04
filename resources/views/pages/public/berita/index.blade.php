{{-- Halaman ini "memakai" layout publik, sama seperti beranda --}}
@extends('layouts.public')

@section('title', 'Berita & Kegiatan - HIMA-TI')

@section('hero')
    <section id="berita-hero" 
        class="relative flex items-center justify-center text-center text-white h-[90vh] bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('img/gti.jpg') }}');">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black bg-opacity-70 z-10"></div>

        {{-- Konten Hero --}}
        <div id="berita-hero-content" 
            class="relative z-20 container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 wow fadeInDown"
            data-wow-duration="1.5s">

            <h1 class="font-bold text-4xl sm:text-5xl mb-3 shadow-text">Berita & Kegiatan HIMA-TI</h1>
            <p class="text-lg sm:text-xl text-gray-200 mb-4 max-w-3xl mx-auto">
                Update kegiatan, informasi, dan pengumuman terbaru dari Himpunan Mahasiswa Teknologi Informasi Politala.
            </p>
        </div>
    </section>
@endsection


@section('content')
    <section id="daftar-berita" class="py-16 sm:py-20 bg-gray-50">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="text-3xl sm:text-4xl font-bold text-blue-800 text-center mb-4 wow fadeInDown">
                Berita Terbaru
            </h2>
            <p class="text-gray-600 text-center mb-10 max-w-2xl mx-auto wow fadeInUp">
                Ikuti kegiatan terbaru kami untuk terus terhubung dengan berbagai program dan prestasi mahasiswa.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                @forelse($daftarKegiatan as $kegiatan)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col wow fadeInUp 
                                transition duration-300 hover:shadow-xl"
                        data-wow-delay="0.{{ $loop->index % 3 }}s">

                        {{-- Gambar --}}
                        <a href="{{ route('berita.show', $kegiatan->id_berita) }}" class="block overflow-hidden h-48">
                            <img src="{{ asset('storage/' . $kegiatan->gambar_berita) }}" 
                                 alt="{{ $kegiatan->judul_berita }}"
                                 class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                        </a>

                        <div class="p-6 flex flex-col flex-grow">

                            {{-- JUDUL --}}
                            <h3 class="font-bold text-lg text-gray-900 my-2 hover:text-blue-700 
                                       line-clamp-2 min-h-[3.5rem] transition duration-300">
                                <a href="{{ route('berita.show', $kegiatan->id_berita) }}" class="link-animasi">
                                    {{ Str::limit($kegiatan->judul_berita, 50) }}
                                </a>
                            </h3>

                            {{-- DESKRIPSI --}}
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3 min-h-[3.75rem] flex-grow">
                                {{ Str::limit(strip_tags($kegiatan->isi_berita), 100) }}
                            </p>

                            {{-- TANGGAL --}}
                            <div class="flex items-center text-xs text-gray-400 mt-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" 
                                     fill="currentColor" class="w-4 h-4 mr-1.5">
                                    <path fill-rule="evenodd" 
                                          d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5h10.5a.75.75 0 0 0 0-1.5H4.75a.75.75 0 0 0 0 1.5Z"
                                          clip-rule="evenodd" />
                                </svg>
                                <span>{{ $kegiatan->created_at->format('d M Y') }}</span>
                            </div>

                        </div>
                    </div>
                @empty
                    <p class="md:col-span-3 text-gray-500 text-center">Belum ada berita kegiatan.</p>
                @endforelse

            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $daftarKegiatan->links() }}
            </div>
        </div>
    </section>
@endsection


@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        .shadow-text {
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
        }

        .link-animasi {
            position: relative;
            display: inline-block;
            padding-bottom: 2px;
        }
        .link-animasi::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #1D4ED8;
            transition: width 0.3s ease-out;
        }
        .link-animasi:hover::after {
            width: 100%;
        }


    </style>
@endpush


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new WOW().init();

            const heroContent = document.getElementById('berita-hero-content');

            if (heroContent) {
                window.addEventListener('scroll', function() {
                    const scrollPosition = window.pageYOffset;
                    const heroHeight = document.getElementById('berita-hero').offsetHeight;

                    if (scrollPosition < heroHeight) {
                        heroContent.style.transform = `translate3d(0, ${scrollPosition * 0.3}px, 0)`;
                    } else {
                        heroContent.style.transform = 'none';
                    }
                });
            }
        });
    </script>
@endpush
