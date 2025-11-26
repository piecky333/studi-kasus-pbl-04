{{-- Halaman ini "memakai" layout publik yang sudah kita buat --}}
@extends('layouts.public')

@section('title', 'Beranda HIMA-TI Politala')


{{-- 1. HERO SLIDER --}}
@section('hero')
    <section id="hero-slider-container" class="relative w-full h-screen overflow-hidden bg-blue-900">
        <div id="slider-wrapper"
            class="absolute top-0 left-0 w-full h-full flex transition-transform duration-1000 ease-in-out">
            {{-- Slide items akan di-inject oleh JS --}}
        </div>

        <div id="hero-overlay" class="absolute inset-0 bg-black bg-opacity-70 z-10"></div>

        {{-- Konten Hero: Menggunakan 'js-animate' --}}
        <div id="hero-content" class="relative z-20 h-full flex items-center justify-center text-center text-white">
            {{-- 
                PERUBAHAN: 
                - Hapus 'wow fadeInDown', ganti 'js-animate'
                - Hapus 'data-wow-duration', ganti 'data-duration' (dalam milidetik)
            --}}
            <div class="container mx-auto px-4 js-animate" data-animation="fadeInDown" data-duration="1500">
                <h1 class="font-extrabold text-4xl sm:text-5xl lg:text-6xl mb-4 leading-tight shadow-text">
                    Himpunan Mahasiswa Teknologi Informasi
                </h1>
                {{-- 
                    PERUBAHAN: 
                    - Hapus 'wow fadeInUp', ganti 'js-animate'
                    - Hapus 'data-wow-delay', ganti 'data-delay' (dalam milidetik)
                --}}
                <p class="text-lg sm:text-xl text-gray-200 mb-8 max-w-2xl mx-auto js-animate" data-animation="fadeInUp" data-delay="500">
                    Wadah penggerak inovasi dan kolaborasi mahasiswa Politeknik Negeri Tanah Laut
                </p>
                <a href="#profil"
                    class="inline-block bg-white text-blue-700 font-semibold px-8 py-3 rounded-full shadow-xl hover:bg-gray-200 transition duration-300 transform hover:scale-105 js-animate" data-animation="fadeInUp"
                    data-delay="1000">
                    Kenali Kami Lebih Lanjut
                </a>
            </div>
        </div>

        {{-- Navigasi Slider --}}
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
                {{-- 
                    PERUBAHAN: 
                    - Hapus 'wow slideInLeft' dan 'data-wow-duration'
                    - Ganti 'js-animate' dan 'data-animation'
                --}}
                <div class="js-animate" data-animation="slideInLeft">
                    <img src="{{ asset('img/Logo hima.png') }}" alt="Profil HIMA"
                        class="w-full h-auto rounded-2xl object-contain max-h-96 transition duration-500">
                </div>
                {{-- 
                    PERUBAHAN: 
                    - Hapus 'wow slideInRight' dan 'data-wow-duration'
                    - Ganti 'js-animate' dan 'data-animation'
                --}}
                <div class="js-animate" data-animation="slideInRight">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-800 mb-4 border-b-4 border-blue-500 pb-2 inline-block">Tentang HIMA-TI</h2>
                    <p class="text-gray-700 mb-4 leading-relaxed">
                        Himpunan Mahasiswa Teknologi Informasi (HIMA-TI) merupakan organisasi kemahasiswaan yang berperan
                        sebagai <b>wadah aspirasi, pengembangan, dan kolaborasi</b> antar mahasiswa di bidang teknologi informasi.
                    </p>
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        Kami berkomitmen menciptakan lingkungan akademik yang aktif, inovatif, dan berdampak positif bagi
                        civitas akademika serta masyarakat luas melalui berbagai program kerja.
                    </p>
                    <a href="#divisi"
                        class="inline-block bg-blue-700 text-white font-semibold px-6 py-3 rounded-full shadow-lg hover:bg-blue-800 transition duration-300 transform hover:-translate-y-1">
                        Lihat Divisi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- DIVISI SECTION --}}
<section id="divisi" class="py-16 sm:py-20 bg-gray-50">
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-800 mb-4 js-animate" data-animation="fadeInDown">
            Divisi Unggulan HIMA-TI
        </h2>

        <p class="text-gray-600 mb-10 max-w-2xl mx-auto js-animate" data-animation="fadeInUp">
            Kami memiliki berbagai divisi yang berperan aktif dalam mendukung kegiatan organisasi dan pengembangan mahasiswa.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            @foreach ($divisi as $index => $div)
    <div class="bg-white rounded-xl shadow-xl hover:shadow-2xl transition 
                duration-500 text-center flex flex-col items-center p-6 
                js-animate group"
         data-animation="fadeInUp" data-delay="{{ ($index + 1) * 100 }}">

        <!-- Foto Divisi -->
        <div class="mb-4">
            <img src="{{ asset('storage/' . $div->foto_divisi) }}"
     alt="{{ $div->nama_divisi }}"
     class="w-28 h-28 object-cover group-hover:scale-110 group-hover:rotate-2 transition">

        </div>

        <!-- Nama Divisi -->
        <h3 class="font-bold text-xl text-gray-900 mb-2">
            {{ $div->nama_divisi }}
        </h3>

        <!-- Isi Deskripsi -->
        <p class="text-sm text-gray-500">
            {{ $div->isi_divisi }}
        </p>

    </div>
@endforeach


        </div>
    </div>
</section>


    {{-- BERITA SECTION (DINAMIS) --}}
    <section id="berita" class="py-16 sm:py-20 bg-white">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            {{-- PERUBAHAN: Hapus 'wow fadeInDown', ganti 'js-animate' --}}
            <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-800 mb-4 js-animate" data-animation="fadeInDown">Berita & Kegiatan Terbaru</h2>
            {{-- PERUBAHAN: Hapus 'wow fadeInUp', ganti 'js-animate' --}}
            <p class="text-gray-600 mb-10 max-w-2xl mx-auto js-animate" data-animation="fadeInUp">Ikuti berbagai aktivitas, seminar, dan event terbaru dari
                HIMA-TI.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($kegiatanTerbaru as $kegiatan)
                    {{-- Card wrapper: flex flex-col --}}
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col wow fadeInUp">

                        <a href="{{ route('berita.show', $kegiatan->id_berita) }}">
                            <img src="{{ asset('storage/'. $kegiatan->gambar_berita) }}" alt="{{ $kegiatan->judul_berita }}"
                                class="w-full h-48 object-cover">
                        </a>
                        <div class="p-6 text-left flex flex-col flex-grow">
                            <h3 class="font-bold text-lg text-gray-900 my-2 hover:text-blue-700 line-clamp-2 min-h-[3.5rem] transition duration-300">
                                <a href="{{ route('berita.show', $kegiatan->id_berita) }}" class="link-animasi">
                                    {{ Str::limit($kegiatan->judul_berita, 50) }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3 min-h-[3.75rem]">
                                {{ Str::limit(strip_tags($kegiatan->isi_berita), 100) }}
                            </p>
                            <div class="flex items-center text-xs text-gray-400 mt-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>{{ $kegiatan->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="md:col-span-3 text-gray-500 py-10">Belum ada berita kegiatan terbaru yang dapat ditampilkan saat ini.</p>
                @endforelse
            </div>
            {{-- PERUBAHAN: Ganti wow.js ke 'js-animate' --}}
            <a href="{{ route('berita.index') }}"
                class="mt-12 inline-block bg-blue-700 text-white font-semibold px-8 py-3 rounded-full hover:bg-blue-800 transition duration-300 transform hover:scale-105 js-animate" data-animation="fadeInUp" data-delay="500">
                Lihat Semua Berita
            </a>
        </div>
    </section>

    {{-- PRESTASI SECTION (DINAMIS) --}}
    <section id="prestasi" class="py-16 sm:py-20 bg-gray-50">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            {{-- PERUBAHAN: Hapus 'wow fadeInDown', ganti 'js-animate' --}}
            <h2 class="text-3xl sm:text-4xl font-extrabold text-blue-800 mb-4 js-animate" data-animation="fadeInDown">Prestasi Mahasiswa Terbaik</h2>
            {{-- PERUBAHAN: Hapus 'wow fadeInUp', ganti 'js-animate' --}}
            <p class="text-gray-600 mb-10 max-w-2xl mx-auto js-animate" data-animation="fadeInUp">Mahasiswa Teknologi Informasi yang berprestasi di tingkat
                nasional dan internasional.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($prestasiTerbaru as $prestasi)

                    {{-- 1. Card wrapper: Tambahkan 'flex flex-col' --}}
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col wow fadeInUp">
                        <a href="{{ route('prestasi.show', $prestasi->id_berita) }}">
                            <img src="{{ asset('storage/'. $prestasi->gambar_berita) }}" alt="{{ $prestasi->judul_berita }}"
                                class="w-full h-48 object-cover">
                        </a>
                        <div class="p-6 text-left flex flex-col flex-grow">
                            <h3 class="font-bold text-lg text-gray-900 my-2 hover:text-blue-700 line-clamp-2 min-h-[3.5rem] transition duration-300">
                                <a href="{{ route('prestasi.show', $prestasi->id_berita) }}" class="link-animasi">{{ Str::limit($prestasi->judul_berita, 50) }}</a>
                            </h3>
                            {{-- Deskripsi: Tambahkan 'min-h-[3.75rem]' untuk 3 baris --}}
                            {{-- <p class="text-gray-600 text-sm mb-4 line-clamp-3 min-h-[3.75rem]"> --}}
                                {{-- Sepertinya deskripsi prestasi terlewat di kodemu, saya biarkan --}}
                            {{-- </p> --}}
                            <div class="flex items-center text-xs text-gray-400 mt-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>{{ $prestasi->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="md:col-span-3 text-gray-500 py-10">Belum ada data prestasi terbaru yang dapat ditampilkan saat ini.</p>
                @endforelse
            </div>

            {{-- PERUBAHAN: Ganti wow.js ke 'js-animate' --}}
            <a href="{{ route('prestasi.index') }}"
                class="mt-12 inline-block bg-blue-700 text-white font-semibold px-8 py-3 rounded-full hover:bg-blue-800 transition duration-300 transform hover:scale-105 js-animate" data-animation="fadeInUp" data-delay="500">
                Lihat Semua Prestasi
            </a>
        </div>
    </section>
    
@endsection

{{-- 3. JAVASCRIPT & CSS KHUSUS HALAMAN INI --}}
@push('styles')
    {{-- 
        PERUBAHAN:
        Link Animate.css DIHAPUS, karena sudah tidak menggunakan WOW.js
    --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" /> --}}

    <style>
        /* Gaya Shadow untuk Teks Hero */
        .shadow-text {
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
        }

        /* Perbaikan CSS Slider (Tidak berubah) */
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
        background-attachment: fixed; 
    }

        #slider-wrapper { ... } /* (Tetap sama) */
        .slider-item { ... } /* (Tetap sama) */
        .slider-nav { ... } /* (Tetap sama) */
        .slider-nav:hover { ... } /* (Tetap sama) */

        /* == Animasi Underline (Tidak berubah) == */
        .link-animasi { ... } /* (Tetap sama) */
        .link-animasi::after { ... } /* (Tetap sama) */
        .link-animasi:hover::after { ... } /* (Tetap sama) */

        /* == Animasi Underline Navbar (Tidak berubah) == */
        .link-animasi-nav { ... } /* (Tetap sama) */
        .link-animasi-nav::after { ... } /* (Tetap sama) */
        .link-animasi-nav[aria-current="page"]::after { ... } /* (Tetap sama) */
        .link-animasi-nav:hover::after { ... } /* (Tetap sama) */
    </style>
@endpush

@push('scripts')
    {{-- CDN ANIME.JS (Sudah benar) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 2. Efek Paralaks Ringan pada Konten Hero (Tidak berubah)
            try {
                const heroContent = document.getElementById('hero-content');
                if (heroContent) {
                    window.addEventListener('scroll', function() {
                        if (window.pageYOffset < window.innerHeight) {
                            const scrollPosition = window.pageYOffset;
                            const translateY = scrollPosition * 0.3; 
                            heroContent.style.transform = `translate3d(0, ${translateY}px, 0)`; 
                        } else {
                            heroContent.style.transform = 'none';
                        }
                    });
                }
            } catch (e) {
                console.error('Error pada Efek Paralaks Hero:', e);
            }


            // 3. Logika Slider (Tidak berubah)
            try {
                const images = [
                    '{{ asset("img/gkt.jpg") }}',
                    '{{ asset("img/gti.jpg") }}'
                ];
                const sliderWrapper = document.getElementById('slider-wrapper');
                const prevBtn = document.getElementById('prev-btn');
                const nextBtn = document.getElementById('next-btn');

                if (sliderWrapper && prevBtn && nextBtn && images.length > 0) {
                    // ... (Logika slider Anda tetap sama) ...
                    let currentIndex = 0;
                    let intervalId;
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
                        intervalId = setInterval(nextImage, 5000);
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
            } catch (e) {
                console.error('Error pada Logika Slider:', e);
            }


            // 4. ========================================================
            //    == LOGIKA BARU: ANIME.JS DENGAN ANIMASI BERULANG ==
            //    ========================================================
            try {
                const animatedElements = document.querySelectorAll('.js-animate');

                if (animatedElements.length > 0) {
                    
                    const observerCallback = (entries, observer) => {
                        entries.forEach(entry => {
                            const el = entry.target;
                            const animationType = el.dataset.animation || 'fadeInUp';
                            const delay = parseInt(el.dataset.delay) || 0;
                            // Variabel 'duration' tidak lagi dipakai oleh spring, tapi 'distance' masih
                            const distance = parseInt(el.dataset.distance) || 50;

                            if (entry.isIntersecting) {
                                // --- ELEMEN MASUK LAYAR ---
                                
                                // ==================================================
                                // == PERUBAHAN DI SINI: Menggunakan Spring Physics ==
                                // ==================================================
                                let animationParams = {
                                    targets: el,
                                    opacity: [0, 1],
                                    delay: delay,

                                    // HAPUS 'duration' dan 'easing'
                                    // duration: duration,
                                    // easing: 'easeOutExpo',

                                    // GANTI DENGAN INI:
                                    type: 'spring',
                                    damping: 12,    // Kontrol 'ayunan'
                                    stiffness: 90,  // Kontrol 'kekuatan'
                                    mass: 1
                                };
                                // ==================================================
                                // == AKHIR PERUBAHAN ==
                                // ==================================================

                                // Tentukan animasi transform berdasarkan tipenya
                                switch (animationType) {
                                    case 'fadeInUp': animationParams.translateY = [distance, 0]; break;
                                    case 'fadeInDown': animationParams.translateY = [-distance, 0]; break;
                                    case 'slideInLeft': animationParams.translateX = [-distance, 0]; break;
                                    case 'slideInRight': animationParams.translateX = [distance, 0]; break;
                                    case 'zoomIn': animationParams.scale = [0.8, 1]; break;
                                    default: animationParams.translateY = [distance, 0];
                                }
                                
                                anime(animationParams);

                            } else {
                                // --- ELEMEN KELUAR LAYAR ---
                                // (Logika Reset ini tidak berubah, sudah benar)
                                el.style.opacity = 0;
                                switch (animationType) {
                                    case 'fadeInUp': el.style.transform = `translateY(${distance}px)`; break;
                                    case 'fadeInDown': el.style.transform = `translateY(${-distance}px)`; break;
                                    case 'slideInLeft': el.style.transform = `translateX(${-distance}px)`; break;
                                    case 'slideInRight': el.style.transform = `translateX(${distance}px)`; break;
                                    case 'zoomIn': el.style.transform = `scale(0.8)`; break;
                                    default: el.style.transform = `translateY(${distance}px)`;
                                }
                            }
                        });
                    };

                    // Buat observer
                    const observer = new IntersectionObserver(observerCallback, {
                        root: null,
                        threshold: 0.1
                    });

                    // Sembunyikan dan set posisi awal (Tidak berubah, sudah benar)
                    animatedElements.forEach(el => {
                        const animationType = el.dataset.animation || 'fadeInUp';
                        const distance = parseInt(el.dataset.distance) || 50;
                        
                        el.style.opacity = 0; 
                        
                        switch (animationType) {
                            case 'fadeInUp': el.style.transform = `translateY(${distance}px)`; break;
                            case 'fadeInDown': el.style.transform = `translateY(${-distance}px)`; break;
                            case 'slideInLeft': el.style.transform = `translateX(${-distance}px)`; break;
                            case 'slideInRight': el.style.transform = `translateX(${distance}px)`; break;
                            case 'zoomIn': el.style.transform = `scale(0.8)`; break;
                            default: el.style.transform = `translateY(${distance}px)`;
                        }
                        
                        observer.observe(el);
                    });
                }
            } catch (e) {
                console.error('Error pada Logika Animasi Anime.js:', e);
            }
            
        });
    </script>
@endpush