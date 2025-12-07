@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="min-h-screen bg-gray-50/50 pb-8 pt-8">
        
        {{-- Welcome Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 to-gray-800 p-8 shadow-xl mb-8">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">Dashboard Administrator</h1>
                    <p class="text-gray-300 text-lg opacity-90">Pantau aktivitas sistem dan kelola data master.</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 shadow-inner">
                        <div class="flex items-center gap-3 text-white">
                            <div class="p-2 bg-white/10 rounded-lg">
                                <i class="bi bi-clock-history text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Waktu Server</p>
                                <p class="font-semibold font-mono">{{ now()->setTimezone('Asia/Makassar')->format('H:i') }} WITA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Decorative Elements --}}
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-blue-500/10 rounded-full blur-2xl"></div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Total Mahasiswa --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="bi bi-mortarboard-fill text-2xl"></i>
                    </div>
                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">Mahasiswa</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalUser }}</h3>
                <p class="text-gray-500 text-sm">Total Mahasiswa</p>
            </div>

            {{-- Total Pengurus --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <i class="bi bi-people-fill text-2xl"></i>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2.5 py-1 rounded-full">Pengurus</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalPengurus }}</h3>
                <p class="text-gray-500 text-sm">Total Pengurus</p>
            </div>

            {{-- Total Berita --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-cyan-50 text-cyan-600 rounded-xl group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                        <i class="bi bi-newspaper text-2xl"></i>
                    </div>
                    <span class="text-xs font-medium text-cyan-600 bg-cyan-50 px-2.5 py-1 rounded-full">Berita</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalBerita }}</h3>
                <p class="text-gray-500 text-sm">Total Berita</p>
            </div>

            {{-- Total Pengaduan --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                        <i class="bi bi-envelope-fill text-2xl"></i>
                    </div>
                    <span class="text-xs font-medium text-yellow-600 bg-yellow-50 px-2.5 py-1 rounded-full">Pengaduan</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalLaporan }}</h3>
                <p class="text-gray-500 text-sm">Total Pengaduan</p>
            </div>
        </div>

        {{-- Line Chart (Full Width) --}}
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tren Pengaduan (Tahun Ini)</h3>
            <div class="relative h-80 w-full">
                <canvas id="laporanChart"></canvas>
            </div>
        </div>

        {{-- Middle Section: Pie Chart & Recent Pengaduan --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            {{-- Pie Chart --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Status Laporan</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">Distribusi status pengaduan yang masuk.</p>
                </div>
            </div>

            {{-- Recent Pengaduan --}}
            <div class="lg:col-span-2 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h5 class="font-bold text-gray-800 flex items-center">
                        <i class="bi bi-clock-history text-blue-500 mr-2"></i> Pengaduan Terbaru
                    </h5>
                    <a href="{{ route('admin.pengaduan.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua <i class="bi bi-arrow-right ml-1"></i></a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Pelapor</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentPengaduan as $pengaduan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $pengaduan->user->nama ?? 'Anonim' }}</td>
                                    <td class="px-6 py-4">
                                        @if($pengaduan->status == 'Terkirim')
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">Terkirim</span>
                                        @elseif($pengaduan->status == 'Diproses')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-400">Diproses</span>
                                        @elseif($pengaduan->status == 'Selesai')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Selesai</span>
                                        @elseif($pengaduan->status == 'Ditolak')
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.pengaduan.show', $pengaduan->id_pengaduan) }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                        <i class="bi bi-inbox text-2xl mb-2 block"></i>
                                        Belum ada pengaduan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- News Verification Section (Full Width) --}}
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h5 class="font-bold text-gray-800 flex items-center">
                        <i class="bi bi-patch-check text-orange-500 mr-2"></i> Berita yang perlu verifikasi
                    </h5>
                    <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2 py-1 rounded-full">{{ $beritaPending->count() }} Pending</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Judul</th>
                                <th class="px-6 py-3">Pengupload</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($beritaPending as $berita)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ Str::limit($berita->judul_berita, 30) }}</td>
                                    <td class="px-6 py-4">{{ $berita->user->nama ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.berita.edit', $berita->id_berita) }}" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Review</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-400">
                                        <i class="bi bi-check-circle text-2xl mb-2 block"></i>
                                        Tidak ada berita pending
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderColor: '#4f46e5',
                            borderWidth: 2,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#4f46e5',
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#111827',
                                titleColor: '#f9fafb',
                                bodyColor: '#f9fafb',
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f3f4f6', borderDash: [2, 2] },
                                ticks: { precision: 0, color: '#6b7280', font: { family: "'Inter', sans-serif" } },
                                border: { display: false }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: '#6b7280', font: { family: "'Inter', sans-serif" } },
                                border: { display: false }
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
                                '#fbbf24', // Amber-400
                                '#3b82f6', // Blue-500
                                '#10b981', // Emerald-500
                                '#ef4444', // Red-500
                                '#6366f1'  // Indigo-500
                            ],
                            borderWidth: 0,
                            hoverOffset: 10
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
                                    color: '#4b5563',
                                    font: { family: "'Inter', sans-serif", size: 12 }
                                }
                            }
                        },
                        cutout: '75%',
                    }
                });
            }
        }
    </script>
@endsection