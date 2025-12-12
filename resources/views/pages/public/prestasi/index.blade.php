{{-- Halaman ini "memakai" layout publik (yang sudah Tailwind) --}}
@extends('layouts.public')

{{-- Judul halaman diubah menjadi Prestasi --}}
@section('title', 'Prestasi Mahasiswa - HIMA-TI Politala')

{{-- HERO SECTION BARU (Copywriting berfokus pada Prestasi) --}}
@section('hero')
    {{-- MODIFIKASI: Menggunakan 'bg-fixed' untuk paralaks dan gambar latar --}}
    <section id="prestasi-hero" class="relative flex items-center justify-center text-center text-white h-[90vh] bg-cover bg-center bg-fixed"
             style="background-image: url('{{ asset('img/gkt.jpg') }}');"> {{-- Menggunakan salah satu gambar slider Anda --}}
        
        {{-- Overlay gelap (sama seperti beranda) --}}
        <div class="absolute inset-0 bg-black bg-opacity-70 z-10"></div>
        
        {{-- Konten Hero (diberi ID dan z-20 untuk script paralaks) --}}
        <div id="prestasi-hero-content" class="relative z-20 container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 wow fadeInDown" data-wow-duration="1.5s">
            <h1 class="font-bold text-4xl sm:text-5xl mb-3 shadow-text">Capaian & Prestasi Mahasiswa</h1>
            <p class="text-lg sm:text-xl text-gray-200 mb-4 max-w-3xl mx-auto">
                Kami bangga membagikan pencapaian luar biasa dari mahasiswa Teknologi Informasi Politala. Temukan inspirasi dari dedikasi dan kerja keras mereka.
            </p>
        </div>
    </section>
@endsection


{{-- Konten utama halaman daftar prestasi (Menggunakan Tailwind) --}}
@section('content')
    <section id="daftar-prestasi" class="py-16 sm:py-20 bg-gray-50">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- MODIFIKASI: Menambahkan animasi WOW.js --}}
            <h2 class="text-3xl sm:text-4xl font-bold text-blue-800 text-center mb-4 wow fadeInDown">Galeri Prestasi</h2>
            <p class="text-gray-600 text-center mb-10 max-w-2xl mx-auto wow fadeInUp">
                Menampilkan karya, penghargaan, dan pencapaian mahasiswa kami di berbagai bidang, baik akademik maupun non-akademik.
            </p>

            {{-- Menggunakan struktur Grid dan Card Tailwind --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- looping setiap berita jenis prestasi --}}
                @forelse($semuaPrestasi as $item)
                    {{-- MODIFIKASI: Menambahkan 'rounded-xl', 'wow fadeInUp', dan 'hover:shadow-xl' --}}
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col wow fadeInUp transition duration-300 hover:shadow-xl" data-wow-delay="0.{{ $loop->index % 3 }}s">
                        
                        {{-- Link dan Gambar --}}
                        {{-- MODIFIKASI: Menambahkan 'overflow-hidden' dan 'hover:scale-105' pada gambar --}}
                        
                        {{-- ========================================================== --}}
                        {{-- == PERBAIKAN: Mengganti route('berita.show') menjadi 'prestasi.show' == --}}
                        {{-- ========================================================== --}}
                        <a href="{{ route('prestasi.show', $item->id_berita) }}" class="block overflow-hidden h-48">
                            @if ($item->gambar_berita)
                                <img src="{{ asset('storage/' . $item->gambar_berita) }}" alt="{{ $item->judul_berita }}"
                                     class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                            @else
                                {{-- Fallback jika tidak ada gambar --}}
                                <img src="https://placehold.co/400x220/002855/FFFFFF?text=Prestasi" alt="Placeholder Prestasi"
                                     class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                            @endif
                        </a>
                        
                        {{-- Card Body (flex-grow membuat card sama tinggi) --}}
                        <div class="p-6 flex flex-col flex-grow">
                            
                            {{-- Judul Berita --}}
                            {{-- MODIFIKASI: Menambahkan 'line-clamp-2' dan 'min-h' untuk konsistensi --}}
                            <h3 class="font-bold text-lg text-gray-900 my-2 hover:text-blue-700 line-clamp-2 min-h-[3.5rem] transition duration-300">
                                
                                {{-- ========================================================== --}}
                                {{-- == PERBAIKAN: Mengganti route('berita.show') menjadi 'prestasi.show' == --}}
                                {{-- ========================================================== --}}
                                {{-- MODIFIKASI: Menambahkan kelas 'link-animasi' --}}
                                <a href="{{ route('prestasi.show', $item->id_berita) }}" class="link-animasi">
                                    {{ Str::limit($item->judul_berita, 50) }}
                                </a>
                            </h3>
                            
                            {{-- Deskripsi/Isi (flex-grow mengisi ruang) --}}
                             {{-- MODIFIKASI: Menambahkan 'line-clamp-3' dan 'min-h' untuk konsistensi --}}
                            <p class="text-gray-600 text-sm mb-4 flex-grow line-clamp-3 min-h-[3.75rem]">
                                {{ Str::limit(strip_tags($item->isi_berita), 100) }}
                            </p>
                            
                            {{-- Footer Card (mt-auto mendorong ke bawah) --}}
                            <div class="flex items-center text-xs text-gray-400 mt-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5">
                                  <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5h10.5a.75.75 0 0 0 0-1.5H4.75a.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $item->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika tidak ada data prestasi --}}
                    <p class="md:col-span-3 text-gray-500 text-center">Belum ada prestasi yang dipublikasikan.</p>
                @endforelse

            </div>

            {{-- Link Paginasi (dari controller) --}}
            <div class="mt-12">
                {{ $semuaPrestasi->links() }}
            </div>
        </div>
    </section>
@endsection

{{-- TAMBAHAN: Styles dan Scripts untuk Animasi --}}
@push('styles')
    {{-- Memuat Animate.css untuk animasi WOW.js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* Gaya Shadow untuk Teks Hero (dari beranda) */
        .shadow-text {
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
        }

        /* == TAMBAHAN UNTUK ANIMASI UNDERLINE PADA JUDUL KARTU == */
        .link-animasi {
            position: relative;
            display: inline-block;
            text-decoration: none;
            padding-bottom: 2px;
        }

        .link-animasi::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #1D4ED8; /* blue-700 */
            transition: width 0.3s ease-out;
        }

        .link-animasi:hover::after {
            width: 100%;
        }


    </style>
@endpush

@push('scripts')
    {{-- Memuat WOW.js untuk animasi scroll --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inisialisasi WOW.js (Sangat Penting)
            new WOW().init();
            
            // 2. Skrip Paralaks untuk Hero Prestasi
            const heroContent = document.getElementById('prestasi-hero-content');
            const heroSection = document.getElementById('prestasi-hero');
            
            if (heroContent && heroSection) {
                window.addEventListener('scroll', function() {
                    const scrollPosition = window.pageYOffset;
                    const heroHeight = heroSection.offsetHeight;

                    // Hanya terapkan efek jika scroll berada di area hero
                    if (scrollPosition < heroHeight) {
                        // Gerakkan konten Hero ke atas 0.3x kecepatan scroll
                        const translateY = scrollPosition * 0.3; 
                        heroContent.style.transform = `translate3d(0, ${translateY}px, 0)`; 
                    } else {
                        heroContent.style.transform = 'none';
                    }
                });
            }
        });
    </script>
@endpush