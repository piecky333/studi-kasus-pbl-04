@extends('layouts.admin')

@section('content')

<div class="max-w-6xl mx-auto py-8 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg p-8">
        <header class="mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $pageTitle }}</h1>
            <p class="text-sm text-gray-600">
                Input Penilaian untuk: <span class="font-semibold text-indigo-600 text-base">{{ $alternatif->nama_alternatif }}</span>
            </p>
        </header>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                <strong class="font-bold">Oops! Ada masalah dengan input Anda:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('penilaian.update', [$keputusan->id_keputusan, $alternatif->id_alternatif]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                {{-- Kolom Kiri: Form Input --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Input Nilai Berdasarkan Kriteria</h3>
                    
                    @forelse($kriteriaList as $kriteria)
                        @php
                            $nilaiSaatIni = $penilaianData[$kriteria->id_kriteria] ?? 0;
                            $inputName = "nilai_kriteria[{$kriteria->id_kriteria}]";
                            $isSubkriteria = isset($subKriteriaMap[$kriteria->id_kriteria]);
                        @endphp

                        <div class="p-4 border rounded-md {{ $isSubkriteria ? 'bg-indigo-50 border-indigo-200' : 'bg-gray-50 border-gray-200' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ $kriteria->kode_kriteria }} - {{ $kriteria->nama_kriteria }}
                                <span class="text-xs italic text-gray-500">({{ $kriteria->jenis_kriteria }})</span>
                            </label>

                            @if($isSubkriteria)
                                {{-- Jika Sub Kriteria (Dropdown) --}}
                                <select name="{{ $inputName }}" 
                                        class="mt-1 block w-full py-2 px-3 text-base border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="" disabled selected>-- Pilih Nilai Sub Kriteria --</option>
                                    @foreach($subKriteriaMap[$kriteria->id_kriteria] as $konversi => $namaSub)
                                        <option value="{{ $konversi }}" {{ (string)$nilaiSaatIni == (string)$konversi ? 'selected' : '' }}>
                                            {{ $namaSub }} (Nilai: {{ number_format($konversi, 4) }})
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{-- Jika Input Langsung (Angka) --}}
                                <input type="number" name="{{ $inputName }}" 
                                       value="{{ old($inputName, number_format($nilaiSaatIni, 4, '.', '')) }}"
                                       step="any" min="0" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Masukkan nilai numerik" required>
                            @endif
                            @error($inputName)
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada Kriteria yang didaftarkan.</p>
                    @endforelse
                </div>

                {{-- Kolom Kanan: Panduan Penilaian --}}
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2">Panduan Nilai Sub Kriteria</h3>
                    
                    @forelse($kriteriaList as $kriteria)
                        @if ($kriteria->subKriteria->count() > 0)
                            <div class="p-4 bg-white rounded-md shadow-sm border border-indigo-300">
                                <p class="font-bold text-base text-indigo-700">{{ $kriteria->kode_kriteria }} - {{ $kriteria->nama_kriteria }}</p>
                                <p class="text-sm italic text-gray-600">Pilih salah satu dari:</p>
                                <ul class="list-disc list-inside mt-2 text-sm text-gray-700 space-y-1">
                                    @foreach($kriteria->subKriteria as $sub)
                                        <li>{{ $sub->nama_subkriteria }} (Konversi: **{{ number_format($sub->nilai_konversi, 4) }}**)</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @empty
                        <p class="text-gray-500">Tidak ada kriteria yang menggunakan Sub Kriteria.</p>
                    @endforelse
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('penilaian.index', $keputusan->id_keputusan) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150 shadow-sm">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    <i class="fas fa-check mr-2"></i> Selesai & Simpan Penilaian
                </button>
            </div>
        </form>
    </div>
</div>

@endsection