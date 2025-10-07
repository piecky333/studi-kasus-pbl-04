@extends('layouts.public')

@section('title', 'Divisi HIMA-TI Politala')

@section('content')

{{-- ================= HERO SECTION ================= --}}
<section id="divisi-hero" class="text-center text-white d-flex align-items-center justify-content-center" 
         style="height: 60vh; background: linear-gradient(135deg, #002855 60%, #004080);">
    <div class="container">
        <h1 class="fw-bold display-5 mb-3">Divisi HIMA-TI Politala</h1>
        <p class="lead mb-4">Setiap divisi memiliki peran penting dalam mendukung aktivitas, pengembangan, dan inovasi mahasiswa Teknologi Informasi.</p>
    </div>
</section>

{{-- ================= LIST DIVISI ================= --}}
<section id="daftar-divisi" class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold text-primary mb-4">Struktur Divisi HIMA-TI</h2>
        <p class="text-secondary mb-5">Berikut divisi-divisi yang berperan aktif dalam mendukung visi dan misi organisasi.</p>

        <div class="row g-4">
            {{-- =========== DIVISI 1 =========== --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="mb-3"><i class="bi bi-gear-fill fs-1 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">Divisi Riset & Teknologi</h5>
                    <p class="text-secondary small mb-4">
                        Berfokus pada pengembangan inovasi dan pelatihan teknologi informasi. Divisi ini mengadakan workshop, coding camp, dan riset berbasis IT.
                    </p>
                    <a href="{{ url('/divisi/riset-teknologi') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 small">Lihat Profil</a>
                </div>
            </div>

            {{-- =========== DIVISI 2 =========== --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="mb-3"><i class="bi bi-people-fill fs-1 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">Divisi Kaderisasi</h5>
                    <p class="text-secondary small mb-4">
                        Membangun semangat kepemimpinan, loyalitas, dan kebersamaan melalui kegiatan internal seperti pelatihan dan mentoring anggota baru.
                    </p>
                    <a href="{{ url('/divisi/kaderisasi') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 small">Lihat Profil</a>
                </div>
            </div>

            {{-- =========== DIVISI 3 =========== --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="mb-3"><i class="bi bi-broadcast fs-1 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">Divisi Humas & Media</h5>
                    <p class="text-secondary small mb-4">
                        Bertanggung jawab dalam publikasi kegiatan, branding HIMA-TI, serta pengelolaan media sosial dan hubungan eksternal.
                    </p>
                    <a href="{{ url('/divisi/humas-media') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 small">Lihat Profil</a>
                </div>
            </div>

            {{-- =========== DIVISI 4 =========== --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="mb-3"><i class="bi bi-briefcase-fill fs-1 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">Divisi Kewirausahaan</h5>
                    <p class="text-secondary small mb-4">
                        Mengembangkan jiwa bisnis mahasiswa melalui pelatihan kewirausahaan, bazar, dan pengelolaan dana organisasi secara kreatif.
                    </p>
                    <a href="{{ url('/divisi/kewirausahaan') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 small">Lihat Profil</a>
                </div>
            </div>

            {{-- =========== DIVISI 5 =========== --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="mb-3"><i class="bi bi-chat-dots-fill fs-1 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">Divisi Advokasi & Aspirasi</h5>
                    <p class="text-secondary small mb-4">
                        Menjadi jembatan komunikasi antara mahasiswa dan pihak kampus, menyampaikan aspirasi, serta membantu menyelesaikan permasalahan akademik.
                    </p>
                    <a href="{{ url('/divisi/advokasi-aspirasi') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 small">Lihat Profil</a>
                </div>
            </div>

            {{-- =========== DIVISI 6 =========== --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <div class="mb-3"><i class="bi bi-calendar-event-fill fs-1 text-primary"></i></div>
                    <h5 class="fw-bold mb-2">Divisi Acara & Kreativitas</h5>
                    <p class="text-secondary small mb-4">
                        Mengatur kegiatan internal dan eksternal seperti seminar, lomba, hingga kegiatan sosial kampus yang melibatkan mahasiswa TI.
                    </p>
                    <a href="{{ url('/divisi/acara-kreativitas') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 small">Lihat Profil</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
