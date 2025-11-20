@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-6">
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
                            {{-- Tombol untuk kembali ke Daftar Kriteria --}}
                            <a href="{{ route('admin.spk.kriteria.index') }}" class="text-gray-500 hover:text-gray-700">
                                Daftar Kriteria
                            </a>
                            <svg class="flex-shrink-0 mx-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10l-3.293-3.293a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </li>
                        <li class="flex items-center">
                            <span class="text-indigo-600 font-semibold">Daftar Sub Kriteria</span>
                        </li>
                    </ol>
                </nav>
                {{-- ========================================================== --}}

                <header class="mb-6 border-b pb-4 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
                        <p class="text-sm text-gray-600">
                            Kriteria Utama: <span class="font-semibold text-indigo-600">{{ $kriteria->kode_kriteria }} -
                                {{ $kriteria->nama_kriteria }}</span>
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.spk.kriteria.index', $keputusan->id_keputusan) }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Kriteria
                        </a>
                        <a href="{{ route('admin.spk.kriteria.subkriteria.create', [$keputusan->id_keputusan, $kriteria->id_kriteria]) }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                            <i class="fas fa-plus mr-1"></i> Tambah Sub Kriteria
                        </a>
                    </div>
                </header>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-1/12">
                                    No</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-6/12">
                                    Nama Sub Kriteria</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-2/12">
                                    Nilai Konversi</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider w-3/12">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($subKriteriaData as $index => $sub)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">{{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $sub->nama_subkriteria }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-indigo-600 font-bold">
                                        {{ number_format($sub->nilai_konversi, 4) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('kriteria.subkriteria.edit', [$keputusan->id_keputusan, $kriteria->id_kriteria, $sub->id_subkriteria]) }}"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>

                                        {{-- Tombol Hapus --}}
                                        <form
                                            action="{{ route('kriteria.subkriteria.destroy', [$keputusan->id_keputusan, $kriteria->id_kriteria, $sub->id_subkriteria]) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('APAKAH ANDA YAKIN? Menghapus sub kriteria ini akan mempengaruhi penilaian alternatif!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm font-medium text-gray-500 bg-gray-50">
                                        Belum ada Sub Kriteria yang ditambahkan untuk kriteria ini.
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