{{-- Halaman ini "memakai" layout publik (yang sudah Tailwind) --}}
@extends('layouts.public')

{{-- Judul halaman diubah menjadi Prestasi --}}
@section('title', 'Prestasi Mahasiswa - HIMA-TI Politala')

{{-- HERO SECTION BARU (Copywriting berfokus pada Prestasi) --}}
@section('hero')
    <section id="prestasi-hero" class="flex items-center justify-center text-center text-white" 
             style="height: 90vh; background: linear-gradient(135deg, #002855 60%, #004080);">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-bold text-4xl sm:text-5xl mb-3">Capaian & Prestasi Mahasiswa</h1>
            <p class="text-lg sm:text-xl text-gray-200 mb-4 max-w-3xl mx-auto">
                Kami bangga membagikan pencapaian luar biasa dari mahasiswa Teknologi Informasi Politala. Temukan inspirasi dari dedikasi dan kerja keras mereka.
            </p>
        </div>
    </section>
@endsection


{{-- Konten utama halaman daftar prestasi (Menggunakan Tailwind) --}}
@section('content')
    <section id="daftar-prestasi" class="py-16 sm:py-20 bg-gray-50">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-blue-800 text-center mb-4">Galeri Prestasi</h2>
            <p class="text-gray-600 text-center mb-10 max-w-2xl mx-auto">
                Menampilkan karya, penghargaan, dan pencapaian mahasiswa kami di berbagai bidang, baik akademik maupun non-akademik.
            </p>

            {{-- Menggunakan struktur Grid dan Card Tailwind --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- looping setiap berita jenis prestasi --}}
                @forelse($semuaPrestasi as $item)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                        
                        {{-- Link dan Gambar --}}
                        <a href="{{ route('berita.show', $item->id_berita) }}">
                            @if ($item->gambar_berita)
                                <img src="{{ asset('storage/' . $item->gambar_berita) }}" alt="{{ $item->judul_berita }}"
                                     class="w-full h-48 object-cover">
                            @else
                                {{-- Fallback jika tidak ada gambar --}}
                                <img src="https://via.placeholder.com/400x220.png?text=Prestasi" alt="Placeholder Prestasi"
                                     class="w-full h-48 object-cover">
                            @endif
                        </a>
                        
                        {{-- Card Body (flex-grow membuat card sama tinggi) --}}
                        <div class="p-6 flex flex-col flex-grow">
                            
                            {{-- Judul Berita --}}
                            <h3 class="font-bold text-lg text-gray-900 my-2 hover:text-blue-700">
                                <a href="{{ route('berita.show', $item->id_berita) }}">
                                    {{ Str::limit($item->judul_berita, 50) }}
                                </a>
                            </h3>
                            
                            {{-- Deskripsi/Isi (flex-grow mengisi ruang) --}}
                            <p class="text-gray-600 text-sm mb-4 flex-grow">
                                {{ Str::limit(strip_tags($item->isi_berita), 100) }}
                            </p>
                            
                            {{-- Footer Card (mt-auto mendorong ke bawah) --}}
                            <div class="flex items-center text-xs text-gray-400 mt-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5">
                                  <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5h10.5a.75.75 0 0 0 0-1.5H4.75a.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $item->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika tidak ada data prestasi --}}
                    <p class="md:col-span-3 text-gray-500 text-center">Belum ada prestasi yang dipublikasikan.</p>
                @endforelse

            </div>

            {{-- Link Paginasi (dari controller) --}}
            <div class="mt-12">
                {{ $semuaPrestasi->links() }}
            </div>
        </div>
    </section>
@endsection