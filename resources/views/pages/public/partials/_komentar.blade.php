{{-- 1. TAMPILKAN KOMENTAR INDUK --}}
<div class="flex space-x-4">
    <div class="flex-shrink-0">
        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-700 font-semibold">
            {{ substr($komen->nama_komentator, 0, 1) }}
        </span>
    </div>
    <div class="flex-1">
        <div class="flex items-baseline space-x-2">
            <span class="font-semibold text-gray-900">{{ $komen->nama_komentator }}</span>
            <span class="text-xs text-gray-500">{{ $komen->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-gray-700 mt-1">{{ $komen->isi }}</p>
        
        {{-- TOMBOL AKSI --}}
        <div class="flex items-center space-x-3 mt-2">
            
            {{-- Tombol Balas (Reply) --}}
            <button onclick="tampilkanFormBalas({{ $komen->id_komentar }}, {{ json_encode($komen->nama_komentator) }})" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                Balas
            </button>

            {{-- TOMBOL HAPUS (DELETE) - Hanya Tampil Jika User Memiliki Izin --}}
            {{-- Pemeriksaan izin menggunakan @can('destroy', $komen) berdasarkan KomentarPolicy --}}
            @can('destroy', $komen)
                <form action="{{ route('komentar.destroy', $komen->id_komentar) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini? Ini akan menghapus semua balasannya.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 focus:outline-none">
                        Hapus
                    </button>
                </form>
            @endcan

        </div>
        {{-- END TOMBOL AKSI --}}
    </div>
</div>

{{-- 2. CONTAINER UNTUK SEMUA BALASAN (Form + List) --}}
<div class="mt-4 pl-12"> {{-- 'pl-12' = padding-left (h-8 + space-x-4) --}}
    
    {{-- Form Balasan (tersembunyi) --}}
    <div id="form-balas-{{ $komen->id_komentar }}" class="hidden mb-6">
        <form action="{{ route('komentar.store', $berita->id_berita) }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $komen->id_komentar }}">
            <p class="text-sm text-gray-500 mb-2">
                Membalas kepada <span id="nama-parent-{{ $komen->id_komentar }}" class="font-semibold text-blue-600"></span>
            </p>
            @guest
            <input type="text" name="nama_komentator" placeholder="Nama Anda" class="w-full text-sm border-gray-300 rounded-md mb-2 shadow-sm" required>
            @endguest
            <textarea name="isi" rows="2" class="w-full text-sm border-gray-300 rounded-md shadow-sm" placeholder="Tulis balasan Anda..." required></textarea>
            <div class="text-right">
                <button type="submit" class="text-sm px-4 py-1 bg-blue-600 text-white rounded-md mt-2">Kirim Balasan</button>
            </div>
        </form>
    </div>

    @if ($komen->replies->count() > 0)
        <div class="space-y-6">
            @foreach ($komen->replies as $balasan)
                @include('pages.public.partials._komentar_nested', ['balasan' => $balasan])
            @endforeach
        </div>
    @endif
</div>  