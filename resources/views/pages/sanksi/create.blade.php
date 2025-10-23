@extends('layouts.admin')

@section('content')

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Tambah Data Sanksi</h1>

<div class="card shadow mb-4">
    <!-- Card Header -->
    <div class="card-header py-3 bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Formulir Sanksi Mahasiswa</h6>
    </div>
    
    <!-- Card Body -->
    <div class="card-body">

        <!-- Tampilkan Error Validasi (Profesional) -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.sanksi.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="id_mahasiswa">Mahasiswa</label>
                <select name="id_mahasiswa" id="id_mahasiswa" 
                        class="form-control @error('id_mahasiswa') is-invalid @enderror" 
                        required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($mahasiswa as $mhs)
                        <option value="{{ $mhs->id_mahasiswa }}" {{ old('id_mahasiswa') == $mhs->id_mahasiswa ? 'selected' : '' }}>
                            {{ $mhs->nama }}
                        </option>
                    @endforeach
                </select>
                @error('id_mahasiswa')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Jenis Sanksi -->
            <div class="form-group">
                <label for="jenis_sanksi">Jenis Sanksi</label>
                <input type="text" id="jenis_sanksi" name="jenis_sanksi" 
                       class="form-control @error('jenis_sanksi') is-invalid @enderror"
                       value="{{ old('jenis_sanksi') }}" required>
                @error('jenis_sanksi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tanggal Sanksi -->
            <div class="form-group">
                <label for="tanggal_sanksi">Tanggal Sanksi</label>
                <input type="date" id="tanggal_sanksi" name="tanggal_sanksi" 
                       class="form-control @error('tanggal_sanksi') is-invalid @enderror"
                       value="{{ old('tanggal_sanksi') }}">
                @error('tanggal_sanksi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
                <a href="{{ route('admin.sanksi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
