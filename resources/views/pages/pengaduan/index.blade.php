@extends('layouts.app')

@section('title', 'Kelola Pengaduan - Admin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Daftar Pengaduan Mahasiswa</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Jenis Kasus</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengaduan as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->user->nama ?? 'User tidak ditemukan' }}</td>
                <td>{{ $p->jenis_kasus }}</td>
                <td>{{ $p->tanggal_pengaduan }}</td>
                <td>
                    <form action="{{ url('/admin/pengaduan/'.$p->id_pengaduan.'/verifikasi') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status_validasi" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="menunggu" {{ $p->status_validasi=='menunggu'?'selected':'' }}>Menunggu</option>
                            <option value="proses" {{ $p->status_validasi=='proses'?'selected':'' }}>Proses</option>
                            <option value="selesai" {{ $p->status_validasi=='selesai'?'selected':'' }}>Selesai</option>
                        </select>
                    </form>
                </td>
                <td>
                    <a href="{{ url('/admin/pengaduan/'.$p->id_pengaduan) }}" class="btn btn-info btn-sm">Detail</a>
                    <form action="{{ url('/admin/pengaduan/'.$p->id_pengaduan) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
