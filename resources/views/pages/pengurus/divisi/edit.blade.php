@extends('layouts.pengurus')

@section('title', 'Edit Divisi')

@section('content')
<div class="container-fluid">

    <h1 class="h4 text-gray-800 mb-4">Edit Divisi</h1>

    <div class="card shadow">
        <div class="card-body">

            <form action="{{ route('pengurus.divisi.update', $divisi) }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nama divisi --}}
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Nama Divisi</label>
                    <input type="text" name="nama_divisi" class="form-control"
                           value="{{ old('nama_divisi', $divisi->nama_divisi) }}" required>

                    @error('nama_divisi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Isi divisi --}}
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Deskripsi / Isi Divisi</label>
                    <textarea name="isi_divisi" rows="4" class="form-control">{{ old('isi_divisi', $divisi->isi_divisi) }}</textarea>

                    @error('isi_divisi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Foto divisi --}}
                <div class="form-group mb-3">
                    <label class="font-weight-bold d-block">Foto Divisi</label>

                    @if($divisi->foto_divisi)
                        <img src="{{ asset('storage/' . $divisi->foto_divisi) }}"
                             class="img-thumbnail mb-2"
                             width="160">
                    @endif

                    <input type="file" name="foto_divisi" class="form-control">

                    @error('foto_divisi')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="mt-4">
                    <button class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> Update
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
