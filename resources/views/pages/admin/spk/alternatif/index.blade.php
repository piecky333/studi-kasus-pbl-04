@extends('pages.admin.spk.detail_base', ['currentTab' => 'alternatif', 'idKeputusan' => $idKeputusan, 'keputusan' => $keputusan])

@section('detail_content')

    <div class="bg-white rounded-lg">

        {{-- ALERT STATUS --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm font-semibold"
                role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm font-semibold"
                role="alert">
                {{ session('error') }}
            </div>
        @endif

        <header class="flex justify-between items-center mb-6 border-b pb-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Daftar Alternatif (Calon Objek SPK)</h3>
                <p class="text-sm text-gray-600">
                    Kelola data objek yang akan dinilai dan diranking untuk keputusan
                    <span
                        class="font-semibold text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span>.
                </p>
            </div>

            <div class="flex space-x-3">
                {{-- Tombol Tambah Alternatif Baru --}}
                <a href="{{ route('admin.spk.alternatif.create', $idKeputusan) }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                    <i class="fas fa-plus mr-1"></i> Tambah Alternatif
                </a>

                {{-- Tombol untuk langsung menuju Matriks Penilaian ($Xij$) --}}
                <a href="{{ route('admin.spk.alternatif.penilaian.index', $idKeputusan) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                    <i class="fas fa-table mr-1"></i> Atur Matriks Penilaian
                </a>
            </div>
        </header>

        {{-- Tabel Daftar Alternatif --}}
        <div class="overflow-x-auto border border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">
                            Nama Alternatif</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                            Nilai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($alternatifList as $alternatif)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $alternatif->nama_alternatif }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Perlu Diperiksa
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <div class="flex justify-center space-x-1">
                                    <form
                                    action="{{ route('admin.spk.alternatif.destroy', ['idKeputusan' => $idKeputusan, 'idAlternatif' => $alternatif->id_alternatif]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                    onclick="return confirm('Menghapus alternatif ini akan menghapus semua penilaian terkait. Yakin?')"
                                    class="text-xs text-red-600 hover:text-red-900 border border-red-600 hover:border-red-900 px-2 py-1 rounded transition duration-150">
                                    Hapus
                                </button>
                            </form>
                            <a href="{{ route('admin.spk.alternatif.edit', [$idKeputusan, $alternatif->id_alternatif]) }}"
                                class="text-xs text-indigo-600 hover:text-indigo-900 border border-indigo-600 hover:border-indigo-900 px-2 py-1 rounded transition duration-150">
                                Edit
                            </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center bg-gray-50">
                                Belum ada Alternatif yang ditambahkan. Silakan tambahkan Alternatif baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection