<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header text-white fw-bold" style="background-color: #0d6efd;">FORM TAMBAH ANGGOTA</div>
                    <div class="card-body">
                        <form action="{{ route('jabatan.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">NAMA LENGKAP</label>
                                <input type="text" class="form-control @error('nama_anggota') is-invalid @enderror" name="nama_anggota" value="{{ old('nama_anggota') }}" placeholder="Masukkan Nama Lengkap">
                                @error('nama_anggota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">JABATAN STRUKTURAL</label>
                                <select class="form-select @error('jabatan_struktural') is-invalid @enderror" name="jabatan_struktural">
                                    <option value="" selected disabled>Pilih Jabatan</option>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan }}" {{ old('jabatan_struktural') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                                    @endforeach
                                </select>
                                @error('jabatan_struktural')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">DIVISI</label>
                                <select class="form-select @error('divisi') is-invalid @enderror" name="divisi">
                                    <option value="" selected disabled>Pilih Divisi</option>
                                    @foreach ($divisis as $divisi)
                                        <option value="{{ $divisi }}" {{ old('divisi') == $divisi ? 'selected' : '' }}>{{ $divisi }}</option>
                                    @endforeach
                                </select>
                                @error('divisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <a href="{{ route('jabatan.index') }}" class="btn btn-secondary">KEMBALI</a>
                            <button type="submit" class="btn btn-primary">SIMPAN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
