@extends('layouts.admin')

@section('title', 'Detail Pengaduan')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pengaduan</h1>
    <a href="{{ route('admin.pengaduan.index') }}" 
       class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar
    </a>
</div>

{{-- Alert/Pesan Sukses (setelah update status) --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Alert jika ada error validasi --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">

    <!-- Kolom Kiri (Detail Isi Pengaduan) -->
    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">{{ $pengaduan->judul }}</h6>
                
                {{-- Badge Status (sesuai logic di index) --}}
                @if ($pengaduan->status == 'Terkirim')
                    <span class="badge badge-primary">{{ $pengaduan->status }}</span>
                @elseif ($pengaduan->status == 'Diproses')
                    <span class="badge badge-warning">{{ $pengaduan->status }}</span>
                @elseif ($pengaduan->status == 'Selesai')
                    <span class="badge badge-success">{{ $pengaduan->status }}</span>
                @elseif ($pengaduan->status == 'Ditolak')
                    <span class="badge badge-danger">{{ $pengaduan->status }}</span>
                @endif
            </div>
            <div class="card-body">
                <h6 class="font-weight-bold">Jenis Kasus:</h6>
                <p>{{ $pengaduan->jenis_kasus }}</p>

                <hr>

                <h6 class="font-weight-bold">Deskripsi Lengkap:</h6>
                {{-- 
                    Sudut Pandang (Format Teks):
                    Menggunakan nl2br() (new-line-to-break) adalah keputusan
                    penting. Ini akan menghormati 'Enter' (baris baru)
                    yang diketik oleh user di <textarea>, membuat deskripsi
                    tetap rapi saat dibaca Admin.
                --}}
                <p>{!! nl2br(e($pengaduan->deskripsi)) !!}</p>

                <hr>
                
                <small class="text-muted">
                    Tanggal Pengaduan: {{ $pengaduan->created_at->format('d M Y, H:i') }}
                </small>
            </div>
        </div>

        <!-- Info Pelapor -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Pelapor</h6>
            </div>
            <div class="card-body">
                {{-- 
                    Sudut Pandang (Data Safety):
                    Kita cek dulu '$pengaduan->mahasiswa' sebelum memanggil datanya.
                    Ini mencegah error jika data mahasiswa-nya (pelapor)
                    sudah terlanjur dihapus dari database.
                --}}
                @if ($pengaduan->mahasiswa)
                    <div class="row">
                        <div class="col-md-4 font-weight-bold">Nama Lengkap</div>
                        <div class="col-md-8">: {{ $pengaduan->mahasiswa->nama }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 font-weight-bold">NIM</div>
                        <div class="col-md-8">: {{ $pengaduan->mahasiswa->nim ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 font-weight-bold">Email</div>
                        {{-- 
                            Data 'email' ada di relasi 'user' (mahasiswa->user->email).
                            Kita cek berlapis untuk keamanan ekstra.
                        --}}
                        <div class="col-md-8">: {{ $pengaduan->mahasiswa->user->email ?? '-' }}</div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        Data pelapor (mahasiswa) tidak ditemukan atau telah dihapus.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom Kanan (Form Aksi Admin) -->
    <div class="col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Verifikasi</h6>
            </div>
            <div class="card-body">
                <p>Ubah status pengaduan ini:</p>
                
                {{-- 
                    Sudut Pandang (Keputusan UI yang Kuat):
                    Menggunakan <form> yang mengarah ke route 'verifikasi'
                    dengan method PUT adalah implementasi yang presisi
                    dari Controller yang kita buat.
                --}}
                <form action="{{ route('admin.pengaduan.verifikasi', $pengaduan->id_pengaduan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="status">Status Pengaduan</label>
                        {{-- 
                            Sudut Pandang (Konsistensi Validasi):
                            Menggunakan <select> (dropdown) adalah keputusan tepat.
                            Ini "memaksa" Admin memilih dari 3 opsi valid,
                            sesuai dengan 'Rule::in([...])' di controller.
                            Ini mencegah Admin mengetik status yang tidak valid.
                        --}}
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            {{-- 
                                Sudut Pandang (Data ERD):
                                Berdasarkan ERD Anda, status default adalah 'Terkirim'.
                                Admin tidak boleh mengembalikannya ke 'Terkirim'.
                            --}}
                            <option value="Diproses" 
                                {{ $pengaduan->status == 'Diproses' ? 'selected' : '' }}>
                                Diproses
                            </option>
                            <option value="Selesai" 
                                {{ $pengaduan->status == 'Selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>
                            <option value="Ditolak" 
                                {{ $pengaduan->status == 'Ditolak' ? 'selected' : '' }}>
                                Ditolak
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-check"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

