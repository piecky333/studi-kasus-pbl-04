@extends('layouts.admin')

@section('content')
        @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm mt-6" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm mt-6" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Header Halaman: Tata letak fleksibel (kolom di mobile, baris di desktop) --}}
        <header class="flex flex-col md:flex-row justify-between items-center mb-6 border-b pb-4 gap-4 mt-8">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Daftar Kriteria</h3>
                <p class="text-sm text-gray-600">
                    Kelola faktor-faktor penilaian untuk keputusan 
                    <span class="font-semibold text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span>
                </p>
            </div>

            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-3 w-full md:w-auto">
                <a href="{{ route('admin.spk.kriteria.perbandingan.index', $keputusan->id_keputusan) }}"
                    class="w-full md:w-auto text-center bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                    Bobot Preferensi AHP
                </a>
                <a href="{{ route('admin.spk.kriteria.create', $keputusan->id_keputusan) }}"
                    class="w-full md:w-auto text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                    <i class="fas fa-plus mr-1"></i> Tambah Kriteria
                </a>
            </div>
        </header>

        {{-- Tampilan Tabel Responsif --}}
        <div class="overflow-x-auto border border-gray-200 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                {{-- Header Tabel: Disembunyikan pada tampilan mobile --}}
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot (Wj)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 block md:table-row-group">
                    @forelse($kriteriaData as $kriteria)
                        {{-- Baris Tabel: Berubah menjadi tampilan Card fleksibel pada mobile --}}
                        <tr class="hover:bg-gray-50 block md:table-row border-b md:border-b-0 mb-4 md:mb-0 pb-4 md:pb-0">
                            
                            {{-- Kolom Nomor Urut --}}
                            <td class="px-6 py-2 md:py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex justify-between md:table-cell">
                                <span class="md:hidden font-bold text-gray-500">No:</span>
                                {{ $loop->iteration }}
                            </td>

                            {{-- Kolom Kode Kriteria --}}
                            <td class="px-6 py-2 md:py-4 whitespace-nowrap text-sm text-gray-600 flex justify-between md:table-cell">
                                <span class="md:hidden font-bold text-gray-500">Kode:</span>
                                {{ $kriteria->kode_kriteria }}
                            </td>

                            {{-- Kolom Nama Kriteria --}}
                            <td class="px-6 py-2 md:py-4 whitespace-nowrap text-sm text-gray-600 flex justify-between md:table-cell">
                                <span class="md:hidden font-bold text-gray-500">Nama:</span>
                                {{ $kriteria->nama_kriteria }}
                            </td>

                            {{-- Kolom Jenis Kriteria --}}
                            <td class="px-6 py-2 md:py-4 whitespace-nowrap text-sm font-medium flex justify-between md:table-cell items-center">
                                <span class="md:hidden font-bold text-gray-500">Jenis:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $kriteria->jenis_kriteria == 'Benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $kriteria->jenis_kriteria }}
                                </span>
                            </td>

                            {{-- Kolom Bobot Kriteria --}}
                            <td class="px-6 py-2 md:py-4 whitespace-nowrap text-sm text-gray-600 font-mono flex justify-between md:table-cell">
                                <span class="md:hidden font-bold text-gray-500">Bobot:</span>
                                {{ number_format($kriteria->bobot_kriteria, 4) }}
                            </td>

                            {{-- Kolom Aksi: Atur Sub, Edit, dan Hapus --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-center md:table-cell">
                                <div class="flex flex-row md:justify-center gap-2 w-full md:w-auto">
                                    <a href="{{ route('admin.spk.kriteria.subkriteria.index', [$idKeputusan, $kriteria->id_kriteria]) }}"
                                        class="flex-1 md:flex-none text-center px-3 py-2 md:py-1 text-xs text-blue-600 hover:text-blue-900 border border-blue-600 hover:border-blue-900 rounded transition duration-150">
                                        Atur Sub
                                    </a>
                                    <a href="{{ route('admin.spk.kriteria.edit', [$idKeputusan, $kriteria->id_kriteria]) }}"
                                        class="flex-1 md:flex-none text-center px-3 py-2 md:py-1 text-xs text-indigo-600 hover:text-indigo-900 border border-indigo-600 hover:border-indigo-900 rounded transition duration-150">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.spk.kriteria.destroy', [$idKeputusan, $kriteria->id_kriteria]) }}" method="POST" class="flex-1 md:flex-none inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus kriteria ini?')"
                                            class="w-full px-3 py-2 md:py-1 text-xs text-red-600 hover:text-red-900 border border-red-600 hover:border-red-900 rounded transition duration-150">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center bg-gray-50">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-list-ul text-4xl text-gray-300 mb-3"></i>
                                    <p>Belum ada kriteria yang ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection