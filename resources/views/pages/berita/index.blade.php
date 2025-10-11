@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Berita</h2>
    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary mb-3">+ Tambah Berita</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($beritas as $berita)
            <tr>
                <td>{{ $berita->judul }}</td>
                <td>{{ $berita->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus berita ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
