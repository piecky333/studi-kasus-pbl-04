@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Berita</h2>

    <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" value="{{ $berita->judul }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Isi</label>
            <textarea name="isi" class="form-control" rows="5" required>{{ $berita->isi }}</textarea>
        </div>
        <div class="mb-3">
            <label>Gambar Baru (opsional)</label>
            <input type="file" name="gambar" class="form-control">
            @if($berita->gambar)
                <small>Gambar saat ini: {{ $berita->gambar }}</small>
            @endif
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
