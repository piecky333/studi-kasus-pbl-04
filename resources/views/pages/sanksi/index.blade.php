@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-t-lg shadow-md text-white">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Manajemen Sanksi</h2>
                <a href="{{ route('admin.sanksi.create') }}"
                    class="bg-white text-indigo-700 font-semibold px-4 py-2 rounded-md hover:bg-indigo-100 transition">
                    + Tambah Sanksi
                </a>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-b-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">No</th>
                            <th class="px-6 py-3 text-left font-semibold">Nama Mahasiswa</th>
                            <th class="px-6 py-3 text-left font-semibold">Jenis Sanksi</th>
                            <th class="px-6 py-3 text-left font-semibold">Keterangan</th>
                            <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($sanksi as $index => $item)
                            <tr class="hover:bg-indigo-50 transition">
                                <td class="px-6 py-3">{{ $index + 1 }}</td>
                                <td class="px-6 py-3">{{ $item->mahasiswa->nama ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full font-semibold
                                        @if($item->jenis_sanksi == 'Ringan') bg-green-100 text-green-700
                                        @elseif($item->jenis_sanksi == 'Sedang') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ $item->jenis_sanksi }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">{{ $item->keterangan ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    {{ $item->tanggal_sanksi ? \Carbon\Carbon::parse($item->tanggal_sanksi)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-3 text-center space-x-2">
                                    <a href="{{ route('admin.sanksi.edit', $item->id_sanksi) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-md hover:bg-indigo-200 transition">
                                        <i class="bi bi-pencil-fill mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.sanksi.destroy', $item->id_sanksi) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition">
                                            <i class="bi bi-trash3-fill mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                    Tidak ada data sanksi untuk saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
