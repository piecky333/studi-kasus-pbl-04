<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pegawai</title>
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
                        <h4 class="card-title fw-bold text-primary mb-4">Form Edit Data Pegawai</h4>
                        <form action="{{ route('jabatan.update', $jabatan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama" class="form-label fw-semibold">Nama Pegawai <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $jabatan->nama) }}" required>
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jabatan" class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                                <select class="form-select @error('jabatan') is-invalid @enderror" name="jabatan" required>
                                    <option value="" disabled>-- Pilih Jabatan --</option>
                                    @foreach ($jabatanOptions as $option)
                                        <option value="{{ $option }}" {{ old('jabatan', $jabatan->jabatan) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label fw-semibold">Deskripsi Jabatan (Opsional)</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="4">{{ old('deskripsi', $jabatan->deskripsi) }}</textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                <a href="{{ route('jabatan.index') }}" class="btn btn-outline-secondary">Batal</a>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

