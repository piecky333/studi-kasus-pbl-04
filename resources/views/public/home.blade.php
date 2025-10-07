@extends('layouts.public')

@section('title', 'Beranda HIMA-TI Politala')

@section('content')

{{-- ================= HERO SECTION ================= --}}
<section id="hero" class="text-center text-white d-flex align-items-center justify-content-center" 
         style="height: 90vh; background: linear-gradient(135deg, #002855 60%, #004080);">
    <div class="container">
        <h1 class="fw-bold display-4 mb-3">Himpunan Mahasiswa Teknologi Informasi</h1>
        <p class="lead mb-4">Wadah penggerak inovasi dan kolaborasi mahasiswa Politeknik Negeri Tanah Laut</p>
        <a href="#profil" class="btn btn-warning btn-lg rounded-pill px-4 py-2 fw-semibold">Kenali Kami</a>
    </div>
</section>

{{-- ================= PROFIL SECTION ================= --}}
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
                </p>
                <p class="text-secondary mb-4">
                    Kami berkomitmen menciptakan lingkungan akademik yang aktif, inovatif, dan berdampak positif bagi civitas akademika serta masyarakat luas.
                </p>
                <a href="#divisi" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">Lihat Divisi</a>
            </div>
        </div>
    </div>
</section>

{{-- ================= DIVISI SECTION ================= --}}
<section id="divisi" class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold text-primary mb-4">Divisi di HIMA-TI</h2>
        <p class="text-secondary mb-5">Kami memiliki berbagai divisi yang berperan aktif dalam mendukung kegiatan organisasi dan pengembangan mahasiswa.</p>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="mb-3"><i class="bi bi-gear-fill fs-1 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">Divisi Riset & Teknologi</h5>
                    <p class="text-secondary small">Fokus pada pengembangan teknologi, inovasi digital, dan pelatihan mahasiswa di bidang IT.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="mb-3"><i class="bi bi-people-fill fs-1 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">Divisi Kaderisasi</h5>
                    <p class="text-secondary small">Membangun kepemimpinan, karakter, dan loyalitas anggota melalui kegiatan internal.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="mb-3"><i class="bi bi-broadcast fs-1 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">Divisi Humas & Media</h5>
                    <p class="text-secondary small">Mengelola publikasi, branding, dan komunikasi eksternal HIMA-TI melalui media digital.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= BERITA SECTION ================= --}}
<section id="berita" class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold text-primary mb-4">Berita & Kegiatan Terbaru</h2>
        <p class="text-secondary mb-5">Ikuti berbagai aktivitas, seminar, dan event terbaru dari HIMA-TI.</p>
        
        <div class="row g-4">
            {{-- Contoh Card Berita (dinamis nanti bisa pakai foreach) --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <img src="{{ asset('images/berita1.jpg') }}" class="card-img-top" alt="Berita 1">
                    <div class="card-body text-start">
                        <h5 class="fw-bold">Seminar Teknologi AI 2025</h5>
                        <p class="small text-muted mb-2">1 Oktober 2025</p>
                        <p class="text-secondary small">Kegiatan seminar membahas dampak AI dalam dunia kerja dan pendidikan tinggi.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <img src="{{ asset('images/berita2.jpg') }}" class="card-img-top" alt="Berita 2">
                    <div class="card-body text-start">
                        <h5 class="fw-bold">Workshop Web Development</h5>
                        <p class="small text-muted mb-2">18 September 2025</p>
                        <p class="text-secondary small">Peserta dilatih membuat website dinamis menggunakan Laravel dan Bootstrap 5.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <img src="{{ asset('images/berita3.jpg') }}" class="card-img-top" alt="Berita 3">
                    <div class="card-body text-start">
                        <h5 class="fw-bold">Pelatihan Desain UI/UX</h5>
                        <p class="small text-muted mb-2">10 September 2025</p>
                        <p class="text-secondary small">Pengenalan prinsip desain antarmuka dan pengalaman pengguna modern.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ url('/berita') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">Lihat Semua Berita</a>
        </div>
    </div>
</section>

{{-- ================= PRESTASI SECTION ================= --}}
<section id="prestasi" class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold text-primary mb-4">Prestasi Mahasiswa</h2>
        <p class="text-secondary mb-5">Mahasiswa Teknologi Informasi yang berprestasi di tingkat nasional dan internasional.</p>
        
        <div class="row g-4">
            {{-- Card Prestasi contoh statis --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <img src="{{ asset('images/prestasi1.jpg') }}" class="rounded-4 mb-3" alt="Prestasi 1">
                    <h6 class="fw-bold">Ahmad Rifqi</h6>
                    <p class="text-secondary small mb-0">Juara 1 Lomba Web Design Nasional</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <img src="{{ asset('images/prestasi2.jpg') }}" class="rounded-4 mb-3" alt="Prestasi 2">
                    <h6 class="fw-bold">Siti Rahma</h6>
                    <p class="text-secondary small mb-0">Finalis Data Science Competition 2025</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <img src="{{ asset('images/prestasi3.jpg') }}" class="rounded-4 mb-3" alt="Prestasi 3">
                    <h6 class="fw-bold">Muhammad Arif</h6>
                    <p class="text-secondary small mb-0">Top 10 Hackathon AI for Society</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
