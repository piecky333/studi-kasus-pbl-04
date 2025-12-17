<footer class="bg-brand" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
        <div class="xl:grid xl:grid-cols-3 xl:gap-8">

            <!-- Kolom 1: Identitas & Sosial Media -->
            <div class="space-y-8 xl:col-span-1 wow fadeInUp" data-wow-delay="0.1s">
                {{-- Logo Anda --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <img class="h-10 w-auto" src="{{ asset('img/Logo hima.png') }}" alt="Logo HIMA-TI">
                    <span class="text-white text-xl font-bold">HIMA-TI</span>
                </a>
                <p class="text-white text-sm max-w-xs leading-relaxed">
                    Wadah penggerak inovasi dan kolaborasi mahasiswa Politeknik Negeri Tanah Laut.
                </p>
                {{-- Ikon Sosial Media --}}
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300 transform hover:-translate-y-1">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/akun-hima-ti" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white transition duration-300 transform hover:-translate-y-1">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.07 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.069-4.849.069-3.205 0-3.584-.012-4.849-.069-3.225-.149-4.771-1.664-4.919-4.919-.058-1.265-.069-1.644-.069-4.849 0-3.204.012-3.584.069-4.849.149-3.225 1.664-4.771 4.919-4.919C8.416 2.175 8.796 2.163 12 2.163zm0 2.712c-3.935 0-7.122 3.187-7.122 7.122s3.187 7.122 7.122 7.122 7.122-3.187 7.122-7.122S15.935 4.875 12 4.875zm0 2.25c2.69 0 4.875 2.185 4.875 4.875s-2.185 4.875-4.875 4.875-4.875-2.185-4.875-4.875S9.31 7.125 12 7.125zm4.875-2.125c-.621 0-1.125.504-1.125 1.125s.504 1.125 1.125 1.125 1.125-.504 1.125-1.125S17.496 5 16.875 5z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-300 transform hover:-translate-y-1">
                         <span class="sr-only">LinkedIn</span>
                         <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                             <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" />
                         </svg>
                    </a>
                </div>
            </div>

            <!-- Kolom 2: Navigasi & Tautan -->
            <div class="xl:col-span-1 wow fadeInUp" data-wow-delay="0.3s">
                <h3 class="text-sm font-semibold text-gray-200 uppercase tracking-wider">Navigasi</h3>
                <ul role="list" class="mt-4 space-y-3">
                    <li><a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition-colors duration-300">Home</a></li>
                    <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-300">Profil Organisasi</a></li>
                    <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-300">Divisi</a></li>
                    <li><a href="{{ route('berita.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors duration-300">Berita</a></li>
                    <li><a href="{{ route('prestasi.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors duration-300">Prestasi</a></li>
                    {{-- Tautan Terkait items merged here --}}
                    <li><a href="https://politala.ac.id" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-400 hover:text-white transition-colors duration-300">Politeknik Negeri Tanah Laut</a></li>
                    <li><a href="{{ route('user.pengaduan.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors duration-300">Buat Pengaduan</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Hubungi Kami (NEW) -->
            <div class="xl:col-span-1 wow fadeInUp" data-wow-delay="0.5s">
                <h3 class="text-lg font-bold text-white mb-4">Hubungi Kami</h3>
                <ul class="space-y-4">
                     <li class="flex items-start">
                        <svg class="h-6 w-6 text-gray-400 mr-3 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm text-gray-300 leading-relaxed">
                             Jl. A. Yani Km. 06 Desa Panggung, Kec. Pelaihari, Kab. Tanah Laut, Kalimantan Selatan, 70815
                        </span>
                     </li>
                     <li class="flex items-center">
                         <svg class="h-6 w-6 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                         </svg>
                         <span class="text-sm text-gray-300">(0512) 2021065</span>
                     </li>
                     <li class="flex items-center">
                         <svg class="h-6 w-6 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                         </svg>
                         <a href="mailto:mail@politala.ac.id" class="text-sm text-gray-300 hover:text-white transition-colors duration-300">mail@politala.ac.id</a>
                     </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar: Copyright & Tautan Sekunder -->
        {{-- MODIFIKASI: Menambahkan 'wow' dan 'delay' --}}
        <div class="mt-12 border-t border-white-800 pt-8 wow fadeInUp" data-wow-delay="0.5s">
            <p class="text-sm text-white text-center">
                &copy; {{ date('Y') }} Himpunan Mahasiswa Teknologi Informasi. All rights reserved.
            </p>
            
            {{-- MODIFIKASI: Dibuat 'flex justify-center' agar rapi --}}
            <div class="mt-4 flex justify-center space-x-6">
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-300">Kebijakan Privasi</a>
                <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors duration-300">Ketentuan Layanan</a>
            </div>

        </div>
    </div>
</footer>