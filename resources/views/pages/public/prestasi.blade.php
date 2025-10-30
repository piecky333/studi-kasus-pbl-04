@extends('layouts.public')

@section('title', 'Prestasi Mahasiswa - HIMA-TI Politala')

@section('content')

{{-- ============ HERO SECTION ============ --}}
<section id="prestasi-hero" class="text-center text-white d-flex align-items-center justify-content-center"
         style="height: 50vh; background: linear-gradient(135deg, #002855 60%, #004080);">
    <div class="container">
        <h1 class="fw-bold display-6 mb-3">Prestasi Mahasiswa</h1>
        <p class="lead mb-4">
            Daftar prestasi mahasiswa HIMA-TI Politala dalam bidang akademik maupun non-akademik.
        </p>
    </div>
</section>

{{-- ============ LIST PRESTASI ============ --}}
<section id="daftar-prestasi" class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-primary text-center mb-4">Capaian Prestasi</h2>
        <p class="text-secondary text-center mb-5">Berikut adalah beberapa prestasi yang telah diraih mahasiswa HIMA-TI Politeknik Negeri Tanah Laut.</p>

        <div class="row g-4">
            {{-- Prestasi 1 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/prestasi1.jpg') }}" class="card-img-top rounded-top-4" alt="Prestasi 1">
                    <div class="card-body">
                        <h5 class="fw-bold">Juara 1 Lomba Pemrograman Nasional</h5>
                        <p class="text-secondary small">Diraih oleh: <span class="fw-semibold">Ahmad Rifqi</span></p>
                        <p class="text-muted small">Ajang kompetisi pemrograman tingkat nasional 2024 yang diikuti lebih dari 100 perguruan tinggi.</p>
                    </div>
                </div>
            </div>

            {{-- Prestasi 2 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/prestasi2.jpg') }}" class="card-img-top rounded-top-4" alt="Prestasi 2">
                    <div class="card-body">
                        <h5 class="fw-bold">Best Design Hackathon Kalimantan</h5>
                        <p class="text-secondary small">Diraih oleh: <span class="fw-semibold">Siti Rahma</span></p>
                        <p class="text-muted small">Penghargaan desain aplikasi terbaik pada hackathon se-Kalimantan 2024.</p>
                    </div>
                </div>
            </div>

            {{-- Prestasi 3 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/prestasi3.jpg') }}" class="card-img-top rounded-top-4" alt="Prestasi 3">
                    <div class="card-body">
                        <h5 class="fw-bold">Juara 2 Debat Teknologi Nasional</h5>
                        <p class="text-secondary small">Diraih oleh: <span class="fw-semibold">Rizky Hidayat & Tim</span></p>
                        <p class="text-muted small">Kompetisi debat teknologi informasi antar universitas tingkat nasional 2025.</p>
                    </div>
                </div>
            </div>

            {{-- Prestasi 4 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/prestasi4.jpg') }}" class="card-img-top rounded-top-4" alt="Prestasi 4">
                    <div class="card-body">
                        <h5 class="fw-bold">Juara 3 Startup Competition</h5>
                        <p class="text-secondary small">Diraih oleh: <span class="fw-semibold">Nurul Aini</span></p>
                        <p class="text-muted small">Ide startup inovatif di bidang teknologi kesehatan pada event Startup Competition 2024.</p>
                    </div>
                </div>
            </div>

            {{-- Prestasi 5 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/prestasi5.jpg') }}" class="card-img-top rounded-top-4" alt="Prestasi 5">
                    <div class="card-body">
                        <h5 class="fw-bold">Juara 1 E-Sport Politeknik Cup</h5>
                        <p class="text-secondary small">Diraih oleh: <span class="fw-semibold">Tim HIMA-TI</span></p>
                        <p class="text-muted small">Kompetisi E-Sport antar politeknik se-Indonesia, cabang Mobile Legends, tahun 2024.</p>
                    </div>
                </div>
            </div>

            {{-- Prestasi 6 --}}
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <img src="{{ asset('images/prestasi6.jpg') }}" class="card-img-top rounded-top-4" alt="Prestasi 6">
                    <div class="card-body">
                        <h5 class="fw-bold">Finalis PKM Nasional</h5>
                        <p class="text-secondary small">Diraih oleh: <span class="fw-semibold">Kelompok Riset TI</span></p>
                        <p class="text-muted small">Masuk ke dalam 10 besar finalis Program Kreativitas Mahasiswa (PKM) tingkat nasional.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
