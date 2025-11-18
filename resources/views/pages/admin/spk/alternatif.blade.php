@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
<p class="mt-1 text-sm text-gray-500">Keputusan: {{ $keputusan->nama_keputusan }} | Daftar kandidat/pilihan yang akan dievaluasi.</p>
</header>

    <div class="flex justify-between items-center mb-4">
        {{-- Tombol untuk menambah alternatif --}}
        <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
            <i class="fas fa-plus mr-1"></i> Tambah Alternatif Baru
        </a>
    </div>

    {{-- Tabel Alternatif --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4/12">Nama Alternatif</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4/12">Keterangan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($alternatifData as $alternatif)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $alternatif->id_alternatif }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $alternatif->nama_alternatif }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $alternatif->keterangan ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                        <form action="#" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif ini? Menghapus alternatif juga akan menghapus semua penilaian terkait.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-sm font-medium text-gray-500 bg-gray-50">
                        Belum ada Alternatif yang ditambahkan untuk keputusan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


</div>
@endsection