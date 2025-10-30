@extends('layouts.pengurus')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold text-primary">Daftar Divisi</h2>

    {{-- Pesan Sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol tambah hanya untuk pengurus --}}
    @if(str_contains(request()->route()->getName(), 'pengurus'))
        <a href="{{ route('pengurus.divisi.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-lg"></i> Tambah Divisi
        </a>
    @endif

    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            @if($divisi->isEmpty())
                <div class="alert alert-info">Belum ada data divisi.</div>
            @else
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Divisi</th>
                            <th>Isi Divisi</th>
                            <th>Foto</th>
                            @if(str_contains(request()->route()->getName(), 'pengurus'))
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($divisi as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->nama_divisi }}</td>
                            <td>{{ Str::limit($d->isi_divisi, 50) }}</td>
                            <td>
                                @if($d->foto_divisi)
                                    <img src="{{ asset('storage/'.$d->foto_divisi) }}" width="80" class="rounded">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>

                            @if(str_contains(request()->route()->getName(), 'pengurus'))
                            <td>
                                <a href="{{ route('pengurus.divisi.edit', $d->id_divisi) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('pengurus.divisi.destroy', $d->id_divisi) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
