@extends('layouts.pengurus')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-3">Kelola Keuangan</h4>

    <a href="{{ route('pengurus.keuangan.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle"></i> Tambah Iuran
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Divisi</th>
                    <th>Pengurus</th>
                    <th>Jumlah Iuran</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($keuangan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->divisi->nama_divisi ?? '-' }}</td>
                        <td>{{ $item->pengurus->user->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($item->jumlah_iuran, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                        <td>
                            <span class="badge 
                                @if($item->status_pembayaran == 'lunas') bg-success 
                                @elseif($item->status_pembayaran == 'proses') bg-warning 
                                @else bg-danger @endif">
                                {{ ucfirst($item->status_pembayaran) }}
                            </span>
                        </td>
                        <td>{{ $item->tanggal_bayar }}</td>
                        <td>
                            <a href="{{ route('pengurus.keuangan.edit', $item->id_keuangan) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('pengurus.keuangan.destroy', $item->id_keuangan) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Belum ada data keuangan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
