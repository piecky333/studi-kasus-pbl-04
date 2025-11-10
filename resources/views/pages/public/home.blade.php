<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda HIMA-TI Politala</title>

    {{-- Bootstrap CSS & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Kumpulan Style CSS --}}
    <style>
        /* Style dari Layout Utama */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        nav.navbar {
            background-color: #004080;
        }
        .nav-item {
            margin-left: 15px
        }
        nav a.nav-link {
            color: hsl(0, 0%, 100%) !important;
        }
        nav a.nav-link:hover {
            color: #ffd700 !important;
        }
        footer {
            background-color: #002855;
            color: #ddd;
            padding: 30px 0;
        }
        footer a {
            color: #ffd700;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }

        /* Style Khusus untuk Hero Slider */
        #hero-slider-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            background-color: #002855; /* Warna cadangan */
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
            background-size: cover; /* Dikembalikan */
            background-position: center; /* Dikembalikan */
        }
        #hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3); /* Overlay sedikit lebih tipis */
            z-index: 1;
        }
        #hero-content {
            position: relative;
            z-index: 2;
            height: 100%;
        }
        .slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            background-color: rgba(255, 255, 255, 0.2);
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
        #prev-btn { left: 1.5rem; }
        #next-btn { right: 1.5rem; }
    </style>
</head>

<body>

    {{-- Navbar Anda akan tetap dimuat dari sini --}}
    @include ('partials.public.navbar')

    {{-- HERO SECTION --}}
    <section id="hero-slider-container">
        <div id="slider-wrapper"></div>
        <div id="hero-overlay"></div>
        <div id="hero-content" class="text-center text-white d-flex align-items-center justify-content-center">
            <div class="container">
                <h1 class="fw-bold display-4 mb-3">Himpunan Mahasiswa Teknologi Informasi</h1>
                <p class="lead mb-4">Wadah penggerak inovasi dan kolaborasi mahasiswa Politeknik Negeri Tanah Laut</p>
                <a href="#profil" class="btn btn-warning btn-lg rounded-pill px-4 py-2 fw-semibold">Kenali Kami</a>
            </div>
        </div>
        <button id="prev-btn" class="slider-nav"><i class="bi bi-chevron-left"></i></button>
        <button id="next-btn" class="slider-nav"><i class="bi bi-chevron-right"></i></button>
    </section>

    {{-- KONTEN HALAMAN LAINNYA --}}
    <main>
        {{-- PROFIL SECTION --}}
        <section id="profil" class="py-5 bg-light">
             <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <img src="{{ asset('img/Logo hima.png') }}" alt="Profil HIMA" class="img-fluid rounded-4">
                    </div>
                    <div class="col-lg-6">
                        <h2 class="fw-bold text-primary mb-3">Tentang HIMA-TI</h2>
                        <p class="text-secondary mb-3">
                            Himpunan Mahasiswa Teknologi Informasi (HIMA-TI) merupakan organisasi kemahasiswaan yang berperan sebagai wadah aspirasi, pengembangan, dan kolaborasi antar mahasiswa di bidang teknologi informasi.
                        </d>
                        <p class="text-secondary mb-4">
                            Kami berkomitmen menciptakan lingkungan akademik yang aktif, inovatif, dan berdampak positif bagi civitas akademika serta masyarakat luas.
                        </p>
                        <a href="#divisi" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">Lihat Divisi</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- DIVISI SECTION --}}
        <section id="divisi" class="py-5">
            <div class="container text-center">
                <h2 class="fw-bold text-primary mb-4">Divisi di HIMA-TI</h2>
                <p class="text-secondary mb-5">Kami memiliki berbagai divisi yang berperan aktif dalam mendukung kegiatan organisasi dan pengembangan mahasiswa.</p>
                <div class="row g-4">
                    {{-- Konten divisi Anda... --}}
                </div>
            </div>
        </section>
        
        {{-- BERITA SECTION --}}
        <section id="berita" class="py-5 bg-light">
            <div class="container text-center">
                <h2 class="fw-bold text-primary mb-4">Berita & Kegiatan Terbaru</h2>
                <p class="text-secondary mb-5">Ikuti berbagai aktivitas, seminar, dan event terbaru dari HIMA-TI.</p>
                <div class="row g-4">
                    {{-- Konten berita Anda... --}}
                </div>
            </div>
        </section>

        {{-- PRESTASI SECTION --}}
        <section id="prestasi" class="py-5">
            <div class="container text-center">
                <h2 class="fw-bold text-primary mb-4">Prestasi Mahasiswa</h2>
                <p class="text-secondary mb-5">Mahasiswa Teknologi Informasi yang berprestasi di tingkat nasional dan internasional.</p>
                <div class="row g-4">
                    {{-- Konten prestasi Anda... --}}
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer>
        <div class="container text-center">
            <p class="mb-2 fw-semibold">© {{ date('Y') }} Himpunan Mahasiswa Teknologi Informasi</p>
            <p>
                <a href="{{ url('/profil') }}">Tentang Kami</a> |
                <a href="{{ url('/kontak') }}">Kontak</a> |
                <a href="{{ url('/kebijakan') }}">Kebijakan Privasi</a>
            </p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JAVASCRIPT UNTUK SLIDER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // GANTI DAFTAR GAMBAR DI SINI
            const images = [
                '{{ asset("img/gkt.jpg") }}', // Ganti dengan path gambar Anda
                '{{ asset("img/gti.jpg") }}',
                '{{ asset("img/abc.jpg") }}'
            ];

            const sliderWrapper = document.getElementById('slider-wrapper');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            
            if (sliderWrapper && prevBtn && nextBtn) {
                let currentIndex = 0;
                let intervalId;

                function loadImages() {
                    sliderWrapper.innerHTML = '';
                    images.forEach(src => {
                        const slide = document.createElement('div');
                        slide.className = 'slider-item';
                        // Dikembalikan ke 'backgroundImage'
                        slide.style.backgroundImage = `url('${src}')`; 
                        sliderWrapper.appendChild(slide);
                    });
                }

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

                if (images.length > 0) {
                    loadImages(); // Panggil fungsi yang sudah diubah
                    showImage(currentIndex);
                    startAutoSlide();
                }
            } else {
                console.error("Elemen slider tidak ditemukan. Cek ID HTML Anda.");
            }
        });
    </script>
</body>
</html>

