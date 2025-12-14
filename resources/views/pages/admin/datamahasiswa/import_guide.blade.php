@extends('layouts.admin')

@section('title', 'Panduan Import Data Mahasiswa')

@section('content')
<div class="container-fluid px-4 mt-6 pb-4">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Panduan Import Excel</h2>
                <p class="mt-1 text-sm text-gray-500">Format dan aturan kolom untuk import data mahasiswa, prestasi, dan sanksi.</p>
            </div>
            <a href="{{ route('admin.datamahasiswa.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Format Utama -->
        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-indigo-500">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-id-card mr-2 text-indigo-500"></i> Wajib & Akademik
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Kolom (Header)</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Wajib?</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2 font-mono text-indigo-700">NIM</td>
                            <td class="px-4 py-2"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 text-xs font-bold">YA</span></td>
                            <td class="px-4 py-2">Identitas Utama.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono text-indigo-700">Nama</td>
                            <td class="px-4 py-2"><span class="px-2 py-0.5 rounded bg-red-100 text-red-800 text-xs font-bold">YA</span></td>
                            <td class="px-4 py-2">Nama Lengkap.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono">Email</td>
                            <td class="px-4 py-2"><span class="px-2 py-0.5 rounded bg-green-100 text-green-800 text-xs">Opsional</span></td>
                            <td class="px-4 py-2">Jika kosong, auto-generate dari nama.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono">Semester</td>
                            <td class="px-4 py-2"><span class="px-2 py-0.5 rounded bg-green-100 text-green-800 text-xs">Opsional</span></td>
                            <td class="px-4 py-2">Default: 1</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono">IPK</td>
                            <td class="px-4 py-2"><span class="px-2 py-0.5 rounded bg-green-100 text-green-800 text-xs">Opsional</span></td>
                            <td class="px-4 py-2">0.00 - 4.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Aturan Umum -->
        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-gray-500">
             <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-gray-500"></i> Catatan Penting
            </h3>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                    <span><strong>Multi-Baris:</strong> Jika mahasiswa memiliki lebih dari 1 Prestasi/Sanksi, buat baris baru dengan <strong>NIM & Nama yang sama</strong>. Sistem akan menggabungkannya.</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                    <span><strong>Header Fleksibel:</strong> Besar kecil huruf pada header tidak masalah (misal: "NIM", "nim", "No Induk").</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 mr-2"></i>
                    <span><strong>File Upload:</strong> Excel tidak mendukung upload file fisik (PDF/Gambar). Anda harus mengupload bukti prestasi/sanksi secara manual melalui menu Edit.</span>
                </li>
            </ul>
        </div>

        <!-- Prestasi -->
        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-blue-500">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-trophy mr-2 text-blue-500"></i> Format Prestasi
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Header</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Valid Values</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2 font-mono text-blue-700">Prestasi / Kegiatan</td>
                            <td class="px-4 py-2">Judul Lomba / Nama Kegiatan</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono text-blue-700">Jenis</td>
                            <td class="px-4 py-2">Akademik, Non-Akademik</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono text-blue-700">Tingkat</td>
                            <td class="px-4 py-2">Nasional, Provinsi, Kabupaten, Internal</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono text-blue-700">Tahun</td>
                            <td class="px-4 py-2">Contoh: 2024</td>
                        </tr>
                         <tr>
                            <td class="px-4 py-2 font-mono text-blue-700">Deskripsi</td>
                            <td class="px-4 py-2">Keterangan tambahan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sanksi -->
        <div class="bg-white shadow rounded-lg p-6 border-t-4 border-red-500">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-gavel mr-2 text-red-500"></i> Format Sanksi
            </h3>
             <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Header</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Valid Values</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-4 py-2 font-mono text-red-700">Jenis Sanksi</td>
                            <td class="px-4 py-2">Ringan, Sedang, Berat</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono text-red-700">Hukuman</td>
                            <td class="px-4 py-2">Teguran Lisan, Skorsing, dll</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono text-red-700">Tanggal</td>
                            <td class="px-4 py-2">Format: YYYY-MM-DD</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-mono text-red-700">Keterangan</td>
                            <td class="px-4 py-2">Detail pelanggaran</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
