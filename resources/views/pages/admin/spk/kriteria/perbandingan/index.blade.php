@extends('layouts.admin')
{{-- Sesuaikan dengan layout yang Anda gunakan --}}

@section('content')
    <div class="container mx-auto p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl overflow-hidden rounded-lg p-6">
                
                {{-- ========================================================== --}}
                {{-- Navigasi Breadcrumb untuk menunjukkan lokasi halaman saat ini --}}
                {{-- ... (kode Breadcrumb tetap sama) ... --}}
                <nav class="text-sm font-medium text-gray-500 mb-2" aria-label="Breadcrumb">
                    <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center">
                            <a href="{{ route('admin.spk.index') }}" class="text-gray-500 hover:text-gray-700">
                                Daftar Keputusan SPK
                            </a>
                            <svg class="flex-shrink-0 mx-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10l-3.293-3.293a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        </li>
                        <li class="flex items-center">
                            <a href="{{ route('admin.spk.kriteria.index', $idKeputusan) }}" class="text-gray-500 hover:text-gray-700">Daftar Kriteria</a>
                        </li><svg class="flex-shrink-0 mx-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10l-3.293-3.293a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        
                        <li class="flex items-center">
                            <span class="text-indigo-600 font-semibold">Bobot Preferensi AHP</span>
                        </li>
                    </ol>
                </nav>
                {{-- ========================================================== --}}
                
                {{-- Menampilkan notifikasi sukses atau error setelah aksi --}}
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

                <div class="bg-white rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Perbandingan Data Antar Kriteria (AHP)</h2>
                    <p class="text-gray-600 mb-6">Silakan bandingkan setiap pasangan kriteria menggunakan skala Saaty (1-9).</p>

                    {{-- Form utama untuk menyimpan nilai perbandingan dan melakukan cek konsistensi --}}
                    <form id="ahp-form" method="POST">
                        @csrf
                        <input type="hidden" name="id_keputusan" value="{{ $idKeputusan ?? '' }}">

                        {{-- Bagian Input Matriks Perbandingan Berpasangan --}}
                        <div class="overflow-x-auto border rounded-lg p-4 bg-gray-50">
                            <h4 class="font-semibold text-gray-700 mb-3">Input Perbandingan Pasangan</h4>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider w-1/4">Kriteria A</th>
                                        <th class="px-3 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider w-1/2">Skala Perbandingan (1-9)</th>
                                        <th class="px-3 py-3 text-right text-sm font-semibold text-gray-700 uppercase tracking-wider w-1/4">Kriteria B</th>
                                    </tr>
                                </thead>
                                
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($pasangan as $p)
                                        <tr>
                                            {{-- Kolom Kriteria A (Sisi Kiri) --}}
                                            <td class="px-3 py-4 whitespace-nowrap align-top text-sm font-medium text-gray-900">
                                                ({{ $p['kriteria1']->kode_kriteria }}) {{ $p['kriteria1']->nama_kriteria }}
                                                <input type="hidden" name="pasangan[{{ $p['kriteria1']->id_kriteria }}_{{ $p['kriteria2']->id_kriteria }}][kriteria1_id]" value="{{ $p['kriteria1']->id_kriteria }}">
                                                <input type="hidden" name="pasangan[{{ $p['kriteria1']->id_kriteria }}_{{ $p['kriteria2']->id_kriteria }}][kriteria2_id]" value="{{ $p['kriteria2']->id_kriteria }}">
                                            </td>

                                            {{-- Input Skala Saaty (1-9) menggunakan Radio Button --}}
                                            <td class="px-3 py-4 align-top text-center text-sm text-gray-500">
                                                @php
                                                    $keyPasangan = "{$p['kriteria1']->id_kriteria}_{$p['kriteria2']->id_kriteria}";
                                                    $inputName = "pasangan[{$keyPasangan}][nilai]";

                                                    // Prioritaskan nilai dari old input (jika ada withInput()), jika tidak ada, ambil dari DB/pasangan (yang sudah tersimpan)
                                                    $nilaiTersimpan = old("pasangan.{$keyPasangan}.nilai", $p['nilai_perbandingan_tersimpan'] ?? 1);
                                                @endphp

                                                <div class="flex justify-center items-center space-x-0.5">
                                                    {{-- Opsi Skala: Kriteria A lebih penting dari B (Nilai 2-9) --}}
                                                    <div class="inline-flex">
                                                        @for ($i = 9; $i >= 2; $i--)
                                                            @php
                                                                $isChecked = $nilaiTersimpan == $i;
                                                            @endphp
                                                            <label class="relative group" title="Skala {{ $i }}: A lebih penting dari B">
                                                                <input type="radio" name="{{ $inputName }}" value="{{ $i }}"
                                                                    class="hidden peer" {{ $isChecked ? 'checked' : '' }} required>
                                                                <span
                                                                    class="px-2 py-1 text-xs font-semibold border border-green-400 text-green-700 bg-green-50 hover:bg-green-200 transition duration-150 ease-in-out cursor-pointer 
                                                                    peer-checked:bg-green-700 peer-checked:text-white peer-checked:border-green-700 
                                                                    {{ $i == 9 ? 'rounded-l-lg' : '' }} border-r-0">
                                                                    {{ $i }}
                                                                </span>
                                                            </label>
                                                        @endfor
                                                    </div>

                                                    {{-- Opsi Skala: Kriteria A dan B sama pentingnya (Nilai 1) --}}
                                                    <label class="relative group" title="Skala 1: A dan B Sama Penting">
                                                        <input type="radio" name="{{ $inputName }}" value="1" class="hidden peer"
                                                            {{ $nilaiTersimpan == 1 ? 'checked' : '' }} required>
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold border border-blue-400 text-blue-700 bg-blue-50 hover:bg-blue-200 transition duration-150 ease-in-out cursor-pointer 
                                                            peer-checked:bg-blue-700 peer-checked:text-white peer-checked:border-blue-700">
                                                            1
                                                        </span>
                                                    </label>

                                                    {{-- Opsi Skala: Kriteria B lebih penting dari A (Nilai 1/2 - 1/9) --}}
                                                    <div class="inline-flex">
                                                        @for ($i = 2; $i <= 9; $i++)
                                                            @php
                                                                $valueKanan = -$i; // Nilai Negatif untuk Kriteria Kanan
                                                                $isChecked = $nilaiTersimpan == $valueKanan;
                                                            @endphp
                                                            <label class="relative group" title="Skala 1/{{ $i }}: B lebih penting dari A">
                                                                <input type="radio" name="{{ $inputName }}" value="{{ $valueKanan }}"
                                                                    class="hidden peer" {{ $isChecked ? 'checked' : '' }} required>
                                                                <span
                                                                    class="px-2 py-1 text-xs font-semibold border border-red-400 text-red-700 bg-red-50 hover:bg-red-200 transition duration-150 ease-in-out cursor-pointer 
                                                                    peer-checked:bg-red-700 peer-checked:text-white peer-checked:border-red-700 
                                                                    {{ $i == 9 ? 'rounded-r-lg' : '' }} border-l-0">
                                                                    {{ $i }}
                                                                </span>
                                                            </label>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Kolom Kriteria B (Sisi Kanan) --}}
                                            <td
                                                class="px-3 py-4 whitespace-nowrap align-top text-sm font-medium text-gray-900 text-right">
                                                ({{ $p['kriteria2']->kode_kriteria }}) {{ $p['kriteria2']->nama_kriteria }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-center space-x-4 mt-8">
                            {{-- Tombol Simpan: Menyimpan nilai perbandingan ke database --}}
                            <button type="submit" formaction="{{ route('admin.spk.kriteria.perbandingan.simpan', $idKeputusan) }}"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-md inline-flex items-center">
                                <i class="fas fa-save mr-2"></i> Simpan
                            </button>

                            {{-- Tombol Cek Konsistensi: Menghitung rasio konsistensi (CR) --}}
                            <button type="submit" formaction="{{ route('admin.spk.kriteria.perbandingan.cek_konsistensi', $idKeputusan) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-md inline-flex items-center">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Cek Konsistensi
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Bagian Hasil Perhitungan AHP (Ditampilkan jika data tersedia) --}}
                @if(isset($hasilAHP))
                    <div
                        class="mt-10 bg-white rounded-lg p-6 border-t-4 @if($hasilAHP['crData']['cr'] <= 0.1) border-green-500 @else border-red-500 @endif">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Hasil Perhitungan AHP dan Konsistensi</h3>

                        <h2><b>Ringkasan Perhitungan AHP</b></h2>
                        <br>
                        {{-- Ringkasan Nilai Consistency Ratio (CR) --}}
                        <div
                            class="mb-6 p-4 rounded-lg @if($hasilAHP['crData']['cr'] <= 0.1) bg-green-50 border border-green-300 @else bg-red-50 border border-red-300 @endif">
                            <div class="flex justify-between items-center">
                                <p
                                    class="font-bold text-lg @if($hasilAHP['crData']['cr'] <= 0.1) text-green-700 @else text-red-700 @endif">
                                    Consistency Ratio (CR): <span
                                        class="text-2xl">{{ number_format($hasilAHP['crData']['cr'], 4) }}</span>
                                    @if($hasilAHP['crData']['cr'] <= 0.1)
                                        <span class="ml-2 text-sm font-normal text-green-600">(KONSISTEN, CR &le; 0.10)</span>
                                    @else
                                        <span class="ml-2 text-sm font-normal text-red-600">(TIDAK KONSISTEN, CR > 0.10)</span>
                                    @endif
                                </p>
                                @if($hasilAHP['crData']['cr'] <= 0.1)
                                    <a href="{{ route('admin.spk.hasil.index', $idKeputusan) }}"
                                        class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-sm text-sm">
                                        <i class="fas fa-play mr-2"></i> Lanjutkan ke Proses SAW
                                    </a>
                                @endif
                            </div>
                            

                            {{-- Detail Nilai Indeks Konsistensi (CI) dan Random Index (RI) --}}
                            <div class="mt-2 text-sm space-y-1">
                                <p><strong>CI (Consistency Index):</strong> <span class="font-mono">{{ number_format($hasilAHP['crData']['ci'], 4) }}</span></p>
                                <p><strong>RI (Random Index, n={{ $hasilAHP['n'] }}):</strong> <span class="font-mono">{{ number_format($hasilAHP['crData']['ri'], 4) }}</span></p>
                            </div>

                            @if($hasilAHP['crData']['cr'] > 0.1)
                                <p class="text-sm mt-1 text-red-600 font-semibold">
                                    **TINJAU ULANG:** Harap perbaiki nilai perbandingan Anda di atas hingga CR &le; 0.10.
                                </p>
                            @endif
                        </div>

                        {{-- Hasil Akhir Bobot Prioritas Kriteria (Eigen Vector) --}}
                        <div class="p-4 bg-gray-50 rounded-lg shadow-inner mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">Prioritas Bobot Akhir Kriteria (Eigen Vector)</h4>
                            <div class="flex flex-wrap gap-4 justify-center">
                                @foreach ($kriteriaList as $i => $kriteria)
                                    <div class="p-3 bg-white rounded-md shadow-sm border border-blue-200 min-w-[120px] text-center">
                                        <p class="text-sm font-medium text-blue-600">{{ $kriteria->kode_kriteria }}</p>
                                        <p class="text-xl font-bold text-gray-900">{{ number_format($hasilAHP['weights'][$i], 4) }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <br>
                            <p class="text-center text-xs text-gray-500 mt-2">Total Bobot: {{ number_format(array_sum($hasilAHP['weights'] ?? [0]), 4) }}</p>
                        </div>
                        <br>

                        <h2><b>Matriks Perbandingan Berpasangan</b></h2>
                        <p class="text-sm text-gray-600 mb-3">Matriks ini dibuat dari input perbandingan Anda Input dari skala perbandingan.</p>
                        <div class="overflow-x-auto border rounded-lg mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-600 uppercase tracking-wider border-r">
                                            Kriteria</th>
                                        @foreach ($kriteriaList as $kriteria)
                                            <th class="px-3 py-2 text-xs font-medium text-gray-600 uppercase tracking-wider text-center">
                                                {{ $kriteria->kode_kriteria }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($hasilAHP['matrix'] as $row_index => $row)
                                        <tr>
                                            {{-- Baris Header Kriteria (Menangani kasus jika kriteria terhapus) --}}
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50 border-r">
                                                @if (isset($kriteriaList[$row_index]))
                                                    {{ $kriteriaList[$row_index]->kode_kriteria }}
                                                @else
                                                    {{-- Maksud dari Kriteria HILANG: Matriks AHP memiliki 7 baris, tapi database hanya ada 6 kriteria --}}
                                                    <span class="text-red-600 font-bold">Kriteria HILANG (#{{ $row_index + 1 }})</span>
                                                @endif
                                            </td>
                                            {{-- Sel Nilai Matriks Perbandingan --}}
                                            @foreach ($row as $col_index => $value)
                                                <td
                                                    class="px-3 py-2 whitespace-nowrap text-sm text-center font-mono @if($row_index == $col_index) bg-gray-200 font-bold @endif">
                                                    {{ number_format($value, 4) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    {{-- Baris Total Penjumlahan Kolom --}}
                                    <tr>
                                        <td class="px-3 py-2 text-xs font-bold text-gray-600 bg-gray-100 border-r">Total Kolom</td>
                                        @foreach($hasilAHP['col_sum'] as $sum)
                                            <td class="px-3 py-2 text-xs font-bold text-gray-700 bg-gray-100 text-center">
                                                {{ number_format($sum, 4) }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>

                        <h2> <b>Matriks Normalisasi (Vektor Prioritas)</b> </h2>
                        <p class="text-sm text-gray-600 mb-3">Nilai di dalamnya didapatkan dari pembagian elemen matriks input dengan Total Kolom yang sesuai. Rata-rata dari nilai-nilai setiap baris inilah yang menghasilkan Bobot Prioritas.</p>
                        <div class="overflow-x-auto border rounded-lg mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-600 uppercase tracking-wider border-r">Kriteria</th>
                                        @foreach ($kriteriaList as $kriteria)
                                            <th class="px-3 py-2 text-xs font-medium text-gray-600 uppercase tracking-wider text-center">{{ $kriteria->kode_kriteria }}</th>
                                        @endforeach
                                        <th
                                            class="px-3 py-2 text-xs font-bold text-gray-700 uppercase tracking-wider text-center bg-blue-100 border-l">
                                            Bobot Prioritas / Rata-rata Baris (WP)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($hasilAHP['normalized_matrix'] as $row_index => $row)
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50 border-r">
                                                @if (isset($kriteriaList[$row_index]))
                                                    {{ $kriteriaList[$row_index]->kode_kriteria }}
                                                @else
                                                    <span class="text-red-600 font-bold">Kriteria HILANG (#{{ $row_index + 1 }})</span>
                                                @endif
                                            </td>
                                            @foreach ($row as $col_index => $value)
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-mono">
                                                    {{ number_format($value, 4) }}
                                                </td>
                                            @endforeach
                                            <td
                                                class="px-3 py-2 whitespace-nowrap text-sm text-center font-bold font-mono bg-blue-100 border-l">
                                                {{ number_format($hasilAHP['weights'][$row_index], 4) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br>

                        <h2><b>Perhitungan Lambda Maksimum dan Vektor Prioritas</b> </h2>
                        <p class="text-sm text-gray-600 mb-3">perhitungan nilai Lambda Maksimum, vektor Prioritas, dan Vektor Konsistensi sebagai dasar untuk menghitung Consistency Index (CI) dan Consistency Ratio (CR). </p>

                        <div class="overflow-x-auto border rounded-lg mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-600 uppercase tracking-wider border-r">Kriteria</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-600 uppercase tracking-wider text-center">Eigen Vector</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-600 uppercase tracking-wider text-center">Vektor Jumlah Tertimbang</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-600 uppercase tracking-wider text-center">Vektor Konsistensi Rasio</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($kriteriaList as $i => $kriteria)
                                        <tr>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50 border-r">
                                                {{ $kriteria->kode_kriteria }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-mono">{{ number_format($hasilAHP['weights'][$i], 4) }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-mono">{{ number_format($hasilAHP['weighted_sum'][$i], 4) }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-center font-mono">{{ number_format($hasilAHP['ratio_vector'][$i], 4) }}</td>
                                        </tr>
                                    @endforeach
                                    {{-- Baris Total Vektor Rasio Konsistensi --}}
                                    <tr>
                                        <td colspan="3"
                                            class="px-3 py-2 text-xs font-bold text-right text-gray-700 bg-gray-100 border-r">Total
                                            Vektor Rasio</td>
                                        <td class="px-3 py-2 text-xs font-bold text-gray-700 bg-gray-100 text-center">
                                            {{ number_format(array_sum($hasilAHP['ratio_vector']), 4) }}</td>
                                    </tr>
                                    {{-- Nilai Lambda Maksimum (Eigenvalue Terbesar) --}}
                                    <tr>
                                        <td colspan="3"
                                            class="px-3 py-2 text-xs font-bold text-right text-gray-700 bg-gray-100 border-r">
                                            Lambda Maksimum
                                        </td>
                                        <td class="px-3 py-2 text-xs font-bold text-gray-700 bg-gray-100 text-center bg-yellow-100">
                                            {{ number_format($hasilAHP['crData']['lambda_max'], 4) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>

                        <h2 class="text-gray-700 mb-2"><b>Perhitungan Akhir</b></h2>
                        <div class="p-4 bg-gray-50 rounded-lg shadow-inner">
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li><b>Consistency Index (CI):</b> <br>lambda maximum - n / (n - 1) 
                                    <br>
                                    ({{ number_format($hasilAHP['crData']['lambda_max'], 4) }} - {{ $hasilAHP['n'] }}) /
                                    ({{ $hasilAHP['n'] }} - 1) = {{ number_format($hasilAHP['crData']['ci'], 4) }}</li>
                                <br>
                                <li><b>Consistency Ratio (CR):</b> <br>CI / RI <br> {{ number_format($hasilAHP['crData']['ci'], 4) }} /
                                    {{ number_format($hasilAHP['crData']['ri'], 4) }} =
                                    {{ number_format($hasilAHP['crData']['cr'], 4) }}</li>
                                    <br>
                                    <p>Nilai CR adalah indikator utama validitas bobot kriteria yang dihasilkan, di mana CR < 0.10 menunjukkan Matriks Perbandingan Anda sudah layak digunakan.</p>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection