@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            {{-- Menampilkan notifikasi sukses atau error setelah aksi --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Header Halaman: Menampilkan informasi kriteria induk --}}
            <header class="flex justify-between items-center mb-6 border-b pb-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight tracking-tight">{{ $pageTitle }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Kriteria Utama: <span class="font-bold text-indigo-700">[{{ $kriteria->kode_kriteria }}] -
                            {{ $kriteria->nama_kriteria }}</span>
                        (Keputusan: {{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }})
                    </p>
                </div>

                {{-- Bagian Tombol Aksi --}}
                <div class="flex space-x-3">
                    {{-- Tombol untuk menambah sub kriteria baru --}}
<a href="{{ route('admin.spk.kriteria.subkriteria.create', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150 shadow-md flex items-center">
                        <i class="fas fa-plus mr-1"></i> Tambah Sub Kriteria
                    </a>
                </div>
            </header>

            {{-- Tabel Daftar Sub Kriteria --}}
            <div class="bg-white  overflow-hidden sm:rounded-lg border border-gray-200">
                <div class="p-0">
                    <div class="min-w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-1/12">
                                        No</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-6/12">
                                        Nama Sub Kriteria</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-2/12">
                                        Nilai Konversi</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider w-3/12">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($subkriteriaList as $index => $sub)
                                                            <tr class="hover:bg-gray-50">
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                                                    {{ $index + 1 }}
                                                                </td>
                                                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $sub->nama_subkriteria }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-indigo-600">
                                                                    {{ number_format($sub->nilai, 4) }}
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3">

                                                                    {{-- Tombol Edit Sub Kriteria --}}
                                                                    <a href="{{ route(
                                        'admin.spk.kriteria.subkriteria.edit',
                                        [
                                            'idKeputusan' => $keputusan->id_keputusan,
                                            'idKriteria' => $kriteria->id_kriteria,
                                            'subkriteriumId' => $sub->id_subkriteria
                                        ]
                                    ) 
                                                                                                                                            }}"
                                                                        class=" text-xs text-indigo-600 hover:text-indigo-900 border border-indigo-600 hover:border-indigo-900 px-2 py-1 rounded transition duration-150">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </a>

                                                                    {{-- Tombol Hapus Sub Kriteria --}}                                                                                                                                
                                                                    <form action="{{ 
                                                                                                                route('admin.spk.kriteria.subkriteria.destroy', [
                                            'idKeputusan' => $keputusan->id_keputusan,
                                            'idKriteria' => $kriteria->id_kriteria,
                                            'subkriteriumId' => $sub->id_subkriteria
                                        ]) 
                                                                                                            }}" method="POST" class="inline-block ml-1"
                                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus subkriteria ini? Tindakan ini tidak dapat dibatalkan.');">


                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="text-xs text-red-600 hover:text-red-900 border border-red-600 hover:border-red-900 px-2 py-1 rounded transition duration-150">
                                                                            <i class="fas fa-trash"></i> Hapus
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-10 text-center text-gray-500 text-base italic bg-gray-50">
                                            Belum ada Sub Kriteria yang terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tombol Kembali ke daftar kriteria --}}
            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.spk.kriteria.index', $keputusan->id_keputusan) }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150 shadow-md">
                    Kembali ke Kriteria
                </a>
            </div>
        </div>
    </div>

@endsection