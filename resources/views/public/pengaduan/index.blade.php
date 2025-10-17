@extends('layouts.public')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Daftar Pengaduan</h2>

    <a href="{{ url('/pengaduan/create') }}" class="btn btn-primary mb-3">+ Buat Pengaduan Baru</a>

    @if($pengaduan->isEmpty())
        <div class="alert alert-info">
            Belum ada pengaduan yang dikirim.
        </div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis Kasus</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengaduan as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->tanggal_pengaduan }}</td>
                    <td>{{ $p->jenis_kasus }}</td>
                    <td>
                        @if($p->status_validasi == 'menunggu')
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @elseif($p->status_validasi == 'proses')
                            <span class="badge bg-primary">Proses</span>
                        @else
                            <span class="badge bg-success">Selesai</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('/pengaduan/'.$p->id_pengaduan) }}" class="btn btn-sm btn-info">Detail</a>
                        <form action="{{ url('/pengaduan/'.$p->id_pengaduan) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
