{{-- Menggunakan layout utama aplikasi (app.blade.php) --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pengaduan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-3 gap-8">

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
                                <li>
                                    <strong class="text-indigo-700">Tips AI:</strong> Tulis saja seadanya, lalu klik tombol "✨ Perbaiki Tulisan" agar lebih formal.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        
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

                        <form action="{{ route('user.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div>
                                <x-input-label for="judul" value="Judul Pengaduan" />
                                <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" 
                                              :value="old('judul')" required autofocus 
                                              placeholder="Contoh: Laporan Kehilangan KTM" />
                                @error('judul')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <x-input-label for="jenis_kasus" value="Jenis Kasus" />
                                <select id="jenis_kasus" name="jenis_kasus" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Pilih Jenis Kasus...</option>
                                    <option value="Pelanggaran Etika" @if(old('jenis_kasus') == 'Pelanggaran Etika') selected @endif>Pelanggaran Etika</option>
                                    <option value="Kekerasan Fisik" @if(old('jenis_kasus') == 'Kekerasan Fisik') selected @endif>Kekerasan Fisik</option>
                                    <option value="Kekerasan Verbal" @if(old('jenis_kasus') == 'Kekerasan Verbal') selected @endif>Kekerasan Verbal</option>
                                    <option value="Fasilitas Kampus" @if(old('jenis_kasus') == 'Fasilitas Kampus') selected @endif>Fasilitas Kampus</option>
                                    <option value="Lainnya" @if(old('jenis_kasus') == 'Lainnya') selected @endif>Lainnya</option>
                                </select>
                                @error('jenis_kasus')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <x-input-label for="deskripsi" value="Deskripsi Kejadian" />
                                <textarea id="deskripsi" name="deskripsi" rows="6" 
                                          class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                                          placeholder="Tuliskan kronologi kejadian, waktu, tempat, dan pihak yang terlibat secara rinci..." required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <x-input-label for="gambar_bukti" value="Unggah Bukti (Opsional)" />
                                <input id="gambar_bukti" name="gambar_bukti" type="file" 
                                       class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none focus:border-indigo-500 focus:ring-indigo-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-l-md file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-indigo-50 file:text-indigo-700
                                              hover:file:bg-indigo-100"
                                       accept="image/png, image/jpeg, image/jpg">
                                <p class="mt-1 text-xs text-gray-500">Hanya format JPG, JPEG, atau PNG. Maksimal 2MB.</p>
                                @error('gambar_bukti')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                            <div class="flex justify-between items-center mt-2">
                                <div id="ai-status" class="text-sm text-gray-500"></div>
                                <button type="button" id="btnBantuTulis" 
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L1.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.25 12L17 14.188 15.75 12 17 9.813 18.25 12zM15.75 12l-1.096-1.096a4.5 4.5 0 00-3.09-3.09L9.813 6.904 9 4.125l-.813 2.779a4.5 4.5 0 00-3.09 3.09L2.25 12l2.779.813a4.5 4.5 0 003.09 3.09L9 18.875l.813-2.779a4.5 4.5 0 003.09-3.09L15.75 12z" />
                                    </svg>
                                    Perbaiki Tulisan (AI)
                                </button>
                            </div>


                            <div class="flex items-center justify-end mt-6 ">
                                <a href="{{ route('user.dashboard') }}" class="text-sm text-red-600 hover:text-red-900 underline mr-4 mx-6 px-8 py-2 rounded">
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

    <script>
      // ... (JavaScript Anda yang sudah ada diletakkan di sini) ...
      document.addEventListener('DOMContentLoaded', function() {
        const btnBantuTulis = document.getElementById('btnBantuTulis');
        const deskripsiTextarea = document.getElementById('deskripsi');
        const aiStatus = document.getElementById('ai-status');

        btnBantuTulis.addEventListener('click', async function() {
            const userText = deskripsiTextarea.value;

            if (userText.trim().length < 10) {
                aiStatus.textContent = 'Harap isi deskripsi terlebih dahulu (min. 10 karakter).';
                aiStatus.className = 'text-sm text-red-500';
                return;
            }

            // Status Loading
            aiStatus.textContent = 'AI sedang memperbaiki tulisan...';
            aiStatus.className = 'text-sm text-blue-500';
            btnBantuTulis.disabled = true;
            btnBantuTulis.classList.add('opacity-50', 'cursor-not-allowed');

            try {
                const improvedText = await callGeminiApi(userText);
                deskripsiTextarea.value = improvedText; // Update textarea
                
                aiStatus.textContent = 'Tulisan berhasil diperbaiki!';
                aiStatus.className = 'text-sm text-green-500';

            } catch (error) {
                console.error('Error panggil AI:', error);
                aiStatus.textContent = 'Gagal memperbaiki tulisan. Coba lagi.';
                aiStatus.className = 'text-sm text-red-500';
            } finally {
                // Kembalikan tombol ke normal
                btnBantuTulis.disabled = false;
                btnBantuTulis.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });

        async function callGeminiApi(text, retries = 3, delay = 1000) {
            // Prompt untuk AI
            const systemPrompt = "Anda adalah asisten penulis yang membantu mahasiswa. Ubah teks berikut menjadi keluhan yang formal, jelas, sopan, dan rapi untuk laporan pengaduan di lingkungan kampus. Jaga agar inti masalahnya tetap sama. Balas HANYA dengan teks yang sudah diperbaiki, tanpa kata pembuka atau penutup tambahan seperti 'Tentu, ini hasilnya:'";
            
            const userQuery = text;
            const apiKey = "AIzaSyDG7XD2DQoViAX2a3e-jgGtT4-OJJ2OSiM"; // API Key akan di-inject oleh environment
            const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${apiKey}`;

            const payload = {
                contents: [{
                    parts: [{ text: userQuery }]
                }],
                systemInstruction: {
                    parts: [{ text: systemPrompt }]
                },
            };

            for (let i = 0; i < retries; i++) {
                try {
                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    
                    if (result.candidates && result.candidates[0].content && result.candidates[0].content.parts[0].text) {
                        return result.candidates[0].content.parts[0].text; // Berhasil
                    } else {
                        throw new Error('Format respons AI tidak valid.');
                    }

                } catch (error) {
                    if (i === retries - 1) {
                        // Gagal setelah semua percobaan
                        throw error;
                    }
                    // Tunggu sebelum mencoba lagi (exponential backoff)
                    await new Promise(res => setTimeout(res, delay * Math.pow(2, i)));
                }
            }
        }
    });
    </script>
</x-app-layout>