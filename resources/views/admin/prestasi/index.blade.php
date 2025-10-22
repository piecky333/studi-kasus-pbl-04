<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Prestasi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .card { border-radius: 0.75rem; border: none; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); }
        .table thead { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center mb-4 fw-bold text-primary">Manajemen Prestasi Mahasiswa</h2>
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.prestasi.create') }}" class="btn btn-success mb-3"><i class="bi bi-plus-lg"></i> Tambah Data Prestasi</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">NIM</th>
                                        <th scope="col">NAMA</th>
                                        <th scope="col">NAMA LOMBA</th>
                                        <th scope="col">TAHUN</th>
                                        <th scope="col">TINGKAT</th>
                                        <th scope="col">JENIS PRESTASI</th>
                                        <th scope="col">STATUS VALIDASI</th>
                                        <th scope="col" class="text-center">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($prestasis as $item)
                                        <tr>
                                            <td class="fw-semibold">{{ $item->mahasiswa->nim ?? '-' }}</td>
                                            <td>{{ $item->mahasiswa->nama ?? '-' }}</td>
                                            <td>{{ $item->nama_kegiatan }}</td>
                                            <td>{{ $item->tahun }}</td>
                                            <td>{{ $item->tingkat_prestasi }}</td>
                                            <td>{{ $item->status_validasi }}</td>
                                            <td class="text-center" style="width: 15%;">
                                                <form onsubmit="return confirm('Anda yakin ingin menghapus data ini?');" action="{{ route('admin.prestasi.destroy', $item->id_prestasi) }}" method="POST">
                                                    <a href="{{ route('admin.prestasi.edit', $item->id_prestasi) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center"><div class="alert alert-warning">Data belum tersedia.</div></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {!! $prestasi->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif
</body>
</html>
