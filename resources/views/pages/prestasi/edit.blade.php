@extends('layouts.admin')

@section('title', 'Edit Data Prestasi')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 fw-bold">✏️ Edit Data Prestasi Mahasiswa</h3>

    {{-- Perbaikan utama di sini: pakai $prestasi->id_prestasi atau langsung $prestasi --}}
    <form action="{{ route('admin.prestasi.update', $prestasi) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- NIM Mahasiswa -->
        <div class="mb-3 position-relative">
            <label for="nim" class="form-label fw-semibold">NIM Mahasiswa</label>
            <input type="text" id="nim" name="nim" class="form-control" 
                   placeholder="Cari NIM Mahasiswa..." value="{{ $prestasi->mahasiswa->nim ?? '' }}" autocomplete="off">
            <ul id="nimList" class="list-group mt-2 position-absolute w-50" style="z-index: 1000;"></ul>
        </div>

        <!-- Nama Mahasiswa -->
        <div class="mb-3">
            <label for="nama" class="form-label fw-semibold">Nama Mahasiswa</label>
            <input type="text" id="nama" class="form-control" value="{{ $prestasi->mahasiswa->nama ?? '' }}" readonly>
        </div>

        <!-- Nama Kegiatan -->
        <div class="mb-3">
            <label for="nama_kegiatan" class="form-label fw-semibold">Nama Kegiatan</label>
            <input type="text" name="nama_kegiatan" id="nama_kegiatan" 
                   class="form-control @error('nama_kegiatan') is-invalid @enderror"
                   value="{{ old('nama_kegiatan', $prestasi->nama_kegiatan) }}" required>
            @error('nama_kegiatan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tingkat Prestasi -->
        <div class="mb-3">
            <label for="tingkat_prestasi" class="form-label fw-semibold">Tingkat Prestasi</label>
            <select name="tingkat_prestasi" id="tingkat_prestasi" 
                    class="form-select @error('tingkat_prestasi') is-invalid @enderror" required>
                <option value="">-- Pilih Tingkat --</option>
                <option value="Universitas" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Universitas' ? 'selected' : '' }}>Universitas</option>
                <option value="Provinsi" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                <option value="Nasional" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                <option value="Internasional" {{ old('tingkat_prestasi', $prestasi->tingkat_prestasi) == 'Internasional' ? 'selected' : '' }}>Internasional</option>
            </select>
            @error('tingkat_prestasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tahun -->
        <div class="mb-3">
            <label for="tahun" class="form-label fw-semibold">Tahun</label>
            <input type="number" name="tahun" id="tahun" class="form-control @error('tahun') is-invalid @enderror"
                   value="{{ old('tahun', $prestasi->tahun) }}" required>
            @error('tahun')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Status Validasi -->
        <div class="mb-3">
            <label for="status_validasi" class="form-label fw-semibold">Status Validasi</label>
            <select name="status_validasi" id="status_validasi" 
                    class="form-select @error('status_validasi') is-invalid @enderror" required>
                <option value="menunggu" {{ old('status_validasi', $prestasi->status_validasi) == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ old('status_validasi', $prestasi->status_validasi) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ old('status_validasi', $prestasi->status_validasi) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            @error('status_validasi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.prestasi.index') }}" class="btn btn-secondary px-4">← Kembali</a>
            <button type="submit" class="btn btn-primary px-4">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>

{{-- AJAX Pencarian NIM --}}
<script>
document.getElementById('nim').addEventListener('keyup', function() {
    const query = this.value;
    const list = document.getElementById('nimList');
    list.innerHTML = '';

    if (query.length > 1) {
        fetch(`/admin/prestasi/cari-mahasiswa?query=${query}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item', 'list-group-item-action');
                    li.textContent = `${item.nim} - ${item.nama}`;
                    li.addEventListener('click', function() {
                        document.getElementById('nim').value = item.nim;
                        document.getElementById('nama').value = item.nama;
                        list.innerHTML = '';
                    });
                    list.appendChild(li);
                });
            });
    }
});
</script>
@endsection
