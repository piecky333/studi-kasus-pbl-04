@extends('layouts.admin')

@section('content')
    <div class="max-w-full md:max-w-5xl xl:max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center gap-2">
                    {{ $pageTitle ?? 'Daftar Keputusan SPK' }}
                </h2>
                
                <a href="{{ route('admin.spk.create') }}"
                    class="w-full md:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Keputusan Baru
                </a>
            </div>

            <div class="p-4 md:p-6">
                {{-- Menampilkan notifikasi sukses atau error setelah aksi --}}
                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                        <p class="font-bold">Berhasil</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                        <p class="font-bold">Error</p>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                {{-- Tampilan Responsif: Tabel pada desktop, Card pada mobile --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        {{-- Header Tabel: Disembunyikan pada tampilan mobile --}}
                        <thead class="bg-gray-50 hidden md:table-header-group">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">NO</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Keputusan</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-48">Tanggal Dibuat</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-64">Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody class="bg-white divide-y divide-gray-200 block md:table-row-group">
                            @forelse($keputusanList as $keputusan)
                                {{-- Baris Tabel: Berubah menjadi tampilan Card fleksibel pada mobile --}}
                                <tr class="hover:bg-gray-50 block md:table-row border-b md:border-b-0 mb-4 md:mb-0 pb-4 md:pb-0">
                                    
                                    {{-- Kolom Nomor Urut --}}
                                    <td class="px-6 py-2 md:py-4 whitespace-nowrap text-sm font-medium text-gray-900 flex justify-between md:table-cell">
                                        <span class="md:hidden font-bold text-gray-500">No:</span>
                                        <span class="bg-gray-100 px-2 py-1 rounded md:bg-transparent md:p-0">
                                            @if (method_exists($keputusanList, 'currentPage'))
                                                {{ ($keputusanList->currentPage() - 1) * $keputusanList->perPage() + $loop->iteration }}
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </span>
                                    </td>

                                    {{-- Kolom Nama Keputusan --}}
                                    <td class="px-6 py-2 md:py-4 text-sm text-gray-700 font-semibold flex flex-col md:table-cell">
                                        <span class="md:hidden font-bold text-gray-500 mb-1">Nama Keputusan:</span>
                                        {{ $keputusan->nama_keputusan }}
                                    </td>

                                    {{-- Kolom Status Keputusan --}}
                                    <td class="px-6 py-2 md:py-4 whitespace-nowrap flex justify-between md:justify-center md:table-cell items-center">
                                        <span class="md:hidden font-bold text-gray-500">Status:</span>
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $keputusan->status == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $keputusan->status }}
                                        </span>
                                    </td>

                                    {{-- Kolom Tanggal Pembuatan --}}
                                    <td class="px-6 py-2 md:py-4 whitespace-nowrap text-sm text-gray-500 flex justify-between md:table-cell">
                                        <span class="md:hidden font-bold text-gray-500">Tanggal:</span>
                                        {{ $keputusan->tanggal_dibuat }}
                                    </td>

                                    {{-- Kolom Aksi: Kelola, Edit, dan Hapus --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex justify-center md:table-cell">
                                        <div class="flex flex-row md:justify-center gap-2 w-full md:w-auto">
                                            {{-- Tombol Kelola: Masuk ke detail kriteria dan alternatif --}}
                                            <a href="{{ route('admin.spk.kriteria.index', ['idKeputusan' => $keputusan->id_keputusan]) }}"
                                                class="flex-1 md:flex-none text-center px-3 py-2 md:py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition shadow-sm">
                                                <i class="fas fa-cogs md:hidden mr-1"></i> Kelola
                                            </a>

                                            {{-- Tombol Edit: Mengubah data keputusan --}}
                                            <a href="{{ route('admin.spk.edit', $keputusan->id_keputusan) }}"
                                                class="flex-1 md:flex-none text-center px-3 py-2 md:py-1 text-xs font-medium text-white bg-amber-500 rounded hover:bg-amber-600 transition shadow-sm">
                                                <i class="fas fa-edit md:hidden mr-1"></i> Edit
                                            </a>

                                            {{-- Tombol Hapus: Menghapus keputusan beserta data terkait --}}
                                            <form action="{{ route('admin.spk.destroy', $keputusan->id_keputusan) }}" method="POST" class="flex-1 md:flex-none inline"
                                                onsubmit="return confirm('APAKAH ANDA YAKIN? Menghapus keputusan ini akan menghapus SEMUA data terkait: Kriteria, Alternatif, dan Hasil!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-3 py-2 md:py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 transition shadow-sm">
                                                    <i class="fas fa-trash md:hidden mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 text-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                            <p>Belum ada data Keputusan SPK.</p>
                                            <p class="text-xs mt-1">Klik tombol "Buat Keputusan Baru" untuk memulai.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Navigasi Paginasi jika data melebihi batas per halaman --}}
                @if (method_exists($keputusanList, 'links'))
                    <div class="mt-6">
                        {{ $keputusanList->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection