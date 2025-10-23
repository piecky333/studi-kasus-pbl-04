@extends('layouts.admin')

@section('title', 'Data Prestasi Mahasiswa')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Data Prestasi Mahasiswa</h4>
        <a href="{{ route('admin.prestasi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Prestasi
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabel Prestasi --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Nama Kegiatan</th>
                        <th>Tingkat Prestasi</th>
                        <th>Tahun</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestasi as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->mahasiswa->nim ?? '-' }}</td>
                            <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>{{ ucfirst($item->tingkat_prestasi) }}</td>
                            <td>{{ $item->tahun }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.prestasi.edit', $item->id_prestasi) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.prestasi.destroy', $item->id_prestasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data prestasi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
