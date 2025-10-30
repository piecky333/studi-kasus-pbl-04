@extends('layouts.public')

@section('title', 'Berita HIMA-TI Politala')

@section('content')

{{-- ============ HERO SECTION ============ --}}
<section id="berita-hero" class="text-center text-white d-flex align-items-center justify-content-center"
         style="height: 50vh; background: linear-gradient(135deg, #002855 60%, #004080);">
    <div class="container">
        <h1 class="fw-bold display-6 mb-3">Berita & Kegiatan HIMA-TI</h1>
        <p class="lead mb-4">
            Update kegiatan, informasi, dan pengumuman terbaru dari Himpunan Mahasiswa Teknologi Informasi Politala.
        </p>
    </div>
</section>

{{-- ============ LIST BERITA ============ --}}
<section id="daftar-berita" class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-primary text-center mb-4">Berita Terbaru</h2>
        <p class="text-secondary text-center mb-5">Ikuti kegiatan terbaru kami untuk terus terhubung dengan berbagai program dan prestasi mahasiswa.</p>

        <div class="row g-4">
            {{-- Berita 1 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/berita1.jpg') }}" class="card-img-top rounded-top-4" alt="Berita 1">
                    <div class="card-body">
                        <h5 class="fw-bold">Seminar Teknologi 2025</h5>
                        <p class="text-secondary small mb-2"><i class="bi bi-calendar-event"></i> 2 Januari 2025</p>
                        <p class="text-muted small">
                            Seminar bertajuk “Transformasi Digital untuk Generasi Inovatif” menghadirkan pembicara dari dunia industri teknologi.
                        </p>
                        <a href="{{ url('/berita/seminar-teknologi-2025') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>

            {{-- Berita 2 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/berita2.jpg') }}" class="card-img-top rounded-top-4" alt="Berita 2">
                    <div class="card-body">
                        <h5 class="fw-bold">Pelatihan Web Development</h5>
                        <p class="text-secondary small mb-2"><i class="bi bi-calendar-event"></i> 18 Februari 2025</p>
                        <p class="text-muted small">
                            Pelatihan intensif pembuatan website dengan HTML, CSS, dan Laravel yang diikuti oleh mahasiswa Teknologi Informasi Politala.
                        </p>
                        <a href="{{ url('/berita/pelatihan-web-development') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>

            {{-- Berita 3 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/berita3.jpg') }}" class="card-img-top rounded-top-4" alt="Berita 3">
                    <div class="card-body">
                        <h5 class="fw-bold">Lomba Inovasi Teknologi</h5>
                        <p class="text-secondary small mb-2"><i class="bi bi-calendar-event"></i> 12 Maret 2025</p>
                        <p class="text-muted small">
                            Tim HIMA-TI berhasil meraih juara 1 dalam lomba inovasi teknologi tingkat regional Kalimantan.
                        </p>
                        <a href="{{ url('/berita/lomba-inovasi-teknologi') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>

            {{-- Berita 4 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/berita4.jpg') }}" class="card-img-top rounded-top-4" alt="Berita 4">
                    <div class="card-body">
                        <h5 class="fw-bold">Rapat Kerja HIMA-TI 2025</h5>
                        <p class="text-secondary small mb-2"><i class="bi bi-calendar-event"></i> 5 April 2025</p>
                        <p class="text-muted small">
                            Rapat kerja tahunan untuk membahas program kerja, pembagian tugas divisi, dan target organisasi selama satu periode.
                        </p>
                        <a href="{{ url('/berita/rapat-kerja-2025') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>

            {{-- Berita 5 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/berita5.jpg') }}" class="card-img-top rounded-top-4" alt="Berita 5">
                    <div class="card-body">
                        <h5 class="fw-bold">Pengabdian Masyarakat</h5>
                        <p class="text-secondary small mb-2"><i class="bi bi-calendar-event"></i> 21 Mei 2025</p>
                        <p class="text-muted small">
                            Kegiatan sosial HIMA-TI di desa binaan yang berfokus pada pelatihan komputer dasar untuk masyarakat.
                        </p>
                        <a href="{{ url('/berita/pengabdian-masyarakat') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>

            {{-- Berita 6 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/berita6.jpg') }}" class="card-img-top rounded-top-4" alt="Berita 6">
                    <div class="card-body">
                        <h5 class="fw-bold">Workshop UI/UX Design</h5>
                        <p class="text-secondary small mb-2"><i class="bi bi-calendar-event"></i> 10 Juni 2025</p>
                        <p class="text-muted small">
                            Workshop desain antarmuka yang mengajarkan prinsip UI/UX modern dengan studi kasus aplikasi HIMA.
                        </p>
                        <a href="{{ url('/berita/workshop-uiux') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
