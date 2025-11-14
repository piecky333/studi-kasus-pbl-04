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

    <!-- Content Row (STATISTIK CARDS) -->
    <div class="row">

        <!-- Data User -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total User </div>
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
    <!-- AKHIR DARI CONTENT ROW (STATISTIK CARDS) -->


    <!-- Content Row Baru untuk KEDUA Chart -->
    <div class="row">

        <!-- Area Chart (LINE CHART ANDA) -->
        {{-- DIUBAH: lebarnya jadi 8 kolom --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Laporan Pengaduan per Bulan (Tahun Ini)</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="laporanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart (BARU) -->
        {{-- TAMBAHAN: Pie chart 4 kolom --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Status Laporan</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    {{-- Wadah untuk Pie chart --}}
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <!-- Akhir Content Row Baru untuk Chart -->


    <!-- SCRIPT UNTUK CHART.JS (Blok B) -->
    <!-- 1. Memuat library Chart.js dari CDN (Cukup satu kali) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- 2. Script untuk menggambar KEDUA chart -->
    <script>
        // SCRIPT UNTUK LINE CHART (dari kode Anda)
        if (typeof @json($chartLabels) !== 'undefined' && typeof @json($chartData) !== 'undefined') {
            
            // Ambil data 'labels' dan 'data' yang kita kirim dari Controller
            const chartLabels = @json($chartLabels);
            const chartData = @json($chartData);

            // Cari elemen <canvas> yang kita buat tadi
            const ctx = document.getElementById('laporanChart');

            if (ctx) {
                // Buat chart baru
                new Chart(ctx.getContext('2d'), {
                    type: 'line', // Tipe chart: 'line' (garis), 'bar' (batang), 'pie', dll.
                    data: {
                        labels: chartLabels, // Label sumbu X (Jan, Feb, Mar, ...)
                        datasets: [{
                            label: 'Jumlah Pengaduan',
                            data: chartData, // Data sumbu Y (angka laporannya)
                            fill: false,
                            borderColor: '#4e73df', // Warna garis (Biru Primary SB Admin)
                            tension: 0.1
                        }]
                    },
                    options: {
                        maintainAspectRatio: false, // Agar chart bisa responsif
                        scales: {
                            y: {
                                beginAtZero: true, // Mulai sumbu Y dari 0
                                ticks: {
                                    // Memastikan angka di sumbu Y adalah bilangan bulat
                                    precision: 0 
                                }
                            }
                        }
                    }
                });
            }
        }

        // SCRIPT UNTUK PIE CHART (Baru ditambahkan)
        if (typeof @json($pieLabels) !== 'undefined' && typeof @json($pieData) !== 'undefined') {
            
            const pieLabels = @json($pieLabels);
            const pieData = @json($pieData);
            const ctxPie = document.getElementById('statusChart'); // ID canvas baru

            if (ctxPie) {
                new Chart(ctxPie.getContext('2d'), {
                    type: 'doughnut', // Tipe chart: doughnut atau pie
                    data: {
                        labels: pieLabels, // Label (Pending, Selesai, dll)
                        datasets: [{
                            data: pieData, // Angka datanya
                            // Warna-warna ini adalah standar SB Admin
                            backgroundColor: ['#f6c23e', '#1cc88a', '#36b9cc', '#4e73df', '#e74a3b'],
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom', // Menampilkan legenda di bawah chart
                            }
                        }
                    }
                });
            }
        }
    </script>
    <!-- AKHIR DARI SCRIPT CHART.JS -->

@endsection