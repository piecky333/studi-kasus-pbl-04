@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">

            {{-- ========================================================== --}}
            {{-- BREADCRUMB NAVIGASI --}}
            {{-- ========================================================== --}}
            <nav class="text-sm font-medium text-gray-500 mb-4" aria-label="Breadcrumb">
                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        {{-- Tombol untuk kembali ke Daftar Keputusan SPK --}}
                        <a href="{{ route('admin.spk.index') }}" class="text-gray-500 hover:text-gray-700">
                            Daftar Keputusan SPK
                        </a>
                        <svg class="flex-shrink-0 mx-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10l-3.293-3.293a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li class="flex items-center">
                        <span class="text-indigo-600 font-semibold">Daftar Kriteria</span>
                    </li>
                </ol>
            </nav>
            {{-- ========================================================== --}}

            <header class="mb-6 border-b pb-4 flex justify-between items-center">

                <div>
                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Untuk Keputusan: <span
                            class="font-semibold text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span>
                    </p>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.spk.perbandingan.index', $keputusan->id_keputusan) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                        Bobot Preferensi AHP
                    </a>
                    <a href="{{ route('admin.spk.kriteria.create', $keputusan->id_keputusan) }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                        <i class="fas fa-plus mr-1"></i> Tambah Data
                    </a>
                </div>
            </header>

            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabel Kriteria Utama --}}
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 p-4 border-b bg-gray-50">Daftar Data Kriteria</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">
                                No</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">
                                Kode Kriteria</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-3/12">
                                Nama Kriteria</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">
                                Jenis</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">
                                Bobot</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">
                                Cara Penilaian</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kriteriaData as $index => $kriteria)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $kriteria->kode_kriteria }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $kriteria->nama_kriteria }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ strtolower($kriteria->jenis_kriteria) == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $kriteria->jenis_kriteria }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-indigo-600 font-medium">
                                    @if($kriteria->bobot_kriteria > 0)
                                        <span
                                            class="font-bold text-indigo-700">{{ number_format($kriteria->bobot_kriteria, 4) }}</span>
                                    @else
                                        <span class="text-xs font-semibold text-red-500">Belum dihitung</span>
                                    @endif
                                </td>

                                {{-- Logika Dinamis Cara Penilaian --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600">
                                    @if($kriteria->subKriteria->count() > 0)
                                        <span class="text-xs font-medium text-indigo-700 bg-indigo-100 px-2 py-1 rounded-full">
                                            Pilihan Sub Kriteria
                                        </span>
                                    @else
                                        <span class="text-xs font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded-full">
                                            Input Langsung
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                    <div class="flex flex-wrap justify-center space-x-2 gap-1">

                                        {{-- Tombol Edit (Koreksi Styling) --}}
                                        <a href="{{ route('admin.spk.kriteria.edit', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium px-2 py-1 rounded-md transition duration-150">
                                            Edit
                                        </a>

                                        {{-- Tombol Hapus (Koreksi Styling) --}}
                                        <form
                                            action="{{ route('admin.spk.kriteria.destroy', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('APAKAH ANDA YAKIN? Menghapus kriteria ini akan menghapus semua Penilaian terkait di semua alternatif!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-xs font-medium px-2 py-1 rounded-md transition duration-150">
                                                Hapus
                                            </button>
                                        </form>

                                        {{-- Tombol Atur/Tambah Sub Kriteria --}}
                                        <a href="{{ route('admin.spk.kriteria.subkriteria.index', ['idKeputusan' => $keputusan->id_keputusan, 'idKriteria' => $kriteria->id_kriteria]) }}"
                                            class="text-indigo-600 hover:text-indigo-800 text-xs font-medium px-2 py-1 rounded-md transition duration-150">
                                            {{ $kriteria->subKriteria->count() > 0 ? 'Atur Sub (' . $kriteria->subKriteria->count() . ')' : 'Tambah Sub' }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm font-medium text-gray-500 bg-gray-50">
                                    Belum ada Kriteria yang ditambahkan untuk keputusan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection