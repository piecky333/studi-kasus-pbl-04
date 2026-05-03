@extends('layouts.admin')

@section('title', 'Manajemen Pengurus')

@section('content')
<div class="container-fluid px-4 mt-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                Manajemen Pengurus
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola daftar akun pengurus dan strukturnya.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.pengurus.create') }}" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 border border-transparent rounded-md shadow-sm text-xs lg:text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Tambah Pengurus
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mb-6 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route('admin.pengurus.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-4">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Nama / Username</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3">
                </div>
                <div class="md:col-span-4">
                    <label for="divisi" class="block text-sm font-medium text-gray-700 mb-1">Filter Divisi</label>
                    <select name="divisi" id="divisi" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="this.form.submit()">
                        <option value="">Semua Divisi</option>
                        @foreach($divisis as $div)
                            <option value="{{ $div->id_divisi }}" {{ request('divisi') == $div->id_divisi ? 'selected' : '' }}>{{ $div->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex space-x-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Filter
                    </button>
                    <a href="{{ route('admin.pengurus.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white shadow-[4px_4px_10px_rgba(0,0,0,0.1)] rounded-lg overflow-hidden border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0d2149] text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Divisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jabatan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengurus as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $row->user->nama }}</div>
                        <div class="text-xs text-gray-500">{{ $row->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->user->username }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->divisi->nama_divisi }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->jabatan->nama_jabatan }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.pengurus.edit', $row) }}" class="text-amber-600 hover:text-amber-900"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.pengurus.destroy', $row) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $pengurus->links() }}
        </div>
    </div>
</div>
@endsection
