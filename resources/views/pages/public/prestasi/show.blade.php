@extends('layouts.public')

{{-- Judul halaman diambil dari database --}}
@section('title', $berita->judul_berita)

@section('hero')
    {{-- MENGATUR TINGGI HERO KE 70% VIEWPORT (70vh) DAN FULL COVER --}}
    <section id="berita-hero" class="relative w-full overflow-hidden" style="height: 70vh;"> 
        
        {{-- WRAP GAMBAR DENGAN LINK YANG MEMANGGIL FUNGSI LIGHTBOX --}}
        <a href="javascript:void(0)" onclick="openLightbox('{{ asset('storage/' . $berita->gambar_berita) }}')" 
           class="absolute inset-0 block cursor-pointer transition duration-300 hover:opacity-90">
            
            {{-- KUNCI: object-cover untuk mengisi penuh area 70vh --}}
            <img src="{{ asset('storage/' . $berita->gambar_berita) }}" alt="{{ $berita->judul_berita }}" 
                 class="w-full h-full object-cover">
        </a>
             
        {{-- Overlay hitam transparan --}}
        <div class="absolute inset-0 bg-black bg-opacity-30 pointer-events-none"></div>
    </section>
@endsection

---

{{-- Konten Utama --}}
@section('content')

    {{-- KONTEN UTAMA DIBUAT DYNAMIC: transform -translate-y-24 mengangkat section ini 
       agar menimpa bagian bawah hero, menghilangkan kesan kaku --}}
    <section id="berita-detail" class="py-16 sm:py-20 bg-gray-50 relative transform -translate-y-24 z-10">
        <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Kolom Konten Utama (Prestasi) --}}
                <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-2xl shadow-xl"> 

                    {{-- Info Penulis --}}
                    <div class="flex items-center text-sm text-gray-500 mb-6">
                        {{-- Icon Penulis --}}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-5 h-5 mr-1.5">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z"
                                clip-rule="evenodd" />
                        </svg>
                        Oleh <strong class="ml-1">{{ $berita->user->name ?? 'Admin HIMA-TI' }}</strong>
                    </div>

                    <article class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        {!! $berita->isi_berita !!}
                    </article>

                    <hr class="my-10">

                    {{-- ============ BAGIAN KOMENTAR (DISESUAIKAN UNTUK PRESTASI) ============ --}}
                    {{-- Asumsi routing komentar tetap menggunakan 'berita' jika modelnya sama --}}
                    <div id="komentar" class="mt-12">
                        <h2 class="text-2xl font-bold text-blue-800 mb-6">Komentar ({{ $berita->komentar->count() }})</h2>

                        {{-- 1. Form Kirim Komentar (Induk) --}}
                        <form action="{{ route('komentar.store', $berita->id_berita) }}" method="POST" class="mb-8">
                            @csrf
                            @if (session('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4"
                                    role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="flex space-x-4">
                                <div class="flex-shrink-0">
                                    <span
                                        class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 text-gray-600 font-semibold">
                                        @auth {{ substr(Auth::user()->nama, 0, 1) }} @else ? @endauth
                                    </span>
                                </div>
                                <div class="flex-1">
                                    @guest
                                        <div class="mb-2">
                                            <label for="nama_komentator" class="sr-only">Nama Anda</label>
                                            <input type="text" name="nama_komentator" id="nama_komentator" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Tulis nama Anda..." value="{{ old('nama_komentator') }}">
                                            @error('nama_komentator') <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endguest
                                    <div>
                                        <label for="isi" class="sr-only">Komentar Anda</label>
                                        <textarea name="isi" id="isi" rows="3" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Tulis komentar Anda...">{{ old('isi') }}</textarea>
                                        @error('isi') <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="submit"
                                            class="inline-flex items-center px-6 py-2 bg-blue-700 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-800 transition">
                                            Kirim Komentar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- 2. Daftar Komentar yang Ada --}}
                        <div class="space-y-8">
                            @forelse ($komentar_induk as $komen)
                                @include('pages.public.partials._komentar', ['komen' => $komen])
                            @empty
                                <p class="text-gray-500 text-center pt-4">Belum ada komentar. Jadilah yang pertama!</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Kolom Sidebar (Prestasi Terkait) --}}
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 p-6 bg-white rounded-2xl shadow-xl">
                        <h3 class="text-xl font-bold text-blue-800 mb-4">Beberapa Prestasi yang mungkin anda tertarik</h3>
                        <div class="space-y-4">
                            @forelse ($beritaTerkait as $terkait)
                                <div class="flex space-x-3 group">
                                    <img src="{{ asset('storage/' . $terkait->gambar_berita) }}"
                                        alt="{{ $terkait->judul_berita }}"
                                        class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 group-hover:text-blue-700 leading-tight">
                                            {{-- ROUTE TELAH DIPERBAIKI KE PRESTASI.SHOW --}}
                                            <a href="{{ route('prestasi.show', $terkait->id_berita) }}">{{ Str::limit($terkait->judul_berita, 50) }}</a>
                                        </h4>
                                        <span class="text-xs text-gray-500">{{ $terkait->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">Tidak ada Prestasi terkait.</p>
                            @endforelse
                        </div>

                        {{-- Tombol Kembali --}}
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            {{-- ROUTE TELAH DIPERBAIKI KE PRESTASI.INDEX --}}
                            <a href="{{ route('prestasi.index') }}"
                                class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                                {{-- Icon Panah Kiri --}}
                                Lihat semua prestasi
                            </a>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

    {{-- ============ MODAL LIGHTBOX (GAMBAR UTUH + BLUR BACKDROP) ============ --}}
    <div id="lightbox-modal" class="fixed inset-0 bg-black bg-opacity-90 z-[9999] hidden items-center justify-center backdrop-blur-md" onclick="closeLightbox()">
        <div class="relative max-w-full max-h-full p-4" onclick="event.stopPropagation()">
            <img id="lightbox-image" src="" alt="Gambar Penuh" class="max-w-full max-h-screen object-contain">
            <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-3xl font-bold p-2 hover:text-gray-400">
                &times;
            </button>
        </div>
    </div>


    @push('scripts')
        <script>
            // FUNGSI JS INI TETAP DIPERLUKAN (Komentar)
            function tampilkanFormBalas(id, namaParent) {
                document.querySelectorAll('[id^="form-balas-"]').forEach(form => {
                    if (form.id !== 'form-balas-' + id) {
                        form.classList.add('hidden');
                    }
                });
                var form = document.getElementById('form-balas-' + id);
                form.classList.toggle('hidden');
                if (!form.classList.contains('hidden')) {
                    var spanNama = document.getElementById('nama-parent-' + id);
                    spanNama.textContent = '@' + namaParent;
                }
            }
            
            // FUNGSI JAVASCRIPT UNTUK LIGHTBOX
            function openLightbox(imageUrl) {
                document.getElementById('lightbox-image').src = imageUrl;
                document.getElementById('lightbox-modal').classList.remove('hidden');
                document.getElementById('lightbox-modal').classList.add('flex');
                document.body.style.overflow = 'hidden'; // Mencegah scrolling
            }

            function closeLightbox() {
                document.getElementById('lightbox-modal').classList.add('hidden');
                document.getElementById('lightbox-modal').classList.remove('flex');
                document.body.style.overflow = 'auto'; // Mengizinkan scrolling lagi
            }
        </script>
    @endpush
@endsection