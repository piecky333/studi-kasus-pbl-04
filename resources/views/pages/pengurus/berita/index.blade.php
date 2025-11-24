@extends('layouts.pengurus')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Berita Saya</h1>
    <a href="{{ route('pengurus.berita.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Berita Baru
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Berita</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beritas as $berita)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $berita->judul_berita }}</td>
                        <td>{{ ucfirst($berita->kategori) }}</td>
                        <td>
                            @if($berita->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($berita->status == 'approved')
                                <span class="badge badge-success">Verified</span>
                            @elseif($berita->status == 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pengurus.berita.edit', $berita->id_berita) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>

                            <form action="{{ route('pengurus.berita.destroy', $berita->id_berita) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus berita ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada berita.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $beritas->links() }}
        </div>
    </div>
</div>
@endsection
