@extends('layouts.public')

@section('content')
<div class="container mt-4">
    <h2>Detail Pengaduan</h2>
    <div class="card mt-3">
        <div class="card-body">
            <p><strong>Tanggal Pengaduan:</strong> {{ $pengaduan->tanggal_pengaduan }}</p>
            <p><strong>Jenis Kasus:</strong> {{ $pengaduan->jenis_kasus }}</p>
            <p><strong>Deskripsi:</strong> {{ $pengaduan->deskripsi }}</p>
            <p><strong>Status Validasi:</strong> 
                <span class="badge 
                    @if($pengaduan->status_validasi == 'menunggu') bg-warning
                    @elseif($pengaduan->status_validasi == 'proses') bg-info
                    @else bg-success @endif">
                    {{ ucfirst($pengaduan->status_validasi) }}
                </span>
            </p>
        </div>
    </div>

    <a href="{{ route('pengaduan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
