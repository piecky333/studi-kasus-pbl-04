@extends('layouts.admin')

@section('title', 'Detail Pengaduan')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pengaduan</h1>
    <a href="{{ route('admin.pengaduan.index') }}" 
       class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar
    </a>
</div>

{{-- Alert/Pesan Sukses --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

{{-- Error Validasi --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">

    <!-- Kolom Kiri -->
    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">{{ $pengaduan->judul }}</h6>

                {{-- Badge Status --}}
                @if ($pengaduan->status == 'Terkirim')
                    <span class="badge badge-primary">{{ $pengaduan->status }}</span>
                @elseif ($pengaduan->status == 'Diproses')
                    <span class="badge badge-warning">{{ $pengaduan->status }}</span>
                @elseif ($pengaduan->status == 'Selesai')
                    <span class="badge badge-success">{{ $pengaduan->status }}</span>
                @elseif ($pengaduan->status == 'Ditolak')
                    <span class="badge badge-danger">{{ $pengaduan->status }}</span>
                @endif
            </div>

            <div class="card-body">

                <h6 class="font-weight-bold">Jenis Kasus:</h6>
                <p>{{ $pengaduan->jenis_kasus }}</p>

                <hr>

                <h6 class="font-weight-bold">Deskripsi Lengkap:</h6>
                <p>{!! nl2br(e($pengaduan->deskripsi)) !!}</p>

                <hr>

                {{-- TAMPILAN GAMBAR BUKTI --}}
                <h6 class="font-weight-bold">Bukti Foto / Lampiran:</h6>

                @if ($pengaduan->gambar_bukti)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $pengaduan->gambar_bukti) }}"
                             alt="Bukti Pengaduan"
                             class="img-fluid rounded shadow">
                    </div>
                @else
                    <p class="text-muted">Tidak ada gambar bukti yang diunggah.</p>
                @endif

                <hr>

                <small class="text-muted">
                    Tanggal Pengaduan: {{ $pengaduan->created_at->format('d M Y, H:i') }}
                </small>
            </div>
        </div>

        <!-- Informasi Pelapor -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Pelapor</h6>
            </div>
            <div class="card-body">

                @if ($pengaduan->mahasiswa)
                    <div class="row mb-2">
                        <div class="col-md-4 font-weight-bold">Nama Lengkap</div>
                        <div class="col-md-8">: {{ $pengaduan->mahasiswa->nama }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 font-weight-bold">NIM</div>
                        <div class="col-md-8">: {{ $pengaduan->mahasiswa->nim ?? '-' }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 font-weight-bold">Email</div>
                        <div class="col-md-8">: {{ $pengaduan->mahasiswa->user->email ?? '-' }}</div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        Data pelapor tidak ditemukan.
                    </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Kolom Kanan -->
    <div class="col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Verifikasi</h6>
            </div>
            <div class="card-body">

                <form action="{{ route('admin.pengaduan.verifikasi', $pengaduan->id_pengaduan) }}" 
                      method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="status">Status Pengaduan</label>
                        <select name="status" id="status" 
                                class="form-control @error('status') is-invalid @enderror" required>
                            <option value="Diproses" {{ $pengaduan->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Selesai" {{ $pengaduan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Ditolak" {{ $pengaduan->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-check"></i> Update Status
                    </button>

                </form>

            </div>
        </div>
    </div>

</div>
@endsection
