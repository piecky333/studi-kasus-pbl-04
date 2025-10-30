@extends('layouts.admin')

@section('title', 'Tambah Prestasi Mahasiswa')

@section('content')
<div class="container mt-4">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Tambah Prestasi Mahasiswa</h4>
        <a href="{{ route('admin.prestasi.index') }}" class="btn btn-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.prestasi.store') }}" method="POST">
                @csrf

                {{-- Cari Mahasiswa (NIM) --}}
                <div class="mb-3">
                    <label for="nim" class="form-label fw-semibold">Cari Mahasiswa (NIM)</label>
                    <div class="input-group">
                        <input type="text" id="nim" name="nim" class="form-control" placeholder="Masukkan NIM mahasiswa..." required>
                        <button type="button" class="btn btn-outline-primary" id="btnCari">Cari</button>
                    </div>
                    <div id="hasilMahasiswa" class="form-text mt-2 text-muted"></div>
                </div>

                {{-- Hidden ID Mahasiswa --}}
                <input type="hidden" name="id_mahasiswa" id="id_mahasiswa">

                {{-- Judul Prestasi --}}
                <div class="mb-3">
                    <label for="judul_prestasi" class="form-label fw-semibold">Judul Prestasi</label>
                    <input type="text" id="judul_prestasi" name="judul_prestasi" 
                           class="form-control @error('judul_prestasi') is-invalid @enderror" 
                           placeholder="Masukkan judul prestasi..." value="{{ old('judul_prestasi') }}" required>
                    @error('judul_prestasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tingkat Prestasi --}}
                <div class="mb-3">
                    <label for="tingkat" class="form-label fw-semibold">Tingkat Prestasi</label>
                    <select id="tingkat" name="tingkat" 
                            class="form-select @error('tingkat') is-invalid @enderror" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="Lokal" {{ old('tingkat') == 'Lokal' ? 'selected' : '' }}>Lokal</option>
                        <option value="Provinsi" {{ old('tingkat') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                        <option value="Nasional" {{ old('tingkat') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="Internasional" {{ old('tingkat') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                    @error('tingkat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal Prestasi --}}
                <div class="mb-3">
                    <label for="tanggal" class="form-label fw-semibold">Tanggal Prestasi</label>
                    <input type="date" id="tanggal" name="tanggal" 
                           class="form-control @error('tanggal') is-invalid @enderror" 
                           value="{{ old('tanggal') }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi / Keterangan --}}
                <div class="mb-3">
                    <label for="deskripsi" class="form-label fw-semibold">Deskripsi / Keterangan</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" 
                              class="form-control @error('deskripsi') is-invalid @enderror"
                              placeholder="Tuliskan keterangan prestasi...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tombol Simpan --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('btnCari').addEventListener('click', function() {
    const nim = document.getElementById('nim').value.trim();
    const hasil = document.getElementById('hasilMahasiswa');
    const idInput = document.getElementById('id_mahasiswa');

    if (!nim) {
        hasil.textContent = 'Masukkan NIM terlebih dahulu.';
        hasil.classList.add('text-danger');
        return;
    }

    hasil.textContent = 'Mencari data mahasiswa...';
    hasil.classList.remove('text-danger');

    fetch(`/admin/prestasi/cari-mahasiswa?nim=${nim}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                hasil.innerHTML = `<strong>${data.mahasiswa.nama}</strong> (${data.mahasiswa.nim}) ditemukan ✅`;
                idInput.value = data.mahasiswa.id_mahasiswa;
            } else {
                hasil.textContent = 'Mahasiswa tidak ditemukan ❌';
                hasil.classList.add('text-danger');
                idInput.value = '';
            }
        })
        .catch(() => {
            hasil.textContent = 'Terjadi kesalahan saat mencari data.';
            hasil.classList.add('text-danger');
        });
});
</script>
@endsection
