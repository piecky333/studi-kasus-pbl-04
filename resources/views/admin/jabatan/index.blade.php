<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Anggota & Jabatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { --primary-color: #0d6efd; --light-gray: #f8f9fa; }
        body { background-color: var(--light-gray); }
        .card { border-radius: 0.75rem; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border: none; }
        .card-header { background-color: var(--primary-color); color: white; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-users-cog"></i> KELOLA JABATAN ANGGOTA</span>
                <a href="{{ route('jabatan.create') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-plus-circle"></i> TAMBAH ANGGOTA
                </a>
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>NAMA ANGGOTA</th>
                            <th>JABATAN</th>
                            <th>DIVISI</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggota as $item)
                            <tr>
                                <td>{{ $item->nama_anggota }}</td>
                                <td>{{ $item->jabatan_struktural }}</td>
                                <td>{{ $item->divisi }}</td>
                                <td class="text-center">
                                    <form onsubmit="return confirm('Yakin ingin menghapus data ini?');" action="{{ route('jabatan.destroy', $item->id) }}" method="POST">
                                        <a href="{{ route('jabatan.edit', $item->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center alert alert-warning">Data Anggota belum tersedia.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $anggota->links() }}
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'BERHASIL!', text: '{{ session('success') }}', showConfirmButton: false, timer: 2000 });
        @endif
    </script>
</body>
</html>
