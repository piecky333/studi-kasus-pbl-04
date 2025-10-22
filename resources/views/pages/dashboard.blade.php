{{-- 
    Catatan Penting:
    Layout diubah dari 'layouts.app' (Tailwind) ke 'layouts.admin' (Bootstrap 4)
    agar sesuai dengan kelas CSS yang Anda gunakan (card, row, col-xl-3, dll.)
--}}
@extends('layouts.admin')

@section('content')

    {{-- Alert untuk menampilkan pesan sukses setelah update profile --}}
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> Profil Anda berhasil diperbarui.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Data User -->
        {{-- Koreksi: col-md-1 diubah menjadi col-md-6 agar rapi di layar sedang --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total User </div>
                            {{-- Dinamis: Menggunakan $totalUser --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUser ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumlah Anggota (Pengurus) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pengurus</div>
                            {{-- Dinamis: Menggunakan $totalAnggota --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPengurus ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Berita -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Berita
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBerita ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-newspaper fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan (Pengaduan) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Laporan (Pengaduan)</div>
                            {{-- Dinamis: Menggunakan $totalLaporan --}}
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLaporan ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-envelope-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

