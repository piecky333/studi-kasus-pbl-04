{{-- 
  MODIFIKASI:
  1. x-data: Ditambah 'showNav: false'
  2. x-init: Menjalankan timer untuk 'showNav = true' (ini yang memicu animasi)
  3. :class: Ditambah logika transform (slide-in)
  4. class: Ditambah 'transform' dan 'transition-all'
--}}
<nav x-data="{ open: false, scrolled: false, showNav: false }"
     x-init="setTimeout(() => showNav = true, 100)"
     @scroll.window="scrolled = (window.pageYOffset > 10)"
     :class="{ 'shadow-lg': scrolled, '-translate-y-full': !showNav, 'translate-y-0': showNav }"
     class="bg-brand border-b border-brand fixed w-full z-50 top-0 start-0 transform transition-all duration-500 ease-out">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <img src="{{ asset('img/Logo hima.png') }}" alt="Logo HIMA-TI" class="h-16 w-16 me- ">
                    <a href="{{ route('home') }}">
                        <span class="text-white text-xl font-bold">HIMA-TI</span>
                    </a>
                </div>

                {{-- =============================================== --}}
                {{-- == BAGIAN NAVIGASI DESKTOP YANG DIPERBAIKI == --}}
                {{-- =============================================== --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>

                    {{-- Link Profil (Divisi) --}}
                    @php
                        $profilHref = request()->routeIs('home') ? '#divisi' : route('home') . '#divisi';
                        // Ganti 'profil.' jika nama route Anda berbeda (misal: 'divisi.')
                        $profilActive = request()->routeIs('profil.*'); 
                    @endphp
                    <x-nav-link :href="$profilHref" :active="$profilActive">
                        {{ __('Profil') }}
                    </x-nav-link>

                    {{-- Link Berita --}}
                    @php
                        $beritaHref = request()->routeIs('home') ? '#berita' : route('home') . '#berita';
                        $beritaActive = request()->routeIs('berita.*'); 
                    @endphp
                    <x-nav-link :href="$beritaHref" :active="$beritaActive">
                        {{ __('Berita') }}
                    </x-nav-link>

                    {{-- Link Prestasi --}}
                    @php
                        $prestasiHref = request()->routeIs('home') ? '#prestasi' : route('home') . '#prestasi';
                        $prestasiActive = request()->routeIs('prestasi.*');
                    @endphp
                    <x-nav-link :href="$prestasiHref" :active="$prestasiActive">
                        {{ __('Prestasi') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    @php
                        $dashboardRoute = 'home'; // Default fallback
                        $userRole = Auth::user()->role;
                        
                        if ($userRole === 'admin')
                            $dashboardRoute = 'admin.dashboard';
                        elseif ($userRole === 'pengurus')
                            $dashboardRoute = 'pengurus.dashboard';
                        elseif ($userRole === 'mahasiswa')
                            $dashboardRoute = 'mahasiswa.dashboard';
                        elseif ($userRole === 'user')
                            $dashboardRoute = 'user.dashboard';
                    @endphp
                    <a href="{{ route($dashboardRoute) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-blue-900 uppercase tracking-widest hover:bg-yellow-300 focus:bg-yellow-300 active:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-blue-800 uppercase tracking-widest hover:bg-gray-100 focus:bg-gray-100 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Login
                    </a>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- == BAGIAN NAVIGASI MOBILE YANG DIPERBAIKI == --}}
    {{-- ============================================= --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-blue-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>

            {{-- Link Profil (Divisi) --}}
            @php
                $profilHref = request()->routeIs('home') ? '#divisi' : route('home') . '#divisi';
                $profilActive = request()->routeIs('profil.*');
            @endphp
            <x-responsive-nav-link :href="$profilHref" :active="$profilActive">
                {{ __('Profil') }}
            </x-responsive-nav-link>

            {{-- Link Berita --}}
            @php
                $beritaHref = request()->routeIs('home') ? '#berita' : route('home') . '#berita';
                $beritaActive = request()->routeIs('berita.*');
            @endphp
            <x-responsive-nav-link :href="$beritaHref" :active="$beritaActive">
                {{ __('Berita') }}
            </x-responsive-nav-link>

            {{-- Link Prestasi --}}
            @php
                $prestasiHref = request()->routeIs('home') ? '#prestasi' : route('home') . '#prestasi';
                $prestasiActive = request()->routeIs('prestasi.*');
            @endphp
            <x-responsive-nav-link :href="$prestasiHref" :active="$prestasiActive">
                {{ __('Prestasi') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-3 border-t border-blue-600">
            <div class="px-4">
                @auth
                    @php
                        $dashboardRoute = 'home'; // Default fallback
                        $userRole = Auth::user()->role;

                        if ($userRole === 'admin')
                            $dashboardRoute = 'admin.dashboard';
                        elseif ($userRole === 'pengurus')
                            $dashboardRoute = 'pengurus.dashboard';
                        elseif ($userRole === 'mahasiswa')
                            $dashboardRoute = 'mahasiswa.dashboard';
                        elseif ($userRole === 'user')
                            $dashboardRoute = 'user.dashboard';
                    @endphp
                    <a href="{{ route($dashboardRoute) }}"
                        class="block w-full text-left px-3 py-2 text-base font-medium text-yellow-300 bg-blue-800 rounded-md">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="block w-full text-left px-3 py-2 text-base font-medium text-blue-900 bg-white rounded-md">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>