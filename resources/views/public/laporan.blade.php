@extends('layouts.public')

@section('title', 'Laporan Mahasiswa - HIMA-TI Politala')

@section('content')

{{-- ============ HERO SECTION ============ --}}
<section id="laporan-hero" class="text-center text-white d-flex align-items-center justify-content-center"
         style="height: 50vh; background: linear-gradient(135deg, #002855 60%, #004080);">
    <div class="container">
        <h1 class="fw-bold display-6 mb-3">Laporan Mahasiswa</h1>
        <p class="lead mb-4">
            Sampaikan laporan, masukan, atau kendala yang kamu hadapi agar dapat ditindaklanjuti oleh HIMA-TI.
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
                        <p class="text-center text-secondary mb-4">Isi form di bawah ini dengan jujur dan lengkap. Semua data akan dijaga kerahasiaannya.</p>

                        {{-- Pesan Sukses / Error (opsional, nanti dari controller) --}}
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- FORM INPUT --}}
                        <form action="{{ route('laporan.store') }}" method="POST">
                            @csrf

                            {{-- NAMA PELAPOR --}}
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold">Nama Pelapor</label>
                                <input type="text" name="nama" id="nama" class="form-control rounded-3 @error('nama') is-invalid @enderror"
                                       placeholder="Masukkan nama lengkap anda" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- NIM --}}
                            <div class="mb-3">
                                <label for="nim" class="form-label fw-semibold">NIM</label>
                                <input type="text" name="nim" id="nim" class="form-control rounded-3 @error('nim') is-invalid @enderror"
                                       placeholder="Masukkan NIM anda" required>
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- KONTAK --}}
                            <div class="mb-3">
                                <label for="kontak" class="form-label fw-semibold">Kontak (Email / WhatsApp)</label>
                                <input type="text" name="kontak" id="kontak" class="form-control rounded-3"
                                       placeholder="Masukkan kontak yang bisa dihubungi">
                            </div>

                            {{-- JENIS KASUS --}}
                            <div class="mb-3">
                                <label for="jenis_kasus" class="form-label fw-semibold">Jenis Kasus</label>
                                <select name="jenis_kasus" id="jenis_kasus" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Jenis Kasus --</option>
                                    <option value="akademik">Akademik (nilai, dosen, jadwal, tugas, dll)</option>
                                    <option value="non-akademik">Non-Akademik (organisasi, kegiatan, konflik)</option>
                                    <option value="fasilitas">Fasilitas Kampus (ruangan, lab, internet, dsb)</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            {{-- DESKRIPSI LAPORAN --}}
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label fw-semibold">Deskripsi Laporan</label>
                                <textarea name="deskripsi" id="deskripsi" rows="5" 
                                          class="form-control rounded-3 @error('deskripsi') is-invalid @enderror"
                                          placeholder="Tuliskan secara detail kendala, masalah, atau laporan yang ingin disampaikan" required></textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- TANGGAL --}}
                            <div class="mb-3">
                                <label for="tanggal" class="form-label fw-semibold">Tanggal Kejadian</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control rounded-3" required>
                            </div>

                            {{-- KATEGORI URGENSI --}}
                            <div class="mb-4">
                                <label for="urgensi" class="form-label fw-semibold">Tingkat Urgensi</label>
                                <select name="urgensi" id="urgensi" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Urgensi --</option>
                                    <option value="rendah">Rendah</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="tinggi">Tinggi</option>
                                </select>
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
