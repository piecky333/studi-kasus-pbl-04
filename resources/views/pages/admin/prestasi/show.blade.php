@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 fw-bold text-primary">Detail Prestasi Mahasiswa</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">NIM</div>
                <div class="col-md-8">{{ $prestasi->mahasiswa->nim ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Nama Mahasiswa</div>
                <div class="col-md-8">{{ $prestasi->mahasiswa->nama ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Nama Kegiatan</div>
                <div class="col-md-8">{{ $prestasi->nama_kegiatan }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Tingkat Prestasi</div>
                <div class="col-md-8">{{ $prestasi->tingkat_prestasi }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Tahun</div>
                <div class="col-md-8">{{ $prestasi->tahun }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Status Validasi</div>
                <div class="col-md-8">
                    @if($prestasi->status_validasi == 'disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif($prestasi->status_validasi == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">Menunggu</span>
                    @endif
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 fw-semibold">Diverifikasi Oleh</div>
                <div class="col-md-8">
                    {{ $prestasi->admin->nama ?? 'Belum diverifikasi' }}
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.prestasi.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('admin.prestasi.edit', $prestasi->id_prestasi) }}" class="btn btn-warning text-white">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        <form action="{{ route('admin.prestasi.destroy', $prestasi->id_prestasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>
@endsection
