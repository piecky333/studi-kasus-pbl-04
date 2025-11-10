{{-- File: resources/views/pages/public/prestasi/_komentar_balasan.blade.php --}}

<div class="flex space-x-4">
    <div class="flex-shrink-0">
        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-700 font-semibold">
            {{ substr($balasan->nama_komentator, 0, 1) }}
        </span>
    </div>
    <div class="flex-1">
        <div class="flex items-baseline space-x-2">
            <span class="font-semibold text-gray-900">{{ $balasan->nama_komentator }}</span>
            <span class="text-xs text-gray-500">{{ $balasan->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-gray-700 mt-1">
            {{-- 
              Tampilkan "@NamaInduk" jika ini adalah balasan dari balasan.
              Ini meniru gaya YouTube.
            --}}
            @if($balasan->parent && $balasan->parent->parent_id != null)
                <a href="#komentar-{{ $balasan->parent->id_komentar }}" class="font-semibold text-blue-600 hover:underline">
                    {{ '@' . $balasan->parent->nama_komentator }}
                </a> 
            @endif
            {{ $balasan->isi }}
        </p>
        <button onclick="tampilkanFormBalas({{ $komen->id_komentar }}, {{ json_encode($balasan->nama_komentator) }})" class="text-sm font-semibold text-blue-600 hover:text-blue-800 mt-2">
            Balas
        </button>
    </div>
</div>