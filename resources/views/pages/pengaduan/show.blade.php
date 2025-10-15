@extends('layouts.app')

@section('title', 'Detail Pengaduan - Admin')

@section('content')
<div class="container mt-4">

    <a href="{{ url('/admin/pengaduan') }}" class="btn btn-secondary mb-3">
        ← Kembali ke Daftar Pengaduan
    </a>

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white fw-bold">
            Detail Pengaduan Mahasiswa
        </div>
        <div class="card-body">
            <h5 class="fw-bold mb-3">Informasi Pelapor</h5>
            <p><strong>Nama:</strong> {{ $pengaduan->user->nama ?? 'User tidak ditemukan' }}</p>
        

            <hr>

            <h5 class="fw-bold mb-3">Detail Laporan</h5>
            <p><strong>Jenis Kasus:</strong> {{ $pengaduan->jenis_kasus }}</p>
            <p><strong>Tanggal Pengaduan:</strong> {{ $pengaduan->tanggal_pengaduan }}</p>
            <p><strong>Deskripsi:</strong></p>
            <div class="border p-3 rounded bg-light">
                {{ $pengaduan->deskripsi }}
            </div>

            <hr>

            <h5 class="fw-bold mb-3">Status Laporan</h5>
            <form action="{{ url('/admin/pengaduan/'.$pengaduan->id_pengaduan.'/verifikasi') }}" method="POST" class="d-flex align-items-center">
                @csrf
                @method('PUT')
                <select name="status_validasi" class="form-select w-auto me-3">
                    <option value="menunggu" {{ $pengaduan->status_validasi == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="proses" {{ $pengaduan->status_validasi == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ $pengaduan->status_validasi == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <button type="submit" class="btn btn-success">Perbarui Status</button>
            </form>

            <hr>

            <div class="d-flex justify-content-end">
                <form action="{{ url('/admin/pengaduan/'.$pengaduan->id_pengaduan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Hapus Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
