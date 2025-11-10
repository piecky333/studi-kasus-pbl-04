@extends('layouts.admin')

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Edit Berita</h1>

<div class="card shadow mb-4">
    <!-- Card Header -->
    <div class="card-header py-3 bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Formulir Edit Berita</h6>
    </div>
    
    <!-- Card Body -->
    <div class="card-body">

        <!-- Tampilkan Error Validasi  -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Method untuk update --}}

            <!-- Judul Berita -->
            <div class="form-group">
                <label for="judul">Judul Berita</label>
                <input type="text" id="judul" name="judul" 
                       class="form-control @error('judul') is-invalid @enderror" 
                       value="{{ old('judul', $berita->judul) }}" required>
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Isi Berita -->
            <div class="form-group">
                <label for="isi">Isi Berita</label>
                <textarea id="isi" name="isi" 
                          class="form-control @error('isi') is-invalid @enderror" 
                          rows="5">{{ old('isi', $berita->isi) }}</textarea>
                @error('isi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Gambar Berita -->
            <div class="form-group">
                <label for="gambar">Gambar Baru (Opsional)</label>
                <input type="file" id="gambar" name="gambar" 
                       class="form-control-file @error('gambar') is-invalid @enderror">
                @error('gambar')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Gambar Saat Ini:</label>
                @if($berita->gambar)
                    <div>
                        <img src="{{ Storage::url($berita->gambar) }}" alt="Gambar Berita" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                    </div>
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                @else
                    <div class="text-muted">Tidak ada gambar terpasang.</div>
                @endif
            </div>


            <!-- Tombol -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Update
                </button>
                <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
