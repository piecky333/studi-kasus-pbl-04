@extends('layouts.pengurus')
@section('title', 'Tambah Pengurus')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-3 text-primary">Tambah Pengurus</h4>

    <form action="{{ route('pengurus.pengurus.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Divisi</label>
            <select name="id_divisi" class="form-select" required>
                <option value="">-- Pilih Divisi --</option>
                @foreach($divisi as $d)
                    <option value="{{ $d->id_divisi }}">{{ $d->nama_divisi }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">User</label>
            <select name="id_user" class="form-select" required>
                <option value="">-- Pilih User --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id_user }}">{{ $u->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Posisi Jabatan</label>
            <input type="text" name="posisi_jabatan" class="form-control" placeholder="Contoh: Ketua Divisi" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('pengurus.pengurus.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
