@extends('layouts.admin')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid px-4 mt-6 pb-4">
    
    {{-- Header Section --}}
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                Data Mahasiswa
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Kelola data mahasiswa universitas dengan mudah.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 border border-transparent rounded-md shadow-sm text-xs lg:text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                <i class="fas fa-file-excel mr-2"></i> Import Excel
            </button>
            <a href="{{ route('admin.datamahasiswa.create') }}" class="inline-flex items-center px-3 py-2 lg:px-4 lg:py-2 border border-transparent rounded-md shadow-sm text-xs lg:text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Tambah Mahasiswa
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="mb-6 bg-white p-5 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center mb-4">
            <i class="fas fa-filter text-indigo-600 mr-2"></i>
            <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian Data</h3>
        </div>
        <form action="{{ route('admin.datamahasiswa.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                {{-- Filter Semester --}}
                <div class="md:col-span-3">
                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Filter Semester</label>
                    <div class="relative">
                        <select name="semester" id="semester" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm" onchange="this.form.submit()">
                            <option value="">Semua Semester</option>
                            @for ($i = 1; $i <= 14; $i++)
                                <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Filter Urutan --}}
                <div class="md:col-span-3">
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                    <div class="relative">
                        <select name="sort" id="sort" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm" onchange="this.form.submit()">
                            <option value="">Default (Nama A-Z)</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>
                </div>

                {{-- Filter NIM --}}
                <div class="md:col-span-4">
                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">Cari NIM</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="nim" id="nim" value="{{ request('nim') }}" placeholder="Masukkan NIM..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2">
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="md:col-span-2 flex space-x-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cari
                    </button>
                    <a href="{{ route('admin.datamahasiswa.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center" role="alert">
            <i class="fas fa-check-circle mr-2 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Data Table -->
        <div class="overflow-x-auto bg-white shadow-[4px_4px_10px_rgba(0,0,0,0.1)] rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#0d2149] text-white text-xs lg:text-sm uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            NIM
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            Nama Mahasiswa
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            Semester
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            IPK
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-left font-medium uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-3 py-2 lg:px-4 lg:py-3 text-center font-medium uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($mahasiswa as $index => $mhs)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 text-xs lg:text-sm">
                            {{-- No --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3 text-left text-gray-500 font-medium">
                                {{ $loop->iteration + ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() }}
                            </td>
                            
                            {{-- NIM --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3">
                                <span class="inline-block px-2 py-1 font-bold rounded-md ">
                                    {{ $mhs->nim }}
                                </span>
                            </td>
                            
                            {{-- Nama --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-800">{{ $mhs->nama }}</span>
                                </div>
                            </td>
                            
                            {{-- Semester --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3 text-center">
                                <span class="inline-flex items-center justify-center h-5 w-5 lg:h-6 lg:w-6 text-gray-600 text-[10px] lg:text-xs font-bold">
                                    {{ $mhs->semester }}
                                </span>
                            </td>

                            {{-- IPK --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3 text-left">
                                <span class="font-bold text-gray-700">{{ $mhs->ipk ?? '-' }}</span>
                            </td>
                            
                            {{-- Email --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3 text-gray-600">
                                {{ $mhs->email }}
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="px-3 py-2 lg:px-4 lg:py-3 text-center">
                                <div class="flex justify-center space-x-1 lg:space-x-2">
                                    {{-- View --}}
                                    <a href="{{ route('admin.datamahasiswa.show', $mhs->id_mahasiswa) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white" title="Detail">
                                        <i class="fas fa-eye mr-1 lg:mr-2"></i> Detail
                                    </a>
                                    
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.datamahasiswa.edit', $mhs->id_mahasiswa) }}" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-amber-100 text-amber-600 hover:bg-amber-600 hover:text-white" title="Edit">
                                        <i class="fas fa-pencil-alt mr-1 lg:mr-2"></i> Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.datamahasiswa.destroy', array_merge(['mahasiswa' => $mhs->id_mahasiswa], request()->query())) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-2 text-xs lg:text-sm font-medium rounded-md transition-colors duration-200 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white" title="Hapus">
                                            <i class="fas fa-trash mr-1 lg:mr-2"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-user-graduate text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium">Belum ada data mahasiswa</p>
                                    <p class="text-sm">Silakan tambahkan data mahasiswa baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($mahasiswa) && $mahasiswa->hasPages())
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50">
                {{ $mahasiswa->withQueryString()->links() }}
            </div>
        @endif
    

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="document.getElementById('importModal').classList.add('hidden')" style="background-color: rgba(17, 24, 39, 0.5);"></div>

            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-import text-green-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Import Data Mahasiswa
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Upload file Excel (.xlsx, .xls) yang berisi data mahasiswa.
                                    Pastikan format kolom: <strong>NIM, Nama, Semester, IPK, Email</strong>.
                                </p>
                                <form action="{{ route('admin.datamahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative hover:border-indigo-500 transition-colors cursor-pointer">
                                        <input id="file-upload" name="file" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required accept=".xlsx, .xls, .csv">
                                        <div class="space-y-1 text-center">
                                            <i id="upload-icon" class="fas fa-file-excel text-gray-400 text-3xl mb-2 transition-colors duration-200"></i>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <span class="relative bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                    <span>Upload a file</span>
                                                </span>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                XLSX, XLS up to 2MB
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-5 sm:mt-6 flex flex-row-reverse gap-2">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                            Import
                                        </button>
                                        <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm" onclick="document.getElementById('importModal').classList.add('hidden')">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('file-upload').addEventListener('change', function() {
        const icon = document.getElementById('upload-icon');
        if (this.files && this.files.length > 0) {
            icon.classList.remove('text-gray-400');
            icon.classList.add('text-green-500');
        } else {
            icon.classList.add('text-gray-400');
            icon.classList.remove('text-green-500');
        }
    });
</script>
@endsection