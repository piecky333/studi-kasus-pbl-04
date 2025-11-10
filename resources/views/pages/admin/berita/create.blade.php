@extends('layouts.admin')

@section('content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Tambah Berita Baru</h1>

    <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Formulir Berita</h6>
        </div>

        <!-- Card Body -->
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="judul_berita">Judul Berita</label>
                    <input type="text" id="judul_berita" name="judul_berita"
                        class="form-control @error('judul_berita') is-invalid @enderror" value="{{ old('judul_berita') }}"
                        required>
                    @error('judul_berita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Isi Berita -->
                <div class="form-group">
                    <label for="isi_berita">Isi Berita</label>
                    <textarea id="isi_berita" name="isi_berita"
                        class="form-control @error('isi_berita') is-invalid @enderror"
                        rows="5">{{ old('isi_berita') }}</textarea>
                    @error('isi_berita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Gambar Berita -->
                <div class="form-group">
                    <label for="gambar_berita">Gambar (Opsional)</label>
                    <input type="file" id="gambar_berita" name="gambar_berita"
                        class="form-control-file @error('gambar_berita') is-invalid @enderror">
                    @error('gambar_berita')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- kategori --}}
                <div class="form-group">
                    <label for="kategori">Kategori Berita</label>
                    <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror"
                        required>
                        <option value="">Pilih Kategori...</option>
                        <option value="kegiatan" {{ old('kategori') == 'kegiatan' ? 'selected' : '' }}>Kegiatan HIMA-TI
                        </option>
                        <option value="prestasi" {{ old('kategori') == 'prestasi' ? 'selected' : '' }}>Prestasi Mahasiswa
                        </option>
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection