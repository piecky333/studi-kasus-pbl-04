@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
<div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
<header class="mb-6 border-b pb-4">
<h1 class="text-2xl font-bold text-gray-900 leading-tight">Manajemen Data Alternatif</h1>
<p class="mt-1 text-sm text-gray-500">Keputusan: <span class="font-medium text-blue-600">{{ $keputusan->nama_keputusan }}</span> | Kelola daftar alternatif yang akan dinilai.</p>
</header>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="mt-6">
        <div class="flex justify-between items-center bg-gray-50 p-4 border rounded-t-lg">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-users-cog mr-2"></i> Daftar Data Alternatif
            </h2>
            {{-- Tombol Tambah Data --}}
            <a href="{{ route('admin.spk.manage.alternatif.create', $keputusan->id_keputusan) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                <i class="fas fa-plus mr-1"></i> Tambah Data
            </a>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-b-lg">
            @if($alternatifData->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    Tidak ada data alternatif yang tersedia untuk keputusan ini.
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-4/12">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-3/12">NISN / ID</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($alternatifData as $index => $alternatif)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $alternatif->nama_alternatif }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $alternatif->keterangan ?? '-' }}</td> {{-- Menggunakan 'keterangan' sebagai NISN --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                <a href="{{ route('admin.spk.manage.alternatif.edit', [$keputusan->id_keputusan, $alternatif->id_alternatif]) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.spk.manage.alternatif.destroy', [$keputusan->id_keputusan, $alternatif->id_alternatif]) }}" 
                                      method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alternatif ini? Ini akan menghapus semua penilaian dan hasil terkait!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>


</div>
@endsection