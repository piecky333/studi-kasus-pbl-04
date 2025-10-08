@extends('layouts.public')

@section('title', 'Laporan Mahasiswa - HIMA-TI Politala')

@section('content')

{{-- ============ HERO SECTION ============ --}}
<section id="laporan-hero" class="text-center text-white d-flex align-items-center justify-content-center"
         style="height: 50vh; background: linear-gradient(135deg, #002855 60%, #004080);">
    <div class="container">
        <h1 class="fw-bold display-6 mb-3">Laporan Mahasiswa</h1>
        <p class="lead mb-4">
            Sampaikan laporan atau kendala yang kamu alami agar dapat ditindaklanjuti oleh pengurus HIMA-TI.
        </p>
    </div>
</section>

{{-- ============ FORM LAPORAN ============ --}}
<section id="form-laporan" class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="fw-bold text-center text-primary mb-4">Formulir Laporan Mahasiswa</h3>
                        <p class="text-center text-secondary mb-4">
                            Mohon isi data di bawah ini secara lengkap dan jujur. Semua laporan akan ditinjau oleh pengurus HIMA-TI.
                        </p>

                        {{-- Pesan Sukses --}}
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- FORM INPUT --}}
                        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- JUDUL LAPORAN --}}
                            <div class="mb-3">
                                <label for="judul" class="form-label fw-semibold">Judul Laporan</label>
                                <input type="text" name="judul" id="judul"
                                       class="form-control rounded-3 @error('judul') is-invalid @enderror"
                                       placeholder="Tuliskan judul singkat laporan kamu" required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ISI LAPORAN --}}
                            <div class="mb-3">
                                <label for="isi" class="form-label fw-semibold">Isi / Deskripsi Laporan</label>
                                <textarea name="isi" id="isi" rows="5"
                                          class="form-control rounded-3 @error('isi') is-invalid @enderror"
                                          placeholder="Jelaskan secara detail laporan atau kendala yang kamu alami" required></textarea>
                                @error('isi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- TANGGAL KEJADIAN --}}
                            <div class="mb-3">
                                <label for="tanggal" class="form-label fw-semibold">Tanggal Kejadian</label>
                                <input type="date" name="tanggal" id="tanggal"
                                       class="form-control rounded-3 @error('tanggal') is-invalid @enderror" required>
                                @error('tanggal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- BUKTI LAPORAN (FILE) --}}
                            <div class="mb-3">
                                <label for="bukti" class="form-label fw-semibold">Unggah Bukti Laporan (opsional)</label>
                                <input type="file" name="bukti" id="bukti"
                                       class="form-control rounded-3 @error('bukti') is-invalid @enderror"
                                       accept=".jpg,.jpeg,.png,.pdf">
                                <small class="text-secondary">Format diperbolehkan: JPG, PNG, PDF. Maksimal 2MB.</small>
                                @error('bukti')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- BUTTON --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">
                                    <i class="bi bi-send-fill me-2"></i>Kirim Laporan
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
