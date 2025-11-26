<x-app-layout>
    <!-- Slot Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-indigo-700 leading-tight tracking-wide">
            {{ __('Detail Pengaduan') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-gray-100 to-gray-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-sm shadow-lg sm:rounded-xl border border-gray-100">
                <div class="p-8">

                    <!-- Header Detail -->
                    <div class="border-b border-gray-300 pb-5 mb-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

                            <div>
                                <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">
                                    {{ $pengaduan->jenis_kasus }}
                                </p>

                                <h3 class="text-3xl font-bold text-gray-900 mt-1">
                                    {{ $pengaduan->judul }}
                                </h3>

                                <p class="mt-2 text-sm text-gray-600">
                                    Dilaporkan oleh: 
                                    <strong class="text-gray-800">{{ $pengaduan->user->nama }}</strong>
                                </p>
                            </div>

                            <div>
                                @if($pengaduan->status == 'Diproses')
                                    <span class="px-4 py-1 rounded-full bg-yellow-200 text-yellow-900 font-semibold shadow-sm">
                                        Diproses
                                    </span>
                                @elseif($pengaduan->status == 'Selesai')
                                    <span class="px-4 py-1 rounded-full bg-green-200 text-green-900 font-semibold shadow-sm">
                                        Selesai
                                    </span>
                                @elseif($pengaduan->status == 'Ditolak')
                                    <span class="px-4 py-1 rounded-full bg-red-200 text-red-900 font-semibold shadow-sm">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="px-4 py-1 rounded-full bg-blue-200 text-blue-900 font-semibold shadow-sm">
                                        Terkirim
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- KONTEN -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                        <!-- Kiri -->
                        <div class="md:col-span-2 space-y-8">

                            <!-- Deskripsi -->
                            <div>
                                <h4 class="text-xl font-semibold text-gray-800 mb-3">
                                    Deskripsi Kronologi
                                </h4>

                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-700 shadow-sm leading-relaxed">
                                    {!! nl2br(e($pengaduan->deskripsi)) !!}
                                </div>
                            </div>

                            <!-- Bukti Foto -->
                            <div>
                                <h4 class="text-xl font-semibold text-gray-800 mb-3">
                                    Bukti Foto / Lampiran
                                </h4>

                                @if ($pengaduan->gambar_bukti)
                                    <div class="group max-w-xs rounded-lg overflow-hidden shadow-md border border-gray-200 bg-white cursor-pointer"
                                         onclick="openModal('{{ asset('storage/' . $pengaduan->gambar_bukti) }}')">

                                        <img src="{{ asset('storage/' . $pengaduan->gambar_bukti) }}"
                                             class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                                    </div>

                                    <button onclick="openModal('{{ asset('storage/' . $pengaduan->gambar_bukti) }}')"
                                            class="mt-3 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        Lihat Ukuran Penuh
                                    </button>

                                @else
                                    <div class="flex items-center justify-center h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg">
                                        <span class="text-gray-500 italic">Tidak ada bukti lampiran</span>
                                    </div>
                                @endif
                            </div>

                        </div>

                        <!-- Kanan -->
                        <div class="md:col-span-1">
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Laporan</h4>

                                <dl class="space-y-3 text-gray-700">
                                    <div class="flex justify-between">
                                        <dt class="font-medium">ID Laporan:</dt>
                                        <dd>#{{ $pengaduan->id_pengaduan }}</dd>
                                    </div>

                                    <div class="flex justify-between">
                                        <dt class="font-medium">Tanggal Dibuat:</dt>
                                        <dd>{{ $pengaduan->created_at->format('d F Y, H:i') }}</dd>
                                    </div>

                                    <div class="flex justify-between">
                                        <dt class="font-medium">Status:</dt>
                                        <dd class="font-semibold">{{ $pengaduan->status }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                    </div>

                    <!-- Tombol -->
                    <div class="border-t border-gray-300 mt-10 pt-5 flex justify-end">
                        <a href="{{ url()->previous() }}"
                           class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg shadow-md transition">
                            Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL GAMBAR -->
    <div id="imgModal" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50">
        <div class="relative bg-white p-4 rounded-lg shadow-2xl max-w-4xl mx-auto animate-fadeIn">

            <!-- Close -->
            <button onclick="closeModal()" 
                class="absolute -top-4 -right-4 bg-red-600 text-white w-9 h-9 rounded-full shadow-lg text-lg font-bold">
                ✕
            </button>

            <img id="modalImage" src="" class="max-w-full max-h-[80vh] rounded-lg shadow">

            <div class="text-center mt-4">
                <button onclick="closeModal()" 
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md text-sm">
                    Kembali ke Halaman Detail
                </button>
            </div>

        </div>
    </div>

    <script>
        function openModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imgModal').classList.remove('hidden');
            document.getElementById('imgModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('imgModal').classList.add('hidden');
        }
    </script>

</x-app-layout>
