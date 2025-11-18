@extends('layouts.pengurus')

@section('title', 'Tambah Divisi')

@section('content')
<div class="container-fluid">

    <h1 class="h4 text-gray-800 mb-4">Tambah Divisi</h1>

    <div class="card shadow">
        <div class="card-body">

            <form action="{{ route('pengurus.divisi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nama divisi --}}
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Nama Divisi</label>
                    <input type="text" name="nama_divisi" class="form-control"
                           placeholder="Masukkan nama divisi"
                           value="{{ old('nama_divisi') }}" required>

                    @error('nama_divisi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Isi divisi --}}
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Deskripsi / Isi Divisi</label>
                    <textarea name="isi_divisi" rows="4" class="form-control"
                              placeholder="Masukkan deskripsi divisi">{{ old('isi_divisi') }}</textarea>

                    @error('isi_divisi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Foto --}}
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Foto Divisi (Opsional)</label>
                    <input type="file" name="foto_divisi" class="form-control">

                    @error('foto_divisi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="mt-4">
                    <button class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('pengurus.divisi.index') }}" class="btn btn-secondary px-4">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
