@extends('layouts.admin')

@section('content')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Alert Status --}}
        @if (session('status') === 'profile-updated')
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>Profil Anda berhasil diperbarui.</p>
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Admin</h1>
                <p class="mt-1 text-sm text-gray-500">Ringkasan statistik dan aktivitas terbaru sistem.</p>
            </div>
             <!-- <div class="mt-4 sm:mt-0">
                <span class="inline-flex rounded-md shadow-sm">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-download mr-2 text-gray-400"></i> Generate Report
                    </button>
                </span>
            </div>  -->
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            
            <!-- Card: Total User -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-500 hover:shadow-md transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="bi bi-person-fill text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total User</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $totalUser ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Total Pengurus -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500 hover:shadow-md transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="bi bi-people-fill text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Pengurus</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $totalPengurus ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Total Berita -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-cyan-500 hover:shadow-md transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-cyan-100 rounded-md p-3">
                            <i class="bi bi-newspaper text-cyan-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Berita</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $totalBerita ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Total Laporan -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-yellow-500 hover:shadow-md transition-shadow duration-300">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                            <i class="bi bi-envelope-fill text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Pengaduan</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $totalLaporan ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Line Chart -->
            <div class="bg-white shadow rounded-lg lg:col-span-2">
                <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Tren Pengaduan (Tahun Ini)
                    </h3>
                </div>
                <div class="p-5">
                    <div class="relative h-80 w-full">
                        <canvas id="laporanChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-5 py-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Status Laporan
                    </h3>
                </div>
                <div class="p-5">
                    <div class="relative h-64 w-full flex justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">Distribusi status pengaduan yang masuk.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // --- Line Chart Configuration ---
        if (typeof @json($chartLabels) !== 'undefined' && typeof @json($chartData) !== 'undefined') {
            const ctx = document.getElementById('laporanChart');
            if (ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Jumlah Pengaduan',
                            data: @json($chartData),
                            fill: true,
                            backgroundColor: 'rgba(79, 70, 229, 0.1)', // Indigo-500 with opacity
                            borderColor: '#4f46e5', // Indigo-600
                            borderWidth: 2,
                            tension: 0.3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#4f46e5',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                titleColor: '#f3f4f6',
                                bodyColor: '#f3f4f6',
                                padding: 10,
                                cornerRadius: 4,
                                displayColors: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f3f4f6' },
                                ticks: { precision: 0, color: '#6b7280' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: '#6b7280' }
                            }
                        }
                    }
                });
            }
        }

        // --- Pie Chart Configuration ---
        if (typeof @json($pieLabels) !== 'undefined' && typeof @json($pieData) !== 'undefined') {
            const ctxPie = document.getElementById('statusChart');
            if (ctxPie) {
                new Chart(ctxPie.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($pieLabels),
                        datasets: [{
                            data: @json($pieData),
                            backgroundColor: [
                                '#fbbf24', // Amber-400 (Pending/Terkirim)
                                '#3b82f6', // Blue-500 (Diproses)
                                '#10b981', // Emerald-500 (Selesai)
                                '#ef4444', // Red-500 (Ditolak)
                                '#6366f1'  // Indigo-500 (Other)
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    color: '#4b5563'
                                }
                            }
                        },
                        cutout: '70%', // Thinner doughnut
                    }
                });
            }
        }
    </script>

@endsection