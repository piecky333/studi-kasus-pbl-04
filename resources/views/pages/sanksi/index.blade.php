@extends('layouts.admin')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Sanksi</h1>
    <a href="{{ route('admin.sanksi.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Sanksi Baru
    </a>
</div>

<!-- Pesan Sukses (Success Alert) -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Sanksi Mahasiswa</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Jenis Sanksi</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sanksi as $index => $item)
                        <tr>
                            <td>{{ $sanksi->firstItem() + $index }}</td> 
                            <td>{{ $item->mahasiswa->nama ?? '[Mahasiswa Dihapus]' }}</td>
                            <td>
                                @if($item->jenis_sanksi == 'Ringan')
                                    <span class="badge badge-success">{{ $item->jenis_sanksi }}</span>
                                @elseif($item->jenis_sanksi == 'Sedang')
                                    <span class="badge badge-warning">{{ $item->jenis_sanksi }}</span>
                                @else
                                    <span class="badge badge-danger">{{ $item->jenis_sanksi ?? 'Berat' }}</span>
                                @endif
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>
                                {{ $item->tanggal_sanksi ? \Carbon\Carbon::parse($item->tanggal_sanksi)->format('d M Y') : '-' }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.sanksi.edit', $item->id_sanksi) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal-{{ $item->id_sanksi }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal-{{ $item->id_sanksi }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-left">
                                                Apakah Anda yakin ingin menghapus sanksi untuk <strong>{{ $item->mahasiswa->nama ?? '' }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                {{-- Form Hapus --}}
                                                <form action="{{ route('admin.sanksi.destroy', $item->id_sanksi) }}" method="POST" class="d-inline">
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
                            <td colspan="6" class="text-center">
                                Tidak ada data sanksi untuk saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        
    </div>
</div>
@endsection
