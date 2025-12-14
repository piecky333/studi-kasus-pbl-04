@extends('layouts.admin')

@section('title', 'Detail Sanksi')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 space-y-4 md:space-y-0">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-gray-500 text-sm">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a></li>
                    <li><span class="text-gray-300">/</span></li>
                    <li><a href="{{ route('admin.sanksi.index') }}" class="hover:text-indigo-600 transition">Sanksi</a></li>
                    <li><span class="text-gray-300">/</span></li>
                    <li class="text-gray-900 font-medium" aria-current="page">Detail</li>
                </ol>
            </nav>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                Detail Sanksi Mahasiswa
            </h2>
        </div>
        <div class="flex space-x-3">
            <a href="{{ url()->previous() == url()->current() ? route('admin.sanksi.index') : url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                Kembali
            </a>
            <a href="{{ route('admin.sanksi.edit', $sanksi->id_sanksi) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <i class="fas fa-edit mr-2"></i> Edit Data
            </a>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Left Column: Student Profile Card -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 h-32 relative">
                    <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2">
                        <img class="h-24 w-24 rounded-full border-4 border-white bg-white" 
                             src="{{ $sanksi->mahasiswa->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($sanksi->mahasiswa->nama ?? 'M') . '&color=7F9CF5&background=EBF4FF&size=128' }}" 
                             alt="Student Avatar">
                    </div>
                </div>
                <div class="pt-16 pb-6 px-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $sanksi->mahasiswa->nama ?? 'Mahasiswa Dihapus' }}
                    </h3>
                    <p class="text-sm text-gray-500 font-medium mt-1">
                        {{ $sanksi->mahasiswa->nim ?? '-' }}
                    </p>
                    
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-gray-50 rounded-xl">
                                <span class="block text-xs uppercase text-gray-400 font-semibold tracking-wide">Semester</span>
                                <span class="block text-lg font-bold text-gray-800">{{ $sanksi->mahasiswa->semester ?? '-' }}</span>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-xl">
                                <span class="block text-xs uppercase text-gray-400 font-semibold tracking-wide">Prodi</span>
                                <span class="block text-lg font-bold text-gray-800">TI</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Severity Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-6 p-6">
                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-widest mb-4">Tingkat Pelanggaran</h4>
                <div class="flex items-center justify-center p-6 rounded-xl 
                    {{ $sanksi->jenis_sanksi == 'Berat' ? 'bg-red-50 border border-red-100' : 
                       ($sanksi->jenis_sanksi == 'Sedang' ? 'bg-yellow-50 border border-yellow-100' : 'bg-green-50 border border-green-100') }}">
                    <div class="text-center">
                        <span class="block text-4xl mb-2">
                             @if($sanksi->jenis_sanksi == 'Berat') 🔴 
                             @elseif($sanksi->jenis_sanksi == 'Sedang') 🟡 
                             @else 🟢 
                             @endif
                        </span>
                        <span class="text-2xl font-bold 
                            {{ $sanksi->jenis_sanksi == 'Berat' ? 'text-red-700' : 
                               ($sanksi->jenis_sanksi == 'Sedang' ? 'text-yellow-700' : 'text-green-700') }}">
                            {{ $sanksi->jenis_sanksi }}
                        </span>
                        <p class="text-sm mt-1 opacity-75">Kategori Pelanggaran</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sanction Details -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 h-full">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-gavel text-indigo-500 mr-2"></i> Rincian Hukuman
                    </h3>
                </div>
                
                <div class="p-8">
                    <div class="space-y-8">
                        
                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">
                                    Jenis Hukuman
                                </label>
                                <div class="text-lg font-medium text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border border-gray-200 group-hover:border-indigo-300 transition-colors">
                                    {{ $sanksi->jenis_hukuman }}
                                </div>
                            </div>
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">
                                    Tanggal Diberikan
                                </label>
                                <div class="text-lg font-medium text-gray-900 bg-gray-50 px-4 py-3 rounded-lg border border-gray-200 group-hover:border-indigo-300 transition-colors flex items-center">
                                    <i class="far fa-calendar-alt text-gray-400 mr-3"></i>
                                    {{ $sanksi->tanggal_sanksi ? \Carbon\Carbon::parse($sanksi->tanggal_sanksi)->isoFormat('dddd, D MMMM Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">
                                Keterangan / Kronologi
                            </label>
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 prose max-w-none text-gray-800 leading-relaxed">
                                @if($sanksi->keterangan)
                                    {!! nl2br(e($sanksi->keterangan)) !!}
                                @else
                                    <span class="text-gray-400 italic">Tidak ada keterangan tambahan yang dilampirkan.</span>
                                @endif
                            </div>
                        </div>

                        <!-- Bukti / File Pendukung -->
                        @if($sanksi->file_pendukung)
                            <div>
                                <label class="block text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">
                                    Bukti / File Pendukung
                                </label>
                                
                                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    @php
                                        $ext = pathinfo($sanksi->file_pendukung, PATHINFO_EXTENSION);
                                        $icon = 'fa-file';
                                        $color = 'text-gray-500';
                                        
                                        if (in_array(strtolower($ext), ['pdf'])) {
                                            $icon = 'fa-file-pdf';
                                            $color = 'text-red-500';
                                        } elseif (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                            $icon = 'fa-file-image';
                                            $color = 'text-purple-600';
                                        }
                                    @endphp

                                    {{-- Icon & Filename --}}
                                    <a href="{{ asset('storage/' . $sanksi->file_pendukung) }}" target="_blank" class="flex items-center flex-1 min-w-0 group cursor-pointer text-decoration-none">
                                        <div class="flex-shrink-0 mr-3">
                                            <i class="fas {{ $icon }} {{ $color }} text-3xl group-hover:scale-110 transition-transform"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-blue-900 truncate group-hover:text-blue-700 transition-colors">
                                                {{ basename($sanksi->file_pendukung) }}
                                            </p>
                                        </div>
                                    </a>

                                    {{-- Download Action --}}
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ asset('storage/' . $sanksi->file_pendukung) }}" download class="text-sm font-bold text-green-600 hover:text-green-800 transition-colors">
                                            Unduh
                                        </a>
                                    </div>
                                </div>

                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 border-dashed rounded-xl p-6 text-center">
                                <i class="fas fa-file-excel text-gray-300 text-3xl mb-2"></i>
                                <p class="text-sm text-gray-400 font-medium">Tidak ada bukti / file pendukung yang dilampirkan.</p>
                            </div>
                        @endif

                        <!-- Footer Info -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-100 text-xs text-gray-500">
                            <span>Dibuat pada: {{ $sanksi->created_at->format('d M Y, H:i') }}</span>
                            <span>Terakhir diupdate: {{ $sanksi->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
