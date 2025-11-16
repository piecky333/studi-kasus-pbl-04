{{-- Halaman ini "memakai" layout publik yang sudah kita buat --}}
@extends('layouts.public')

@section('title', 'Beranda HIMA-TI Politala')


{{-- 1. HERO SLIDER --}}
@section('hero')
    <section id="hero-slider-container" class="relative w-full h-screen overflow-hidden bg-blue-900">
        <div id="slider-wrapper"
            class="absolute top-0 left-0 w-full h-full flex transition-transform duration-1000 ease-in-out">
        </div>

        <div id="hero-overlay" class="absolute inset-0 bg-black bg-opacity-70 z-10"></div>

        <div id="hero-content" class="relative z-20 h-full flex items-center justify-center text-center text-white">
            <div class="container mx-auto px-4">
                <h1 class="font-extrabold text-4xl sm:text-5xl lg:text-6xl mb-4 leading-tight">
                    Himpunan Mahasiswa Teknologi Informasi
                </h1>
                <p class="text-lg sm:text-xl text-gray-200 mb-8 max-w-2xl mx-auto">
                    Wadah penggerak inovasi dan kolaborasi mahasiswa Politeknik Negeri Tanah Laut
                </p>
                <a href="#profil"
                    class="inline-block bg-blue-700 text-white font-semibold px-6 py-3 rounded-full shadow-md hover:bg-blue-800 transition duration-300">
                    Kenali Kami
                </a>
            </div>
        </div>

        <button id="prev-btn" class="slider-nav left-4 lg:left-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </button>
        <button id="next-btn" class="slider-nav right-4 lg:right-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </section>
@endsection

{{-- 2. KONTEN HALAMAN UTAMA --}}
@section('content')

    {{-- PROFIL SECTION --}}
    <section id="profil" class="py-16 sm:py-20 bg-white">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="wow slideInLeft">
                    <img src="{{ asset('img/Logo hima.png') }}" alt="Profil HIMA"
                        class="w-full h-auto rounded-2xl  object-contain max-h-96">
                </div>
                <div class="wow slideInRight">
                    <h2 class="text-3xl sm:text-4xl font-bold text-blue-800 mb-4">Tentang HIMA-TI</h2>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        Himpunan Mahasiswa Teknologi Informasi (HIMA-TI) merupakan organisasi kemahasiswaan yang berperan
                        sebagai wadah aspirasi, pengembangan, dan kolaborasi antar mahasiswa di bidang teknologi informasi.
                    </p>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Kami berkomitmen menciptakan lingkungan akademik yang aktif, inovatif, dan berdampak positif bagi
                        civitas akademika serta masyarakat luas.
                    </p>
                    <a href="#divisi"
                        class="inline-block bg-blue-700 text-white font-semibold px-6 py-3 rounded-full shadow-md hover:bg-blue-800 transition duration-300">
                        Lihat Divisi
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- DIVISI SECTION --}}
    <section id="divisi" class="py-16 sm:py-20 bg-gray-50">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-blue-800 mb-4">Divisi di HIMA-TI</h2>
            <p class="text-gray-600 mb-10 max-w-2xl mx-auto">Kami memiliki berbagai divisi yang berperan aktif dalam
                mendukung kegiatan organisasi dan pengembangan mahasiswa.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Divisi 1: Riset & Teknologi --}}
                <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center wow fadeInUp">
                    <img src="https://placehold.co/400x250/0059b3/FFFFFF?text=Riset+%26+Teknologi"
                        alt="Divisi Riset & Teknologi" class="w-full h-48 object-cover">

                    {{-- Konten Teks --}}
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">Riset & Teknologi</h3>
                    </div>
                </div>

                {{-- Divisi 2: Humas --}}
                <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center wow fadeInUp" data-wow-delay="0.1s">
                    <img src="https://placehold.co/400x250/0059b3/FFFFFF?text=Humas" alt="Divisi Humas"
                        class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">Humas</h3>
                    </div>
                </div>

                {{-- Divisi 3: Minat & Bakat --}}
                <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center wow fadeInUp" data-wow-delay="0.2s">
                    <img src="https://placehold.co/400x250/0059b3/FFFFFF?text=Minat+Bakat" alt="Divisi Minat & Bakat"
                        class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">Minat & Bakat</h3>
                    </div>
                </div>

                {{-- Divisi 4: Akademik --}}
                <div class="bg-white rounded-lg shadow-lg overflow-hidden text-center wow fadeInUp" data-wow-delay="0.3s">
                    <img src="https://placehold.co/400x250/0073e6/FFFFFF?text=Akademik" alt="Divisi Akademik"
                        class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">Akademik</h3>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- BERITA SECTION (DINAMIS) --}}
    <section id="berita" class="py-16 sm:py-20 bg-white">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-blue-800 mb-4">Berita & Kegiatan Terbaru</h2>
            <p class="text-gray-600 mb-10 max-w-2xl mx-auto">Ikuti berbagai aktivitas, seminar, dan event terbaru dari
                HIMA-TI.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($kegiatanTerbaru as $kegiatan)
                    {{-- Card wrapper: flex flex-col --}}
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col wow fadeInUp">

                        <a href="{{ route('berita.show', $kegiatan->id_berita) }}">
                            <img src="{{ asset('storage/' . $kegiatan->gambar_berita) }}" alt="{{ $kegiatan->judul_berita }}"
                                class="w-full h-48 object-cover">
                        </a>

                        {{-- Content wrapper: flex flex-col flex-grow --}}
                        <div class="p-6 text-left flex flex-col flex-grow">

                            <h3 class="font-bold text-lg text-gray-900 my-2 hover:text-blue-700 min-h-[3.5rem]">
                                <a href="{{ route('berita.show', $kegiatan->id_berita) }}">
                                    {{ Str::limit($kegiatan->judul_berita, 50) }}
                                </a>
                            </h3>

                            <p class="text-gray-600 text-sm mb-4 min-h-[3.75rem]">
                                {{ Str::limit(strip_tags($kegiatan->isi_berita), 100) }}
                            </p>

                            {{-- Wrapper tanggal: 'mt-auto' sudah benar untuk mendorong ke bawah --}}
                            <div class="flex items-center text-xs text-gray-400 mt-auto">
                                <span>{{ $kegiatan->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="md:col-span-3 text-gray-500">Belum ada berita kegiatan terbaru.</p>
                @endforelse
            </div>
            <a href="{{ route('berita.index') }}"
                class="mt-12 inline-block bg-blue-100 text-blue-700 font-semibold px-6 py-3 rounded-full hover:bg-blue-200 transition duration-300">
                Lihat Semua Berita
            </a>
        </div>
    </section>

    {{-- PRESTASI SECTION (DINAMIS) --}}
    <section id="prestasi" class="py-16 sm:py-20 bg-gray-50">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-blue-800 mb-4">Prestasi Mahasiswa</h2>
            <p class="text-gray-600 mb-10 max-w-2xl mx-auto">Mahasiswa Teknologi Informasi yang berprestasi di tingkat
                nasional dan internasional.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($prestasiTerbaru as $prestasi)

                    {{-- 1. Card wrapper: Tambahkan 'flex flex-col' --}}
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col wow fadeInUp">
                        <a href="{{ route('prestasi.show', $prestasi->id_berita) }}">
                            <img src="{{ asset('storage/' . $prestasi->gambar_berita) }}" alt="{{ $prestasi->judul_berita }}"
                                class="w-full h-48 object-cover">
                        </a>

                        {{-- 2. Content wrapper: Tambahkan 'flex flex-col flex-grow' --}}
                        <div class="p-6 text-left flex flex-col flex-grow">

                            {{-- 3. Judul: Tambahkan 'min-h-[3.5rem]' untuk 2 baris --}}
                            <h3 class="font-bold text-lg text-gray-900 my-2 hover:text-blue-700 min-h-[3.5rem]">
                                <a
                                    href="{{ route('prestasi.show', $prestasi->id_berita) }}">{{ Str::limit($prestasi->judul_berita, 50) }}</a>
                            </h3>

                            {{-- 4. Deskripsi: Tambahkan 'min-h-[3.75rem]' untuk 3 baris --}}
                            <p class="text-gray-600 text-sm mb-4 min-h-[3.75rem]">
                                {{ Str::limit(strip_tags($prestasi->isi_berita), 100) }}
                            </p>

                            {{-- 5. Tanggal: Bungkus dengan div, tambahkan 'mt-auto' dan ikon --}}
                            <div class="flex items-center text-xs text-gray-400 mt-auto">
                                <span>{{ $prestasi->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="md:col-span-3 text-gray-500">Belum ada prestasi terbaru.</p>
                @endforelse
            </div>

            <a href="{{ route('prestasi.index') }}"
                class="mt-12 inline-block bg-blue-100 text-blue-700 font-semibold px-6 py-3 rounded-full hover:bg-blue-200 transition duration-300">
                Lihat Semua Prestasi
            </a>
        </div>
    </section>
@endsection

{{-- 3. JAVASCRIPT & CSS KHUSUS HALAMAN INI --}}
@push('styles')
    {{-- CSS Khusus untuk Slider --}}
    <style>
        #hero-slider-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background-color: #002855;
        }

        #slider-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            transition: transform 1s ease-in-out;
        }

        .slider-item {
            width: 100%;
            height: 100%;
            flex-shrink: 0;
            background-size: cover;
            background-position: center;
        }

        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 30;
            background-color: rgba(171, 158, 158, 0.2);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            transition: background-color 0.3s;
        }

        .slider-nav:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }
    </style>
@endpush

@push('scripts')
    {{-- JAVASCRIPT UNTUK SLIDER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const images = [
                '{{ asset("img/gkt.jpg") }}',
                '{{ asset("img/gti.jpg") }}'
            ];

            const sliderWrapper = document.getElementById('slider-wrapper');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');

            if (sliderWrapper && prevBtn && nextBtn && images.length > 0) {
                let currentIndex = 0;
                let intervalId;

                // Load gambar ke slider
                images.forEach(src => {
                    const slide = document.createElement('div');
                    slide.className = 'slider-item';
                    slide.style.backgroundImage = `url('${src}')`;
                    sliderWrapper.appendChild(slide);
                });

                function showImage(index) {
                    const offset = -index * 100;
                    sliderWrapper.style.transform = `translateX(${offset}%)`;
                }

                function nextImage() {
                    currentIndex = (currentIndex + 1) % images.length;
                    showImage(currentIndex);
                }

                function prevImage() {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    showImage(currentIndex);
                }

                function startAutoSlide() {
                    stopAutoSlide();
                    intervalId = setInterval(nextImage, 5000); // Ganti slide setiap 5 detik
                }

                function stopAutoSlide() {
                    clearInterval(intervalId);
                }

                nextBtn.addEventListener('click', () => {
                    stopAutoSlide();
                    nextImage();
                    startAutoSlide();
                });

                prevBtn.addEventListener('click', () => {
                    stopAutoSlide();
                    prevImage();
                    startAutoSlide();
                });

                showImage(currentIndex);
                startAutoSlide();
            }
        });
    </script>


@endpush