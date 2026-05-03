{{-- File: resources/views/pages/public/prestasi/_komentar_balasan.blade.php --}}

<div class="flex space-x-4">
    <div class="flex-shrink-0">
        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-gray-700 font-semibold">
            {{ substr($balasan->nama_komentator, 0, 1) }}
        </span>
    </div>
    <div class="flex-1">
        <div class="flex items-baseline space-x-2 flex-wrap">
            <span class="font-semibold text-gray-900">{{ $balasan->nama_komentator }}</span>
            @if($balasan->parent)
                <span class="text-xs text-gray-500 mx-1">membalas</span>
                <span class="font-semibold text-blue-600 text-sm">{{ $balasan->parent->nama_komentator }}</span>
            @endif
            <span class="text-xs text-gray-400 ml-2">{{ $balasan->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-gray-700 mt-1">
            {{ $balasan->isi }}
        </p>
        <button onclick="tampilkanFormBalas({{ $komen->id_komentar }}, {{ json_encode($balasan->nama_komentator) }})" class="text-sm font-semibold text-blue-600 hover:text-blue-800 mt-2">
            Balas
        </button>
    </div>
</div>


