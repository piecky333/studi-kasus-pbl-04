@extends('layouts.pengurus')

@section('title', 'Edit Pengurus')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Edit Pengurus
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi pengurus dan divisinya.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('pengurus.pengurus.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] overflow-hidden sm:rounded-lg border border-gray-200 max-w-3xl mx-auto">
        <div class="px-4 py-4 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Formulir Edit Pengurus
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Perbarui detail pengurus di bawah ini.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-6 border-l-4 border-red-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Terdapat beberapa kesalahan pada pengisian formulir:
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('pengurus.pengurus.update', $pengurus->id_pengurus) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    
                    {{-- Pilih Divisi --}}
                    <div class="sm:col-span-6">
                        <label for="id_divisi" class="block text-sm font-medium text-gray-700">
                            Divisi <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select name="id_divisi" id="id_divisi" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('id_divisi') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2">
                                @foreach($divisi as $d)
                                    <option value="{{ $d->id_divisi }}" {{ old('id_divisi', $pengurus->id_divisi) == $d->id_divisi ? 'selected' : '' }}>
                                        {{ $d->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_divisi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pilih Mahasiswa (Alpine.js Custom) --}}
                    <div class="sm:col-span-6" x-data="studentSelect()">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Hidden Input for Form Submission -->
                        <input type="hidden" name="id_user" :value="selectedStudent ? selectedStudent.id_user : ''">

                        <!-- Search Input -->
                        <div class="relative">
                            <input 
                                type="text" 
                                x-model="search" 
                                @input="isOpen = true"
                                @click.away="isOpen = false"
                                @focus="isOpen = true"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out"
                                placeholder="Ketik nama atau NIM mahasiswa..."
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Dropdown List -->
                        <div 
                            x-show="isOpen && filteredStudents.length > 0" 
                            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                            style="display: none;"
                        >
                            <template x-for="student in filteredStudents" :key="student.id_user">
                                <div 
                                    @click="selectStudent(student)"
                                    class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-600 hover:text-white group"
                                >
                                    <div class="flex items-center">
                                        <span class="font-normal block truncate" x-text="student.nama + ' (' + student.nim + ')'"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Selected Tag -->
                        <div x-show="selectedStudent" class="mt-2" style="display: none;">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <span x-text="selectedStudent.nama + ' (' + selectedStudent.nim + ')'"></span>
                                <button type="button" @click="selectedStudent = null" class="flex-shrink-0 ml-1.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-blue-600 hover:bg-blue-200 hover:text-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white">
                                    <span class="sr-only">Remove</span>
                                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                                    </svg>
                                </button>
                            </span>
                        </div>
                        
                        @error('id_user')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <script>
                        function studentSelect() {
                            return {
                                search: '',
                                isOpen: false,
                                selectedStudent: null,
                                students: @json($mahasiswa),
                                get filteredStudents() {
                                    if (this.search === '') {
                                        return this.students;
                                    }
                                    return this.students.filter(student => {
                                        return student.nama.toLowerCase().includes(this.search.toLowerCase()) || 
                                            student.nim.includes(this.search);
                                    });
                                },
                                selectStudent(student) {
                                    this.selectedStudent = student;
                                    this.search = '';
                                    this.isOpen = false;
                                },
                                init() {
                                    // Pre-select existing student
                                    const currentId = "{{ old('id_user', $pengurus->id_user) }}";
                                    if (currentId) {
                                        this.selectedStudent = this.students.find(s => s.id_user == currentId);
                                    }
                                }
                            }
                        }
                    </script>

                    {{-- Pilih Jabatan --}}
                    <div class="sm:col-span-6">
                        <label for="id_jabatan" class="block text-sm font-medium text-gray-700">
                            Posisi Jabatan <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <select name="id_jabatan" id="id_jabatan" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('id_jabatan') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror px-3 py-2">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatan as $j)
                                    <option value="{{ $j->id_jabatan }}" {{ old('id_jabatan', $pengurus->id_jabatan) == $j->id_jabatan ? 'selected' : '' }}>
                                        {{ $j->nama_jabatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_jabatan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // No dependent dropdown needed
    });
</script>
@endsection
