@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Sanksi</h3>
    <form action="{{ route('admin.sanksi.update', $sanksi->id_sanksi) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Mahasiswa</label>
            <select name="id_mahasiswa" class="form-control" required>
                @foreach($mahasiswa as $mhs)
                    <option value="{{ $mhs->id_mahasiswa }}" {{ $mhs->id_mahasiswa == $sanksi->id_mahasiswa ? 'selected' : '' }}>
                        {{ $mhs->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Jenis Sanksi</label>
            <input type="text" name="jenis_sanksi" value="{{ $sanksi->jenis_sanksi }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Sanksi</label>
            <input type="date" name="tanggal_sanksi" value="{{ $sanksi->tanggal_sanksi }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.sanksi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
