@extends('layouts.pengurus')
@section('title', 'Edit Jabatan')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-primary mb-4">Edit Jabatan</h4>

    <form action="{{ route('pengurus.jabatan.update', $jabatan->id_jabatan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
            <input type="text" name="nama_jabatan" class="form-control" value="{{ $jabatan->nama_jabatan }}" required>
        </div>

        <div class="mb-3">
            <label for="id_divisi" class="form-label">Pilih Divisi</label>
            <select name="id_divisi" class="form-select" required>
                @foreach($divisi as $d)
                    <option value="{{ $d->id_divisi }}" {{ $jabatan->id_divisi == $d->id_divisi ? 'selected' : '' }}>
                        {{ $d->nama_divisi }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('pengurus.jabatan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
