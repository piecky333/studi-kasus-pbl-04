@extends('layouts.pengurus')
@section('title', 'Kelola Pengurus')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-3 text-primary">Kelola Pengurus</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('pengurus.pengurus.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Tambah Pengurus
    </a>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Posisi Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengurus as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->user->nama ?? '-' }}</td>
                            <td>{{ $p->divisi->nama_divisi ?? '-' }}</td>
                            <td>{{ $p->posisi_jabatan }}</td>
                            <td>
                                <a href="{{ route('pengurus.pengurus.edit', $p->id_pengurus) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('pengurus.pengurus.destroy', $p->id_pengurus) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Belum ada data pengurus</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
