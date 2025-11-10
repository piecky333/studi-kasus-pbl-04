@extends('layouts.public')

@section('title', $berita->judul ?? 'Detail Berita')

@section('content')

{{-- ============ HEADER BERITA ============ --}}
<section id="berita-header" class="text-center text-white d-flex align-items-center justify-content-center"
         style="height: 40vh; background: linear-gradient(135deg, #002855 60%, #004080);">
    <div class="container">
        <h1 class="fw-bold display-6 mb-3">{{ $berita->judul ?? 'Judul Berita' }}</h1>
        <p class="lead mb-0"><i class="bi bi-calendar-event"></i> {{ $berita->tanggal->format('d F Y') ?? 'Tanggal Tidak Diketahui' }}</p>
    </div>
</section>

{{-- ============ ISI BERITA ============ --}}
<section id="isi-berita" class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                {{-- Gambar Utama --}}
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" 
                         class="img-fluid rounded-4 shadow-sm">
                </div>

                {{-- Isi Konten --}}
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <p class="text-secondary mb-3">
                        <i class="bi bi-person-circle"></i> Oleh <strong>{{ $berita->penulis ?? 'Admin HIMA-TI' }}</strong>
                    </p>
                    <article class="text-justify">
                        {!! nl2br(e($berita->isi ?? 'Belum ada isi berita.')) !!}
                    </article>
                </div>

                {{-- Tombol Kembali --}}
                <div class="mt-4 text-center">
                    <a href="{{ url('/berita') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Berita
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ KOLOM KOMENTAR ============ --}}
<section id="komentar" class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="bg-white rounded-4 shadow-sm p-4">
                    <h4 class="fw-bold text-primary mb-4">Kolom Komentar</h4>

                    {{-- Form Komentar --}}
                    <form action="{{ route('komentar.store', $berita->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control rounded-3 @error('nama') is-invalid @enderror"
                                   placeholder="Masukkan nama anda" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="isi" class="form-label fw-semibold">Komentar</label>
                            <textarea name="isi" id="isi" rows="3" 
                                      class="form-control rounded-3 @error('isi') is-invalid @enderror"
                                      placeholder="Tulis komentar anda di sini..." required></textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                                <i class="bi bi-chat-dots"></i> Kirim Komentar
                            </button>
                        </div>
                    </form>

                    {{-- List Komentar --}}
                    <hr class="my-4">
                    <h5 class="fw-bold text-dark mb-3">Komentar Pengunjung</h5>

                    @forelse ($berita->komentar as $komen)
                        <div class="mb-3 border-bottom pb-3">
                            <p class="fw-semibold mb-1 text-primary">{{ $komen->nama }}</p>
                            <p class="text-secondary small mb-1">{{ $komen->created_at->diffForHumans() }}</p>
                            <p class="text-muted mb-0">{{ $komen->isi }}</p>
                        </div>
                    @empty
                        <p class="text-secondary">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
