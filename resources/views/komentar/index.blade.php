@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Daftar Komentar</h2>

    <a href="{{ route('komentar.create') }}" class="btn btn-primary mb-3">+ Tambah Komentar</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th>
                <th>Berita</th>
                <th>User</th>
                <th>Isi Komentar</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($komentars as $index => $k)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $k->berita->judul ?? '-' }}</td>
                <td>{{ $k->user->name ?? '-' }}</td>
                <td>{{ $k->isi }}</td>
                <td>{{ $k->tanggal }}</td>
                <td class="text-center">
                    <a href="{{ route('komentar.show', $k->id) }}" class="btn btn-info btn-sm">Lihat</a>
                    <a href="{{ route('komentar.edit', $k->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('komentar.destroy', $k->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus komentar ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada komentar</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
