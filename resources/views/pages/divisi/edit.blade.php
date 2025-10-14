@extends('layouts.pengurus')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-primary mb-4">Tambah Divisi</h2>

    <form action="{{ route('pengurus.divisi.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm rounded-4">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Divisi</label>
            <input type="text" name="nama_divisi" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Isi / Deskripsi Divisi</label>
            <textarea name="isi_divisi" rows="5" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Foto Divisi</label>
            <input type="file" name="foto_divisi" class="form-control" accept=".jpg,.jpeg,.png">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('pengurus.divisi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
