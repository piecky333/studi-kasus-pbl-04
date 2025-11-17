@extends('layouts.pengurus')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Edit Berita</h1>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('pengurus.berita.update', $berita->id_berita) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Judul Berita</label>
        <input type="text" name="judul_berita" class="form-control" value="{{ old('judul_berita', $berita->judul_berita) }}" required>
    </div>
    <div class="form-group">
        <label>Kategori</label>
        <select name="kategori" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="kegiatan" {{ old('kategori', $berita->kategori)=='kegiatan'?'selected':'' }}>Kegiatan</option>
            <option value="prestasi" {{ old('kategori', $berita->kategori)=='prestasi'?'selected':'' }}>Prestasi</option>
        </select>
    </div>
    <div class="form-group">
        <label>Isi Berita</label>
        <textarea name="isi_berita" class="form-control" rows="5" required>{{ old('isi_berita', $berita->isi_berita) }}</textarea>
    </div>
    <div class="form-group">
        <label>Gambar (Opsional)</label>
        @if($berita->gambar_berita)
            <div class="mb-2">
                <img src="{{ asset('storage/'.$berita->gambar_berita) }}" alt="Gambar Berita" width="200">
            </div>
        @endif
        <input type="file" name="gambar_berita" class="form-control-file">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('pengurus.berita.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
