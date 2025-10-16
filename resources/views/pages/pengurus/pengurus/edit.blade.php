@extends('layouts.pengurus')
@section('title', 'Edit Pengurus')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-3 text-primary">Edit Pengurus</h4>

    <form action="{{ route('pengurus.pengurus.update', $pengurus->id_pengurus) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">Divisi</label>
            <select name="id_divisi" class="form-select" required>
                @foreach($divisi as $d)
                    <option value="{{ $d->id_divisi }}" {{ $pengurus->id_divisi == $d->id_divisi ? 'selected' : '' }}>
                        {{ $d->nama_divisi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">User</label>
            <select name="id_user" class="form-select" required>
                @foreach($users as $u)
                    <option value="{{ $u->id_user }}" {{ $pengurus->id_user == $u->id_user ? 'selected' : '' }}>
                        {{ $u->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Posisi Jabatan</label>
            <input type="text" name="posisi_jabatan" value="{{ $pengurus->posisi_jabatan }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('pengurus.pengurus.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
