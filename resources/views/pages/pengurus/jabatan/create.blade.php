@extends('layouts.pengurus')
@section('title', 'Tambah Jabatan')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Tambah Jabatan</h4>

    <form action="{{ route('pengurus.jabatan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
            <input type="text" name="nama_jabatan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="id_divisi" class="form-label">Pilih Divisi</label>
            <select name="id_divisi" class="form-select" required>
                <option value="">-- Pilih Divisi --</option>
                @foreach($divisi as $d)
                    <option value="{{ $d->id_divisi }}">{{ $d->nama_divisi }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('pengurus.jabatan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
