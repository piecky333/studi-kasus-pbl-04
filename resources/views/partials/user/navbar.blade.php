{{-- File: resources/views/layouts/navigation.blade.php --}}

<nav x-data="{ open: false }" class="bg-brand border-b border-brand shadow-sm">
    
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('img/Logo hima.png') }}" alt="Logo" class="block h-9 w-auto">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(Auth::user()->role === 'mahasiswa')
                        <x-nav-link :href="route('mahasiswa.dashboard')" :active="request()->routeIs('mahasiswa.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.sertifikat.index')" :active="request()->routeIs('mahasiswa.sertifikat.*')">
                            {{ __('Sertifikat (Lama)') }}
                        </x-nav-link>
                        <x-nav-link :href="route('mahasiswa.prestasi.index')" :active="request()->routeIs('mahasiswa.prestasi.*')">
                            {{ __('Prestasi') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif
                    
                    {{-- Common Links --}}
                    @if(Auth::user()->role === 'mahasiswa')
                        <x-nav-link :href="route('mahasiswa.pengaduan.index')" :active="request()->routeIs('mahasiswa.pengaduan.*')">
                            {{ __('Riwayat Pengaduan') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('user.pengaduan.index')" :active="request()->routeIs('user.pengaduan.*')">
                            {{ __('Riwayat Pengaduan') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notification Dropdown -->
                <x-dropdown align="right" width="96">
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center p-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-100 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <!-- Bell Icon -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            
                            <!-- Red Dot for Unread Count -->
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-2 block h-2.5 w-2.5 rounded-full ring-2 ring-brand bg-red-500 transform translate-x-1/2 -translate-y-1/2"></span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-700">Notifikasi</h3>
                        </div>
                        
                        <div class="max-h-64 overflow-y-auto">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                <a href="{{ route('user.notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 transition duration-150 ease-in-out border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 pt-0.5">
                                            @if(!empty($notification->data['image']))
                                                <img src="{{ $notification->data['image'] }}" class="h-8 w-8 rounded-full object-cover border border-gray-200" alt="Avatar">
                                            @elseif(($notification->data['type'] ?? '') == 'reply')
                                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <i class="fas fa-comment shadow-sm text-green-600 text-xs"></i>
                                                </div>
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                     <i class="fas fa-bell shadow-sm text-blue-600 text-xs"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3 w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $notification->data['judul'] ?? 'Notifikasi Baru' }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500 line-clamp-2">
                                                {{ $notification->data['pesan'] ?? '' }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                    <p>Tidak ada notifikasi baru.</p>
                                </div>
                            @endforelse
                        </div>
                        
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <div class="border-t border-gray-100 bg-gray-50 text-center">
                                <a href="#" class="block py-2 text-xs font-medium text-brand hover:text-indigo-800">
                                    Lihat Semua
                                </a>
                            </div>
                        @endif
                    </x-slot>
                </x-dropdown>

                <!-- Profile Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-100 bg-brand hover:text-white focus:outline-none transition ease-in-out duration-150">
                            
                                <img class="h-8 w-8 rounded-full object-cover me-2" 
                                     src="{{ Auth::user()->profile_photo_url }}" 
                                     alt="Avatar">
                            {{-- ================================== --}}

                            <div>{{ Auth::user()->nama }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-indigo-300 hover:text-white hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-indigo-700 border-t border-indigo-600">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->role === 'mahasiswa')
                <x-responsive-nav-link :href="route('mahasiswa.dashboard')" :active="request()->routeIs('mahasiswa.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mahasiswa.sertifikat.index')" :active="request()->routeIs('mahasiswa.sertifikat.*')">
                    {{ __('Sertifikat (Lama)') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mahasiswa.prestasi.index')" :active="request()->routeIs('mahasiswa.prestasi.*')">
                    {{ __('Prestasi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('mahasiswa.pengaduan.index')" :active="request()->routeIs('mahasiswa.pengaduan.*')">
                    {{ __('Riwayat Pengaduan') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.pengaduan.index')" :active="request()->routeIs('user.pengaduan.*')">
                    {{ __('Riwayat Pengaduan') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-indigo-600">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->nama }}</div>
                <div class="font-medium text-sm text-indigo-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil Saya') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>