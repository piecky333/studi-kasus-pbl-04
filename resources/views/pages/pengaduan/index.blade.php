@extends('layouts.admin')

@section('title', 'Manajemen Pengaduan')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Pengaduan</h1>
</div>

{{-- Alert/Pesan Sukses --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- DataTabel Pengaduan -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Pengaduan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>No.</th>
                        <th>Pelapor</th>
                        <th>Judul Pengaduan</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop data pengaduan --}}
                    @forelse ($daftarPengaduan as $item)
                        <tr>
                            <td>{{ $loop->iteration + $daftarPengaduan->firstItem() - 1 }}</td>
                            <td>
                                
                                {{ $item->mahasiswa->nama ?? '[User Dihapus]' }}
                            </td>
                            <td>{{ $item->judul }}</td>
                            <td>
                                
                                @if ($item->status == 'Terkirim')
                                    <span class="badge badge-primary">{{ $item->status }}</span>
                                @elseif ($item->status == 'Diproses')
                                    <span class="badge badge-warning">{{ $item->status }}</span>
                                @elseif ($item->status == 'Selesai')
                                    <span class="badge badge-success">{{ $item->status }}</span>
                                @elseif ($item->status == 'Ditolak')
                                    <span class="badge badge-danger">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <!-- Tombol Detail -->
                                <a href="{{ route('admin.pengaduan.show', $item->id_pengaduan) }}" 
                                   class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                <form action="{{ route('admin.pengaduan.destroy', $item->id_pengaduan) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Data pengaduan tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Link Paginasi --}}
        <div class="mt-3">
            {{ $daftarPengaduan->links() }}
        </div>
    </div>
</div>
@endsection
