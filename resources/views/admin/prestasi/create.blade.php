<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Prestasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .card { border-radius: 0.75rem; border: none; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-primary mb-4">Form Tambah Data Prestasi</h4>
                        <form action="{{ route('prestasi.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nim" class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nim') is-invalid @enderror" name="nim" value="{{ old('nim') }}" placeholder="Masukkan NIM mahasiswa" required>
                                @error('nim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama mahasiswa" required>
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_lomba" class="form-label fw-semibold">Nama Lomba <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lomba') is-invalid @enderror" name="nama_lomba" value="{{ old('nama_lomba') }}" placeholder="Masukkan nama lomba" required>
                                @error('nama_lomba') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tahun" class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror" name="tahun" value="{{ old('tahun') }}" placeholder="Masukkan tahun" required>
                                @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tingkat" class="form-label fw-semibold">Tingkat <span class="text-danger">*</span></label>
                                <select class="form-control @error('tingkat') is-invalid @enderror" name="tingkat" required>
                                    <option value="">Pilih Tingkat</option>
                                    <option value="Kampus" {{ old('tingkat') == 'Kampus' ? 'selected' : '' }}>Kampus</option>
                                    <option value="Kota" {{ old('tingkat') == 'Kota' ? 'selected' : '' }}>Kota</option>
                                    <option value="Provinsi" {{ old('tingkat') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                    <option value="Nasional" {{ old('tingkat') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                    <option value="Internasional" {{ old('tingkat') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                                </select>
                                @error('tingkat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jenis_prestasi" class="form-label fw-semibold">Jenis Prestasi <span class="text-danger">*</span></label>
                                <select class="form-control @error('jenis_prestasi') is-invalid @enderror" name="jenis_prestasi" required>
                                    <option value="">Pilih Jenis Prestasi</option>
                                    <option value="Akademik" {{ old('jenis_prestasi') == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                                    <option value="Non-Akademik" {{ old('jenis_prestasi') == 'Non-Akademik' ? 'selected' : '' }}>Non-Akademik</option>
                                </select>
                                @error('jenis_prestasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status_validasi" class="form-label fw-semibold">Status Validasi <span class="text-danger">*</span></label>
                                <select class="form-control @error('status_validasi') is-invalid @enderror" name="status_validasi" required>
                                    <option value="">Pilih Status Validasi</option>
                                    <option value="Tervalidasi" {{ old('status_validasi') == 'Tervalidasi' ? 'selected' : '' }}>Tervalidasi</option>
                                    <option value="Menunggu" {{ old('status_validasi') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Ditolak" {{ old('status_validasi') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status_validasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="id_mahasiswa" class="form-label fw-semibold">ID Mahasiswa <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('id_mahasiswa') is-invalid @enderror" name="id_mahasiswa" value="{{ old('id_mahasiswa') }}" placeholder="Masukkan ID mahasiswa" required>
                                @error('id_mahasiswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                                <a href="{{ route('prestasi.index') }}" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
