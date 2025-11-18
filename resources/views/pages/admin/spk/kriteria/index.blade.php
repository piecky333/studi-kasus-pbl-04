@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4 flex justify-between items-center">
    
<div>
<h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">Keputusan: {{ $keputusan->nama_keputusan }} | Metode: {{ $keputusan->metode_yang_digunakan }}</p>
</div>

        {{-- Tombol AHP dan Tambah Data --}}
        <div class="flex space-x-3">
            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                Bobot Preferensi AHP
            </a>
            <a href="{{ route('admin.spk.manage.kriteria.create', $keputusan->id_keputusan) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                <i class="fas fa-plus mr-1"></i> Tambah Data
            </a>
        </div>
    </header>

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Kriteria Utama --}}
    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <h3 class="text-lg font-semibold text-gray-700 p-4 border-b bg-gray-50">Daftar Data Kriteria</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">Kode Kriteria</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-3/12">Nama Kriteria</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">Jenis</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">Bobot</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Cara Penilaian</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($kriteriaData as $index => $kriteria)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $kriteria->kode_kriteria }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $kriteria->nama_kriteria }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ strtolower($kriteria->jenis_kriteria) == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $kriteria->jenis_kriteria }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-indigo-600 font-medium">{{ number_format($kriteria->bobot_kriteria, 4) }}</td>
                    
                    {{-- Logika Cara Penilaian --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600">
                        @if(isset($kriteria->subKriteria) && $kriteria->subKriteria->count() > 0)
                            Pilihan Sub Kriteria
                        @else
                            Input Langsung
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                        {{-- Tombol Edit --}}
                        <a href="{{ route('admin.spk.manage.kriteria.edit', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                        
                        {{-- Tombol Hapus --}}
                        <form action="{{ route('admin.spk.manage.kriteria.destroy', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}" 
                              method="POST" 
                              class="inline" 
                              onsubmit="return confirm('APAKAH ANDA YAKIN? Menghapus kriteria ini akan menghapus semua Penilaian terkait di semua alternatif!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
                        </form>
                        
                        {{-- Tombol Atur Sub Kriteria (Link ke View Sub Kriteria) --}}
                        @if(isset($kriteria->subKriteria) && $kriteria->subKriteria->count() > 0)
                            <a href="#" class="text-indigo-600 hover:text-indigo-800 ml-2 text-xs">Atur Sub</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm font-medium text-gray-500 bg-gray-50">
                        Belum ada Kriteria yang ditambahkan untuk keputusan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 flex justify-end">
         <a href="{{ route('admin.spk.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Keputusan
        </a>
    </div>
</div>


</div>
@endsection