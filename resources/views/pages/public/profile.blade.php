@extends('layouts.public')

@section('title', 'Profile HIMA-TI Politala')

@section('content')

{{-- ================= HERO / HEADER ================= --}}
<section id="profil-hero" class="text-center text-white d-flex align-items-center justify-content-center" 
         style="height: 60vh; background: linear-gradient(135deg, #002855 60%, #004080);">
    <div class="container">
        <h1 class="fw-bold display-5 mb-3">Profil Himpunan Mahasiswa Teknologi Informasi</h1>
        <p class="lead">Menjadi wadah penggerak kolaborasi, inovasi, dan aspirasi mahasiswa Teknologi Informasi Politala</p>
    </div>
</section>

{{-- ================= SEJARAH ================= --}}
<section id="sejarah" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="{{ asset('images/hima-history.jpg') }}" alt="Sejarah HIMA-TI" 
                     class="img-fluid rounded-4 shadow-sm">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold text-primary mb-3">Sejarah Singkat</h2>
                <p class="text-secondary">
                    Himpunan Mahasiswa Teknologi Informasi (HIMA-TI) berdiri sebagai organisasi mahasiswa di bawah Program Studi Teknologi Informasi Politeknik Negeri Tanah Laut.
                    Didirikan oleh mahasiswa angkatan awal dengan semangat kolaborasi dan keinginan untuk mengembangkan potensi mahasiswa di bidang teknologi.
                </p>
                <p class="text-secondary">
                    Sejak awal berdirinya, HIMA-TI aktif menjadi penghubung antara mahasiswa, dosen, dan pihak kampus dalam menyuarakan aspirasi serta memfasilitasi kegiatan akademik maupun non-akademik.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ================= VISI & MISI ================= --}}
<section id="visi-misi" class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold text-primary mb-4">Visi & Misi</h2>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-4">
                    <h5 class="fw-bold text-dark">Visi</h5>
                    <p class="text-secondary">
                        Mewujudkan HIMA-TI sebagai organisasi mahasiswa yang aktif, inovatif, dan berintegritas dalam membangun ekosistem teknologi informasi yang berdaya guna bagi kampus dan masyarakat.
                    </p>
                </div>

                <div>
                    <h5 class="fw-bold text-dark">Misi</h5>
                    <ul class="text-secondary text-start list-unstyled ps-4">
                        <li class="mb-2">1. Mengembangkan potensi dan kreativitas mahasiswa di bidang teknologi informasi.</li>
                        <li class="mb-2">2. Membangun komunikasi dan sinergi antara mahasiswa, dosen, dan pihak kampus.</li>
                        <li class="mb-2">3. Mengadakan kegiatan akademik, pelatihan, dan pengabdian masyarakat berbasis teknologi.</li>
                        <li class="mb-2">4. Menumbuhkan semangat kepemimpinan, tanggung jawab, dan kolaborasi antar anggota.</li>
                        <li>5. Menjadi wadah penyampaian aspirasi dan inspirasi bagi seluruh mahasiswa Teknologi Informasi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= STRUKTUR ORGANISASI ================= --}}
<section id="struktur" class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold text-primary mb-4">Struktur Organisasi</h2>
        <p class="text-secondary mb-5">Susunan kepengurusan HIMA-TI Politeknik Negeri Tanah Laut periode 2025</p>

        {{-- Contoh Struktur Sederhana --}}
        <div class="row justify-content-center g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <img src="{{ asset('images/ketua.jpg') }}" class="rounded-circle mx-auto mb-3" style="width:100px; height:100px; object-fit:cover;" alt="Ketua HIMA">
                    <h6 class="fw-bold mb-1">Ahmad Rifqi</h6>
                    <p class="text-secondary small mb-0">Ketua HIMA</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <img src="{{ asset('images/wakil.jpg') }}" class="rounded-circle mx-auto mb-3" style="width:100px; height:100px; object-fit:cover;" alt="Wakil Ketua">
                    <h6 class="fw-bold mb-1">Siti Rahma</h6>
                    <p class="text-secondary small mb-0">Wakil Ketua</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <img src="{{ asset('images/sekretaris.jpg') }}" class="rounded-circle mx-auto mb-3" style="width:100px; height:100px; object-fit:cover;" alt="Sekretaris">
                    <h6 class="fw-bold mb-1">Rizky Hidayat</h6>
                    <p class="text-secondary small mb-0">Sekretaris</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                    <img src="{{ asset('images/bendahara.jpg') }}" class="rounded-circle mx-auto mb-3" style="width:100px; height:100px; object-fit:cover;" alt="Bendahara">
                    <h6 class="fw-bold mb-1">Nurul Aini</h6>
                    <p class="text-secondary small mb-0">Bendahara</p>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <a href="{{ url('/divisi') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">Lihat Divisi HIMA</a>
        </div>
    </div>
</section>

@endsection
