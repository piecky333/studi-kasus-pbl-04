@extends('pages.admin.spk.detail_base', ['currentTab' => 'kriteria', 'idKeputusan' => $idKeputusan, 'keputusan' => $keputusan])

@section('detail_content')

{{-- Konten Utama Kriteria --}}
<div class="bg-white rounded-lg">
    
    {{-- ALERT STATUS --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <header class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h3 class="text-xl font-bold text-gray-800">Daftar Kriteria</h3>
            <p class="text-sm text-gray-600">
                Kelola faktor-faktor penilaian untuk keputusan 
                <span class="font-semibold text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span>
            </p>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('admin.spk.kriteria.perbandingan.index', $keputusan->id_keputusan) }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                Bobot Preferensi AHP
            </a>
            <a href="{{ route('admin.spk.kriteria.create', $keputusan->id_keputusan) }}"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                <i class="fas fa-plus mr-1"></i> Tambah Kriteria
            </a>
        </div>
    </header>

    {{-- Tabel Daftar Kriteria --}}
    <div class="overflow-x-auto border border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot (Wj)</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{-- Asumsi $kriteriaData tersedia --}}
                @forelse($kriteriaData as $kriteria)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $kriteria->kode_kriteria }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $kriteria->nama_kriteria }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $kriteria->jenis_kriteria == 'Benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $kriteria->jenis_kriteria }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                            {{ number_format($kriteria->bobot_kriteria, 4) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex justify-center space-x-1">
                                <a href="{{ route('admin.spk.kriteria.subkriteria.index', [$idKeputusan, $kriteria->id_kriteria]) }}"
                                    class="text-xs text-blue-600 hover:text-blue-900 border border-blue-600 hover:border-blue-900 px-2 py-1 rounded transition duration-150">
                                    Atur Sub
                                </a>
                                <a href="{{ route('admin.spk.kriteria.edit', [$idKeputusan, $kriteria->id_kriteria]) }}"
                                    class="text-xs text-indigo-600 hover:text-indigo-900 border border-indigo-600 hover:border-indigo-900 px-2 py-1 rounded transition duration-150">
                                    Edit
                                </a>
                                <form action="{{ route('admin.spk.kriteria.destroy', [$idKeputusan, $kriteria->id_kriteria]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus kriteria ini?')"
                                        class="text-xs text-red-600 hover:text-red-900 border border-red-600 hover:border-red-900 px-2 py-1 rounded transition duration-150">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center bg-gray-50">
                            Belum ada kriteria yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection