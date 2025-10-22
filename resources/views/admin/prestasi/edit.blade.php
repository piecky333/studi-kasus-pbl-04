<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Prestasi</title>
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
                        <h4 class="card-title fw-bold text-primary mb-4">Form Edit Data Prestasi</h4>
                        <form action="{{ route('admin.prestasi.update', $prestasi->id_prestasi) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama_kegiatan" class="form-label fw-semibold">Nama Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror" name="nama_kegiatan" value="{{ old('nama_kegiatan', $prestasi->nama_kegiatan) }}" required>
                                @error('nama_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tingkat_prestasi" class="form-label fw-semibold">Tingkat Prestasi <span class="text-danger">*</span></label>
                                <select class="form-control @error('tingkat_prestasi') is-invalid @enderror" name="tingkat_prestasi" required>
                                    <option value="">Pilih Tingkat</option>
                                    <option value="Lokal" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Lokal' ? 'selected' : '' }}>Lokal</option>
                                    <option value="Provinsi" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                    <option value="Nasional" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                    <option value="Internasional" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                                </select>
                                @error('tingkat_prestasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tahun" class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror" name="tahun" value="{{ old('tahun', $prestasi->tahun) }}" required>
                                @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status_validasi" class="form-label fw-semibold">Status Validasi <span class="text-danger">*</span></label>
                                <select class="form-control @error('status_validasi') is-invalid @enderror" name="status_validasi" required>
                                    <option value="">Pilih Status Validasi</option>
                                    <option value="menunggu" {{ old('status_validasi', $prestasi->status_validasi) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="disetujui" {{ old('status_validasi', $prestasi->status_validasi) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="ditolak" {{ old('status_validasi', $prestasi->status_validasi) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status_validasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="id_mahasiswa" class="form-label fw-semibold">ID Mahasiswa <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('id_mahasiswa') is-invalid @enderror" name="id_mahasiswa" value="{{ old('id_mahasiswa', $prestasi->id_mahasiswa) }}" required>
                                @error('id_mahasiswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                <a href="{{ route('admin.prestasi.index') }}" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
