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

            {{-- 
              PERBAIKAN 1: Nama route diubah dari 'pages.admin.berita.update' menjadi 'admin.berita.update'
              PERBAIKAN 2: Parameter ID diubah dari $berita->id menjadi $berita->id_berita
            --}}
            <form action="{{ route('admin.berita.update', $berita->id_berita) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Method untuk update --}}

                <!-- Judul Berita -->
                <div class="form-group">
                    <label for="judul_berita">Judul Berita</label>
                    {{-- PERBAIKAN 3: name="judul" diubah menjadi "judul_berita" agar sesuai dengan database --}}
                    <input type="text" id="judul_berita" name="judul_berita" class="form-control @error('judul_berita') is-invalid @enderror"
                        value="{{ old('judul_berita', $berita->judul_berita) }}" required>
                    @error('judul_berita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Isi Berita -->
                <div class="form-group">
                    <label for="isi_berita">Isi Berita</label>
                    {{-- PERBAIKAN 4: name="isi" diubah menjadi "isi_berita" --}}
                    <textarea id="isi_berita" name="isi_berita" class="form-control @error('isi_berita') is-invalid @enderror"
                        rows="5">{{ old('isi_berita', $berita->isi_berita) }}</textarea>
                    @error('isi_berita')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label for="kategori">Kategori Berita</label>
                    <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih Kategori...</option>
                        <option value="kegiatan" {{ old('kategori', $berita->kategori) == 'kegiatan' ? 'selected' : '' }}>
                            Kegiatan HIMA-TI
                        </option>
                        <option value="prestasi" {{ old('kategori', $berita->kategori) == 'prestasi' ? 'selected' : '' }}>
                            Prestasi Mahasiswa
                        </option>
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Gambar Berita -->
                <div class="form-group">
                    {{-- Nama input "gambar" ini tidak ada di migrasi Anda, saya asumsikan ini benar --}}
                    {{-- Berdasarkan migrasi, nama kolomnya adalah "gambar_berita" --}}
                    <label for="gambar_berita">Ganti Gambar (Opsional)</label>
                    <input type="file" id="gambar_berita" name="gambar_berita"
                        class="form-control-file @error('gambar_berita') is-invalid @enderror">
                    @error('gambar_berita')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Gambar Saat Ini:</label>
                    @if($berita->gambar_berita)
                        <div>
                            {{-- Saya asumsikan Anda punya 'storage:link' --}}
                            <img src="{{ asset('storage/' . $berita->gambar_berita) }}" alt="Gambar Berita" class="img-thumbnail"
                                style="max-width: 300px; max-height: 200px;">
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