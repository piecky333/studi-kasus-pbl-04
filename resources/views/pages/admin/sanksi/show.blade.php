@extends('layouts.admin')

@section('title', 'Detail Sanksi')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-6xl">

        {{-- Breadcrumb & Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li><a href="{{ route('admin.dashboard') }}"
                                class="hover:text-indigo-600 transition-colors">Dashboard</a></li>
                        <li><i class="fas fa-chevron-right text-gray-300 text-xs"></i></li>
                        <li><a href="{{ route('admin.sanksi.index') }}"
                                class="hover:text-indigo-600 transition-colors">Sanksi</a></li>
                        <li><i class="fas fa-chevron-right text-gray-300 text-xs"></i></li>
                        <li class="text-gray-900 font-medium">Detail</li>
                    </ol>
                </nav>
                <h2 class="text-2xl font-bold text-gray-900">Detail Sanksi Mahasiswa</h2>
                <p class="text-sm text-gray-500 mt-1">Informasi lengkap mengenai sanksi yang diberikan.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() == url()->current() ? route('admin.sanksi.index') : url()->previous() }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-all">
                    <i class="fas fa-arrow-left mr-2 text-gray-400"></i> Kembali
                </a>
                <a href="{{ route('admin.sanksi.edit', $sanksi) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all">
                    <i class="fas fa-edit mr-2"></i> Edit Data
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== KOLOM KIRI: Profil Mahasiswa ===== --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Profil Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Banner --}}
                    <div class="h-28 bg-gradient-to-br from-[#0d2149] to-[#1a3a75] relative">
                        <div class="absolute -bottom-10 left-1/2 -translate-x-1/2">
                            <img class="h-20 w-20 rounded-full border-4 border-white object-cover shadow-md"
                                src="{{ $sanksi->mahasiswa->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($sanksi->mahasiswa->nama ?? 'M') . '&color=7F9CF5&background=EBF4FF&size=128' }}"
                                alt="Foto {{ $sanksi->mahasiswa->nama ?? 'Mahasiswa' }}">
                        </div>
                    </div>

                    {{-- Nama & NIM --}}
                    <div class="pt-14 pb-6 px-6 text-center">
                        <h3 class="text-lg font-bold text-gray-900 leading-tight">
                            {{ $sanksi->mahasiswa->nama ?? 'Mahasiswa Dihapus' }}
                        </h3>
                        <p class="text-sm text-indigo-600 font-semibold mt-1">
                            {{ $sanksi->mahasiswa->nim ?? '-' }}
                        </p>

                        {{-- Stats Grid --}}
                        <div class="mt-5 grid grid-cols-2 gap-3 border-t border-gray-100 pt-5">
                            <div class="bg-gray-50 rounded-xl p-3 text-center">
                                <span
                                    class="block text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">Semester</span>
                                <span
                                    class="block text-xl font-bold text-gray-800">{{ $sanksi->mahasiswa->semester ?? '-' }}</span>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 text-center">
                                <span
                                    class="block text-xs text-gray-400 font-semibold uppercase tracking-wide mb-1">IPK</span>
                                <span
                                    class="block text-xl font-bold text-gray-800">{{ number_format($sanksi->mahasiswa->ipk ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tingkat Pelanggaran Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Tingkat Pelanggaran</h4>

                    @php
                        $severityConfig = match ($sanksi->jenis_sanksi) {
                            'Berat' => ['bg' => 'bg-red-50 border-red-200', 'text' => 'text-red-700', 'icon' => '🔴', 'badge' => 'bg-red-100 text-red-800 border-red-200'],
                            'Sedang' => ['bg' => 'bg-yellow-50 border-yellow-200', 'text' => 'text-yellow-700', 'icon' => '🟡', 'badge' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
                            default => ['bg' => 'bg-green-50 border-green-200', 'text' => 'text-green-700', 'icon' => '🟢', 'badge' => 'bg-green-100 text-green-800 border-green-200'],
                        };
                    @endphp

                    <div
                        class="flex flex-col items-center justify-center p-6 rounded-xl border {{ $severityConfig['bg'] }}">
                        <span class="text-4xl mb-3">{{ $severityConfig['icon'] }}</span>
                        <span class="text-2xl font-extrabold {{ $severityConfig['text'] }}">
                            {{ $sanksi->jenis_sanksi }}
                        </span>
                        <span class="mt-2 text-xs font-medium px-3 py-1 rounded-full border {{ $severityConfig['badge'] }}">
                            Kategori Pelanggaran
                        </span>
                    </div>
                </div>

                {{-- Metadata Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Catatan</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-clock text-gray-300 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <span class="text-gray-400 text-xs block">Dicatat pada</span>
                                <span
                                    class="text-gray-700 font-medium">{{ $sanksi->created_at->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-history text-gray-300 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <span class="text-gray-400 text-xs block">Terakhir diperbarui</span>
                                <span
                                    class="text-gray-700 font-medium">{{ $sanksi->updated_at->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== KOLOM KANAN: Detail Sanksi ===== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Rincian Hukuman Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <i class="fas fa-gavel text-indigo-600 text-sm"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Rincian Hukuman</h3>
                    </div>

                    <div class="p-6 space-y-5">

                        {{-- Jenis Hukuman + Tanggal (2 kolom) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="fas fa-balance-scale mr-1.5 text-indigo-400"></i>Jenis Hukuman
                                </label>
                                <div
                                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 font-semibold text-sm">
                                    {{ $sanksi->jenis_hukuman ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                    <i class="fas fa-calendar-alt mr-1.5 text-indigo-400"></i>Tanggal Diberikan
                                </label>
                                <div
                                    class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 font-semibold text-sm">
                                    {{ $sanksi->tanggal_sanksi
        ? \Carbon\Carbon::parse($sanksi->tanggal_sanksi)->translatedFormat('d F Y')
        : '-' }}
                                </div>
                            </div>
                        </div>

                        {{-- Keterangan / Kronologi --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                <i class="fas fa-align-left mr-1.5 text-indigo-400"></i>Keterangan / Kronologi
                            </label>
                            <div
                                class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 text-gray-700 text-sm leading-relaxed min-h-[80px]">
                                @if($sanksi->keterangan)
                                    {!! nl2br(e($sanksi->keterangan)) !!}
                                @else
                                    <span class="text-gray-400 italic">Tidak ada keterangan tambahan yang dilampirkan.</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Bukti / File Pendukung Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fas fa-paperclip text-blue-600 text-sm"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">Bukti / Berkas Pendukung</h3>
                    </div>

                    <div class="p-6">
                        @if($sanksi->file_pendukung)
                            @php
                                $ext = strtolower(pathinfo($sanksi->file_pendukung, PATHINFO_EXTENSION));
                                $icon = match (true) {
                                    in_array($ext, ['pdf']) => ['class' => 'fa-file-pdf', 'color' => 'text-red-500'],
                                    in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) => ['class' => 'fa-file-image', 'color' => 'text-purple-500'],
                                    in_array($ext, ['doc', 'docx']) => ['class' => 'fa-file-word', 'color' => 'text-blue-500'],
                                    default => ['class' => 'fa-file', 'color' => 'text-gray-400'],
                                };
                            @endphp
                            <a href="{{ asset('storage/' . $sanksi->file_pendukung) }}" target="_blank"
                                class="flex items-center justify-between p-4 bg-blue-50 hover:bg-blue-100 border border-blue-100 rounded-xl transition-colors group">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="flex-shrink-0">
                                        <i
                                            class="fas {{ $icon['class'] }} {{ $icon['color'] }} text-3xl group-hover:scale-110 transition-transform"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-blue-900 truncate">
                                            {{ basename($sanksi->file_pendukung) }}
                                        </p>
                                        <p class="text-xs text-blue-500 uppercase font-bold mt-0.5">{{ strtoupper($ext) }}</p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ml-4">
                                    <a href="{{ asset('storage/' . $sanksi->file_pendukung) }}" download
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-lg transition-colors"
                                        onclick="event.stopPropagation()">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                </div>
                            </a>
                        @else
                            <div
                                class="flex flex-col items-center justify-center py-10 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                                <i class="fas fa-folder-open text-gray-300 text-4xl mb-3"></i>
                                <p class="text-sm font-medium text-gray-400">Tidak ada berkas pendukung yang dilampirkan.</p>
                            </div>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection