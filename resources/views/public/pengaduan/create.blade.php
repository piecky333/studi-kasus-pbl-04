@extends('layouts.public')

@section('title', 'Buat Pengaduan')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center fw-bold text-primary">Formulir Pengaduan</h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- Pesan error --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM INPUT --}}
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <form action="{{ route('pengaduan.store') }}" method="POST">
            @csrf

            {{-- sementara id_user diset manual --}}
            <input type="hidden" name="id_user" value="1">

            {{-- Tanggal Pengaduan --}}
            <div class="mb-3">
                <label for="tanggal_pengaduan" class="form-label fw-semibold">Tanggal Kejadian</label>
                <input type="date" name="tanggal_pengaduan" id="tanggal_pengaduan" 
                       class="form-control rounded-3 @error('tanggal_pengaduan') is-invalid @enderror" 
                       required>
                @error('tanggal_pengaduan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jenis Kasus --}}
            <div class="mb-3">
                <label for="jenis_kasus" class="form-label fw-semibold">Jenis Kasus</label>
                <input type="text" name="jenis_kasus" id="jenis_kasus" 
                       class="form-control rounded-3 @error('jenis_kasus') is-invalid @enderror"
                       placeholder="Contoh: Pelanggaran etika, perkelahian, dll." required>
                @error('jenis_kasus')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
                <label for="deskripsi" class="form-label fw-semibold">Deskripsi Kejadian</label>
                <textarea name="deskripsi" id="deskripsi" rows="5"
                          class="form-control rounded-3 @error('deskripsi') is-invalid @enderror"
                          placeholder="Tuliskan kronologi kejadian secara detail..." required></textarea>
                @error('deskripsi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">
                    <i class="bi bi-send-fill me-2"></i>Kirim Pengaduan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
