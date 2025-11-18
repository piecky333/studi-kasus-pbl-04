@extends('layouts.admin')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Berita</h1>
    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Berita Baru
    </a>
</div>

<!-- Pesan Sukses (Success Alert) -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- DataTables Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Berita</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Judul</th>
                        <th width="15%">Tanggal Publikasi</th>
                        <th width="15%">Status</th>
                        <th width="25%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beritas as $index => $berita)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $berita->judul_berita }}</td>
                            <td>{{ $berita->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($berita->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($berita->status == 'verified')
                                    <span class="badge badge-success">Verified</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.berita.edit', $berita->id_berita) }}" class="btn btn-info btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Tombol Verifikasi / Tolak hanya muncul jika status pending --}}
                                @if($berita->status == 'pending')
                                    <form action="{{ route('admin.berita.verifikasi', $berita->id_berita) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-success btn-sm" title="Verifikasi">Verifikasi</button>
                                    </form>
                                    <form action="{{ route('admin.berita.tolak', $berita->id_berita) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-warning btn-sm" title="Tolak">Tolak</button>
                                    </form>
                                @endif
                                
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal-{{ $berita->id_berita }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal-{{ $berita->id_berita }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $berita->id_berita }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel-{{ $berita->id_berita }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-left">
                                                Apakah Anda yakin ingin menghapus berita ini?
                                                <br><strong>{{ $berita->judul_berita }}</strong>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.berita.destroy', $berita->id_berita) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada berita.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
