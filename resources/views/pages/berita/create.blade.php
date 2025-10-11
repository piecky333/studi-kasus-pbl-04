@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Berita Baru</h2>

    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul_berita" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Isi</label>
            <textarea name="isi_berita" class="form-control" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label>Gambar (opsional)</label>
            <input type="file" name="gambar_berita" class="form-control">
        </div>
        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
