@extends('layouts.admin')

@section('title', 'Detail Mahasiswa')

@section('content')
<div class="container-fluid px-4 mt-6 mb-10">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h4 class="text-2xl font-bold text-gray-800">Detail Mahasiswa</h4>
            <p class="text-sm text-gray-500 mt-1">Informasi lengkap data mahasiswa.</p>
        </div>
        <a href="{{ route('admin.prestasi.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Profil Singkat --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden p-6 text-center">
                <div class="h-24 w-24 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-3xl mx-auto mb-4 border-4 border-indigo-50">
                    {{ substr($mahasiswa->nama, 0, 1) }}
                </div>
                <h5 class="text-xl font-bold text-gray-800">{{ $mahasiswa->nama }}</h5>
                <p class="text-sm text-gray-500 mb-4">{{ $mahasiswa->nim }}</p>
                
                <div class="flex justify-center gap-2">
                    <a href="{{ route('admin.datamahasiswa.edit', $mahasiswa->id_mahasiswa) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.datamahasiswa.destroy', $mahasiswa->id_mahasiswa) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>

            {{-- Detail Info Card --}}
            <div class="bg-white rounded-xl shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden mt-6 p-6">
                <h6 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Akademik</h6>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Email</p>
                        <p class="text-gray-800 font-medium">{{ $mahasiswa->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Semester</p>
                        <span class="inline-flex items-center py-0.5 text-xs font-medium text-blue-800">
                            Semester {{ $mahasiswa->semester }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Terdaftar Sejak</p>
                        <p class="text-gray-800 text-sm">{{ $mahasiswa->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Prestasi & Sanksi --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Prestasi --}}
            <div class="bg-white rounded-xl shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden p-6">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                    <h6 class="font-bold text-gray-800">Riwayat Prestasi</h6>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $mahasiswa->prestasi->count() }} Prestasi</span>
                </div>
                
                <div class="overflow-x-auto">
                    @if($mahasiswa->prestasi->count() > 0)
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">Kegiatan</th>
                                    <th class="px-6 py-3 font-semibold text-center">Tingkat</th>
                                    <th class="px-6 py-3 font-semibold text-center">Tahun</th>
                                    <th class="px-6 py-3 font-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($mahasiswa->prestasi as $prestasi)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 font-medium text-gray-800">{{ $prestasi->nama_kegiatan }}</td>
                                        <td class="px-6 py-3 text-center text-sm text-gray-600">{{ $prestasi->tingkat_prestasi }}</td>
                                        <td class="px-6 py-3 text-center text-sm text-gray-600">{{ $prestasi->tahun }}</td>
                                        <td class="px-6 py-3 text-center">
                                            @if($prestasi->status_validasi == 'disetujui')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                            @elseif($prestasi->status_validasi == 'ditolak')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Menunggu</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-trophy text-gray-300 text-3xl mb-2"></i>
                            <p class="text-sm">Belum ada data prestasi.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sanksi --}}
            <div class="bg-white rounded-xl shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden p-6">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                    <h6 class="font-bold text-gray-800">Riwayat Sanksi</h6>
                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $mahasiswa->sanksi->count() }} Sanksi</span>
                </div>
                
                <div class="overflow-x-auto">
                    @if($mahasiswa->sanksi->count() > 0)
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">Jenis Sanksi</th>
                                    <th class="px-6 py-3 font-semibold text-center">Tanggal</th>
                                    <th class="px-6 py-3 font-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($mahasiswa->sanksi as $sanksi)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 font-medium text-gray-800">{{ $sanksi->jenis_sanksi }}</td>
                                        <td class="px-6 py-3 text-center text-sm text-gray-600">{{ \Carbon\Carbon::parse($sanksi->tanggal_sanksi)->format('d M Y') }}</td>
                                        <td class="px-6 py-3 text-center">
                                            @if($sanksi->status_sanksi == 'aktif')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Aktif</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <i class="fas fa-gavel text-gray-300 text-3xl mb-2"></i>
                            <p class="text-sm">Belum ada data sanksi.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection