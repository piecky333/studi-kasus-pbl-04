@extends('pages.admin.spk.detail_base', ['currentTab' => 'alternatif', 'idKeputusan' => $idKeputusan, 'keputusan' => $keputusan])

@section('detail_content')

    <div class="bg-white rounded-lg">

        {{-- Menampilkan notifikasi sukses atau error setelah aksi --}}
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
                {{-- Tombol untuk membuka halaman tambah alternatif baru --}}
                <a href="{{ route('admin.spk.alternatif.create', $idKeputusan) }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                    <i class="fas fa-plus mr-1"></i> Tambah Alternatif
                </a>

                {{-- Tombol pintas menuju halaman pengisian matriks penilaian --}}
                <a href="{{ route('admin.spk.alternatif.penilaian.index', $idKeputusan) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                    <i class="fas fa-table mr-1"></i> Atur Matriks Penilaian
                </a>
            </div>
        </header>

        {{-- Tabel yang menampilkan daftar alternatif yang sudah terdaftar --}}
        <form action="{{ route('admin.spk.alternatif.bulkDestroy', $idKeputusan) }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')
            
            <div class="mb-4 flex justify-end">
                <button type="submit" onclick="return confirm('Yakin ingin menghapus data yang dipilih?')" 
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed" id="bulkDeleteBtn" disabled>
                    <i class="fas fa-trash mr-1"></i> Hapus Terpilih
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 sm:rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-center w-12 border-b border-gray-200">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-4 w-4">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16 border-b border-gray-200">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Nama Alternatif</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Semester</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Status Penilaian</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($alternatifList as $alternatif)
                            @php
                                // Cek apakah sudah ada nilai penilaian yang masuk (total nilai > 0)
                                $totalNilai = $alternatif->penilaian->sum('nilai');
                                $sudahDinilai = $totalNilai > 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <input type="checkbox" name="selected_alternatif[]" value="{{ $alternatif->id_alternatif }}" class="rowCheckbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-4 w-4">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-semibold text-gray-900">{{ $alternatif->nama_alternatif }}</div>
                                    </div>
                                    @if($alternatif->keterangan)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($alternatif->keterangan, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-800">
                                    {{ $alternatif->mahasiswa->semester ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @if($sudahDinilai)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-check-circle mr-1.5"></i> Sudah Dinilai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-times-circle mr-1.5"></i> Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                    <a href="{{ route('admin.spk.alternatif.penilaian.index', $idKeputusan) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150" title="Atur Nilai">
                                        <i class="fas fa-edit"></i> Nilai
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 whitespace-nowrap text-center text-gray-500 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-base font-medium">Belum ada Alternatif</p>
                                        <p class="text-xs mt-1">Silakan tambahkan data alternatif mahasiswa terlebih dahulu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAll = document.getElementById('selectAll');
                const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
                const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

                function updateButtonState() {
                    const checkedCount = document.querySelectorAll('.rowCheckbox:checked').length;
                    bulkDeleteBtn.disabled = checkedCount === 0;
                }

                selectAll.addEventListener('change', function() {
                    rowCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAll.checked;
                    });
                    updateButtonState();
                });

                rowCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateButtonState();
                        // Uncheck "Select All" if any row is unchecked
                        if (!this.checked) {
                            selectAll.checked = false;
                        }
                    });
                });
            });
        </script>
    </div>

@endsection