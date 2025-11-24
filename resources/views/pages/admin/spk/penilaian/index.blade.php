@extends('layouts.admin')

@section('content')

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg p-6">
            <header class="mb-6 border-b pb-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
                    <p class="text-sm text-gray-600">
                        Untuk Keputusan: <span
                            class="font-semibold text-indigo-600">{{ $keputusan->nama_keputusan ?? 'Tidak Ditemukan' }}</span>
                    </p>
                    @if ($kriteriaList->isEmpty() || $alternatifData->isEmpty())
                        <div class="mt-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded text-sm">
                            Harap tambahkan minimal 1 Kriteria dan 1 Alternatif untuk memulai Matriks Penilaian.
                        </div>
                    @endif
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.spk.alternatif.index', $keputusan->id_keputusan) }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                        <i class="fas fa-users mr-1"></i> Kelola Alternatif
                    </a>
                    <a href="{{ route('admin.spk.hasil.index', $keputusan->id_keputusan) }}"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                        <i class="fas fa-calculator mr-1"></i> Lanjut ke Perhitungan SAW
                    </a>
                </div>
            </header>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($kriteriaList->isNotEmpty() && $alternatifData->isNotEmpty())
                <form action="{{ route('admin.spk.alternatif.penilaian.update', $keputusan->id_keputusan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-md mb-6">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="w-1/4 px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r">
                                        Alternatif / Kriteria
                                    </th>
                                    {{-- Header Kriteria --}}
                                    @foreach($kriteriaList as $kriteria)
                                        <th scope="col"
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            {{ $kriteria->kode_kriteria }} <br> ({{ $kriteria->jenis_kriteria }})
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Data Alternatif dan Input Penilaian --}}
                                @foreach($alternatifData as $alternatif)
                                    <tr class="hover:bg-gray-50">
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900 border-r bg-gray-50">
                                            {{ $alternatif->nama_alternatif }}
                                        </td>
                                        @foreach($kriteriaList as $kriteria)
                                            <td class="px-4 py-2 text-center">
                                                @php
                                                    // Mendapatkan nilai tersimpan, default ke 0
                                                    $nilai = $penilaianMatrix[$alternatif->id_alternatif][$kriteria->id_kriteria] ?? 0;
                                                    $inputName = "nilai_penilaian[{$alternatif->id_alternatif}][{$kriteria->id_kriteria}]";
                                                    $isSubkriteria = isset($subKriteriaMap[$kriteria->id_kriteria]);
                                                @endphp

                                                @if($isSubkriteria)
                                                    {{-- Jika menggunakan Sub Kriteria (Dropdown) --}}
                                                    <select name="{{ $inputName }}"
                                                        class="block w-full py-2 px-1 text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                                        <option value="" disabled>Pilih Nilai</option>
                                                        @foreach($subKriteriaMap[$kriteria->id_kriteria] as $konversi => $namaSub)
                                                            <option value="{{ $konversi }}" {{ $nilai == $konversi ? 'selected' : '' }}>
                                                                {{ $namaSub }} ({{ number_format($konversi, 2) }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    {{-- Jika Input Langsung (Teks/Angka) --}}
                                                    <input type="number" name="{{ $inputName }}"
                                                        value="{{ number_format($nilai, 4, '.', '') }}" step="any" min="0"
                                                        class="block w-full py-1 px-1 text-sm text-center border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                                        required>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            <i class="fas fa-save mr-2"></i> Simpan Matriks Penilaian
                        </button>
                    </div>
                </form>

                {{-- Panduan Penilaian --}}
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Panduan Penilaian per Kriteria</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($kriteriaList as $kriteria)
                            <div
                                class="p-4 border rounded-lg shadow-sm {{ $kriteria->subKriteria->count() > 0 ? 'bg-indigo-50 border-indigo-300' : 'bg-gray-50 border-gray-300' }}">
                                <p class="font-bold text-base">{{ $kriteria->kode_kriteria }} - {{ $kriteria->nama_kriteria }}</p>
                                <p class="text-xs italic text-gray-600 mt-1">Jenis: {{ $kriteria->jenis_kriteria }}</p>

                                @if ($kriteria->subKriteria->count() > 0)
                                    <p class="mt-2 text-sm font-medium text-indigo-700">Mode: Pilihan Sub Kriteria</p>
                                    <ul class="list-disc list-inside mt-1 text-xs text-gray-700">
                                        @foreach($kriteria->subKriteria as $sub)
                                            <li>{{ $sub->nama_subkriteria }}: **{{ number_format($sub->nilai, 2) }}**</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mt-2 text-sm font-medium text-gray-700">Mode: Input Langsung (Angka)</p>
                                    <p class="text-xs text-gray-600 italic">Nilai diinput sebagai angka mentah (misalnya: skor, jumlah,
                                        atau rating).</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            @endif
        </div>
    </div>

@endsection