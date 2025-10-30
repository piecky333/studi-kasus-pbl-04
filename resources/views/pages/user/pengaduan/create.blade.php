{{-- Menggunakan layout utama aplikasi (app.blade.php) --}}
<x-app-layout>
    <!-- Slot Header (Judul Halaman) -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pengaduan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">

                    <!-- Kolom Kiri: Informasi & Panduan -->
                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium text-gray-900">Formulir Pengaduan</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Silakan isi formulir di samping dengan data yang benar dan jelas. Setiap laporan yang masuk akan kami proses dengan kerahasiaan yang terjamin.
                        </p>
                        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 text-blue-700 rounded-r-lg">
                            <p class="font-bold">Panduan Pengisian:</p>
                            <ul class="list-disc list-inside mt-2 text-sm space-y-1">
                                <li>Gunakan judul yang singkat dan jelas.</li>
                                <li>Pilih jenis kasus yang paling sesuai.</li>
                                <li>Jelaskan kronologi kejadian secara detail pada deskripsi.</li>
                                <li>Pastikan tidak ada informasi pribadi yang sensitif.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Formulir Input -->
                    <div class="md:col-span-2">
                        
                        <!-- Menampilkan Error Validasi di Atas -->
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('user.pengaduan.store') }}" method="POST">
                            @csrf
                            
                            <!-- Input Judul Pengaduan (Baru) -->
                            <div>
                                <x-input-label for="judul" value="Judul Pengaduan" />
                                <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" 
                                              :value="old('judul')" required autofocus 
                                              placeholder="Contoh: Laporan Kehilangan KTM" />
                            </div>

                            <!-- Input Jenis Kasus -->
                            <div class="mt-4">
                                <x-input-label for="jenis_kasus" value="Jenis Kasus" />
                                <select id="jenis_kasus" name="jenis_kasus" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Pilih Jenis Kasus...</option>
                                    <option value="Pelanggaran Etika" @if(old('jenis_kasus') == 'Pelanggaran Etika') selected @endif>Pelanggaran Etika</option>
                                    <option value="Kekerasan Fisik" @if(old('jenis_kasus') == 'Kekerasan Fisik') selected @endif>Kekerasan Fisik</option>
                                    <option value="Kekerasan Verbal" @if(old('jenis_kasus') == 'Kekerasan Verbal') selected @endif>Kekerasan Verbal</option>
                                    <option value="Fasilitas Kampus" @if(old('jenis_kasus') == 'Fasilitas Kampus') selected @endif>Fasilitas Kampus</option>
                                    <option value="Lainnya" @if(old('jenis_kasus') == 'Lainnya') selected @endif>Lainnya</option>
                                </select>
                            </div>

                            <!-- Input Deskripsi -->
                            <div class="mt-4">
                                <x-input-label for="deskripsi" value="Deskripsi Kejadian" />
                                <textarea id="deskripsi" name="deskripsi" rows="6" 
                                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                          placeholder="Tuliskan kronologi kejadian, waktu, tempat, dan pihak yang terlibat secara rinci...">{{ old('deskripsi') }}</textarea>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex items-center justify-end mt-6 ">
                                <a href="{{ route('user.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 underline mr-4 mx-6 px-8 py-2 rounded">
                                    Batal
                                </a>

                                <x-primary-button>
                                    {{ __('Kirim Pengaduan') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
