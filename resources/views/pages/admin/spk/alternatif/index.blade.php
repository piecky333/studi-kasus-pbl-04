@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg p-6">
        <header class="mb-6 border-b pb-4 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
                <p class="text-sm text-gray-600">
                    Untuk Keputusan: <span class="font-semibold text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span>
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('kriteria.index', $keputusan->id_keputusan) }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                    <i class="fas fa-list-ol mr-1"></i> Kelola Kriteria
                </a>
                <a href="{{ route('alternatif.create', $keputusan->id_keputusan) }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                    <i class="fas fa-plus mr-1"></i> Tambah Alternatif
                </a>
            </div>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">ID Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-4/12">Nama Alternatif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-3/12">Keterangan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($alternatifData as $index => $alternatif)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $alternatif->id_mahasiswa ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $alternatif->nama_alternatif }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $alternatif->keterangan ?? '-' }}</td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                
                                {{-- Tombol Edit --}}
                                <a href="{{ route('alternatif.edit', [$keputusan->id_keputusan, $alternatif->id_alternatif]) }}"
                                    class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('alternatif.destroy', [$keputusan->id_keputusan, $alternatif->id_alternatif]) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('APAKAH ANDA YAKIN? Menghapus alternatif ini akan menghapus semua penilaian terkait!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm font-medium text-gray-500 bg-gray-50">
                                Belum ada Alternatif (Mahasiswa) yang didaftarkan untuk keputusan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection