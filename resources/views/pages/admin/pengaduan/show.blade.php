@extends('layouts.admin')

@section('title', 'Detail Pengaduan')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Detail Pengaduan
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Lihat detail dan update status pengaduan.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.pengaduan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kembali
            </a>
        </div>
    </div>

    {{-- Alert/Pesan Sukses --}}
    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6 border-l-4 border-green-400">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Alert/Pesan Error --}}
    @if (session('error'))
        <div class="rounded-md bg-red-50 p-4 mb-6 border-l-4 border-red-400">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Error Validasi --}}
    @if ($errors->any())
        <div class="rounded-md bg-red-50 p-4 mb-6 border-l-4 border-red-400">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada inputan Anda:</h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Detail Pengaduan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] overflow-hidden sm:rounded-lg border border-gray-200">
                <div class="px-4 py-4 sm:px-6 flex justify-between items-center bg-gray-50 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $pengaduan->judul }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Diajukan pada {{ $pengaduan->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div>
                        @if ($pengaduan->status == 'Terkirim')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                Terkirim
                            </span>
                        @elseif ($pengaduan->status == 'Diproses')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Diproses
                            </span>
                        @elseif ($pengaduan->status == 'Selesai')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Selesai
                            </span>
                        @elseif ($pengaduan->status == 'Ditolak')
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Ditolak
                            </span>
                        @endif
                    </div>
                </div>
                <div class="px-4 py-4 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Jenis Kasus
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $pengaduan->jenis_kasus }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Deskripsi Lengkap
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-md border border-gray-100">
                                {!! nl2br(e($pengaduan->deskripsi)) !!}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">
                                Bukti Foto / Lampiran
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if ($pengaduan->gambar_bukti)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm max-w-lg">
                                        <img src="{{ asset('storage/' . $pengaduan->gambar_bukti) }}" alt="Bukti Pengaduan" class="w-full h-auto object-cover">
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $pengaduan->gambar_bukti) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            <i class="fas fa-external-link-alt mr-1"></i> Lihat Ukuran Penuh
                                        </a>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center h-32 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg">
                                        <span class="text-gray-500 italic">Tidak ada bukti lampiran</span>
                                    </div>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Info Pelapor & Aksi -->
        <div class="space-y-6">
            
            <!-- Informasi Pelapor -->
            <div class="bg-white shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] overflow-hidden sm:rounded-lg border border-gray-200">
                <div class="px-4 py-4 sm:px-6 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Informasi Pelapor
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if ($pengaduan->user)
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-12 w-12">
                                <img class="h-12 w-12 rounded-full object-cover border border-gray-200" 
                                     src="https://ui-avatars.com/api/?name=Anonymous&color=6B7280&background=E5E7EB" 
                                     alt="Anonymous"
                                     referrerpolicy="no-referrer">
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Pelapor (Disamarkan)</h4>
                                <p class="text-sm text-gray-500">
                                    Identitas Dirahasiakan
                                </p>
                            </div>
                        </div>
                            <div class="border-t border-gray-100 pt-4">
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900">Dirahasiakan</dd>
                                    </div>
                                    @if($pengaduan->no_telpon_dihubungi)
                                    <div>
                                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak (WhatsApp)</dt>
                                        <dd class="mt-1 text-sm font-bold text-indigo-700 font-mono">
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $pengaduan->no_telpon_dihubungi)) }}?text={{ urlencode('Halo, kami dari Admin Kampus ingin menindaklanjuti pengaduan Anda mengenai: "' . $pengaduan->judul . '".') }}" target="_blank" class="hover:underline">
                                                <i class="fab fa-whatsapp mr-1"></i> {{ $pengaduan->no_telpon_dihubungi }}
                                            </a>
                                        </dd>
                                    </div>
                                    @endif
                                    @if($pengaduan->mahasiswa)
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $pengaduan->mahasiswa->semester ?? '-' }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        @else
                            <div class="rounded-md bg-yellow-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Data pelapor tidak ditemukan (User terhapus)</h3>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- DISKUSI / TANGGAPAN -->
                <div class="bg-white shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] overflow-hidden sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-4 sm:px-3 bg-indigo-50 border-b border-indigo-100">
                        <h3 class="text-lg leading-6 font-medium text-indigo-900">
                            Diskusi & Tanggapan
                        </h3>
                    </div>
                    <div class="p-4">
                         <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 h-80 overflow-y-auto mb-4 custom-scrollbar flex flex-col space-y-4">
                            @forelse($pengaduan->tanggapan as $chat)
                                @php
                                    $isAdmin = !empty($chat->id_admin); 
                                @endphp
                                <div class="flex {{ $isAdmin ? 'justify-end' : 'justify-start' }}">
                                    <div class="flex items-end max-w-[85%] {{ $isAdmin ? 'flex-row-reverse' : 'flex-row' }}">
                                        <!-- Avatar -->
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full overflow-hidden border border-gray-300 {{ $isAdmin ? 'ml-2' : 'mr-2' }}">
                                            @if($isAdmin)
                                                <img src="https://ui-avatars.com/api/?name=Admin&background=4F46E5&color=fff" class="h-full w-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name=Anonymous&color=6B7280&background=E5E7EB" class="h-full w-full object-cover">
                                            @endif
                                        </div>

                                        <!-- Bubble -->
                                        <div class="px-4 py-2 rounded-lg shadow-sm text-sm {{ $isAdmin ? 'bg-indigo-100 text-gray-900 rounded-br-none' : 'bg-white text-gray-800 border border-gray-200 rounded-bl-none' }}">
                                            <p>{{ $chat->isi_tanggapan }}</p>
                                            <span class="text-[10px] {{ $isAdmin ? 'text-indigo-500' : 'text-gray-400' }} block mt-1 text-right">
                                                {{ $chat->created_at->format('d M H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                                    <i class="fas fa-comment-slash text-4xl mb-2"></i>
                                    <p class="text-sm">Belum ada tanggapan.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Form Balas -->
                        <form action="{{ route('admin.pengaduan.tanggapan', $pengaduan->id_pengaduan) }}" method="POST">
                            @csrf
                            <div class="flex gap-2">
                                <input type="text" name="isi_tanggapan" required placeholder="Tulis balasan sebagai Admin..." 
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm px-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-paper-plane mr-2"></i> Balas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Aksi Verifikasi -->
                <div class="bg-white shadow-[10px_10px_15px_-3px_rgba(0,0,0,0.1)] overflow-hidden sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-4 sm:px-3 bg-indigo-50 border-b border-indigo-100">
                        <h3 class="text-lg leading-6 font-medium text-indigo-900">
                            Aksi Verifikasi
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('admin.pengaduan.verifikasi', $pengaduan->id_pengaduan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
                            <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="Diproses" {{ $pengaduan->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Selesai" {{ $pengaduan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Ditolak" {{ $pengaduan->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
