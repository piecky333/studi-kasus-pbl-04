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

            <div class="overflow-x-auto border border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-center w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">Nama Alternatif</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($alternatifList as $alternatif)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <input type="checkbox" name="selected_alternatif[]" value="{{ $alternatif->id_alternatif }}" class="rowCheckbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $alternatif->nama_alternatif }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Perlu Diperiksa
                                    </span>
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