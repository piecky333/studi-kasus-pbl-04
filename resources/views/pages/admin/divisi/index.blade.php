@extends('layouts.admin')

@section('title', 'Manajemen Divisi')

@section('content')
<div class="container-fluid px-4 mt-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                Manajemen Divisi
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola struktur divisi dalam organisasi.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.divisi.create') }}" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 border border-transparent rounded-md shadow-sm text-xs lg:text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Tambah Divisi
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="max-w-4xl bg-white shadow-[4px_4px_10px_rgba(0,0,0,0.1)] rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0d2149] text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider w-20">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama Divisi</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($divisi as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row->nama_divisi }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('admin.divisi.edit', $row) }}" class="text-amber-600 hover:text-amber-900"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.divisi.destroy', $row) }}" method="POST" onsubmit="return confirm('Yakin hapus divisi ini? Menghapus divisi dapat berdampak pada data pengurus terkait.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-gray-500">Belum ada data divisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
