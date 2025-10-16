@extends('layouts.pengurus')
@section('title', 'Kelola Jabatan')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Kelola Jabatan</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('pengurus.jabatan.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Tambah Jabatan
    </a>

    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            @if($jabatan->isEmpty())
                <p class="text-muted">Belum ada data jabatan.</p>
            @else
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Jabatan</th>
                            <th>Divisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jabatan as $j)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $j->nama_jabatan }}</td>
                            <td>{{ $j->divisi->nama_divisi ?? '-' }}</td>
                            <td>
                                <a href="{{ route('pengurus.jabatan.edit', $j->id_jabatan) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('pengurus.jabatan.destroy', $j->id_jabatan) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus jabatan ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
