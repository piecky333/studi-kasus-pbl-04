{{-- Halaman ini "memakai" layout publik, sama seperti welcome.blade.php --}}
@extends('layouts.public')

@section('title', 'Berita & Kegiatan - HIMA-TI')

@section('hero')
    <section id="berita-hero" class="flex items-center justify-center text-center text-white" 
             style="height: 90vh; background: linear-gradient(135deg, #002855 60%, #004080);">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-bold text-4xl sm:text-5xl mb-3">Berita & Kegiatan HIMA-TI</h1>
            <p class="text-lg sm:text-xl text-gray-200 mb-4 max-w-3xl mx-auto">
                Update kegiatan, informasi, dan pengumuman terbaru dari Himpunan Mahasiswa Teknologi Informasi Politala.
            </p>
        </div>
    </section>
@endsection


{{-- Ini adalah konten utama halaman daftar berita --}}
@section('content')
    <section id="daftar-berita" class="py-16 sm:py-20 bg-gray-50">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl font-bold text-blue-800 text-center mb-4">Berita Terbaru</h2>
            <p class="text-gray-600 text-center mb-10 max-w-2xl mx-auto">Ikuti kegiatan terbaru kami untuk terus terhubung dengan berbagai program dan prestasi mahasiswa.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- 
                  Kita ganti konten statis Anda dengan loop dinamis 
                  dari BeritaController (yang mengirim $daftarKegiatan) 
                --}}
                @forelse($daftarKegiatan as $kegiatan)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col wow fadeInUp">
                        <a href="{{ route('berita.show', $kegiatan->id_berita) }}">
                            <img src="{{ asset('storage/' . $kegiatan->gambar_berita) }}" alt="{{ $kegiatan->judul_berita }}"
                                 class="w-full h-48 object-cover">
                        </a>
                        
                        {{-- 
                          Gunakan 'flex flex-col flex-grow' agar 
                          semua card memiliki tinggi yang sama 
                        --}}
                        <div class="p-6 flex flex-col flex-grow">
                            <span class="text-blue-600 text-sm font-semibold">{{ $kegiatan->kategori ?? 'Kegiatan' }}</span>
                            <h3 class="font-bold text-lg text-gray-900 my-2 hover:text-blue-700">
                                <a href="{{ route('berita.show', $kegiatan->id_berita) }}">
                                    {{ Str::limit($kegiatan->judul_berita, 50) }}
                                </a>
                            </h3>
                            
                            {{-- Gunakan 'flex-grow' agar paragraf mengisi ruang --}}
                            <p class="text-gray-600 text-sm mb-4 flex-grow">
                                {{ Str::limit(strip_tags($kegiatan->isi_berita), 100) }}
                            </p>
                            
                            {{-- 'mt-auto' akan mendorong tanggal ke bagian bawah card --}}
                            <div class="flex items-center text-xs text-gray-400 mt-auto">
                                {{-- Icon Kalender SVG (Pengganti Bootstrap Icon 'bi') --}}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5">
                                  <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5h10.5a.75.75 0 0 0 0-1.5H4.75a.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $kegiatan->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="md:col-span-3 text-gray-500 text-center">Belum ada berita kegiatan.</p>
                @endforelse

            </div>

            {{-- Tampilkan link pagination (Halaman 1, 2, 3, dst.) --}}
            <div class="mt-12">
                {{ $daftarKegiatan->links() }}
            </div>
        </div>
    </section>
@endsection