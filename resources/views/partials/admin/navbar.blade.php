<nav class="bg-white border-b border-gray-200 shadow-sm z-50 w-full" x-data="{ profileOpen: false, notificationOpen: false, showBadge: true, mobileMenuOpen: false }">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Title / Breadcrumbs -->
            <div class="flex items-center">
                {{-- Toggle Sidebar Button (Mobile) --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="flex-shrink-0 flex items-center">
                    <h1 class="text-xl font-bold text-gray-800 tracking-tight">
                        Panel Admin
                    </h1>
                </div>
            </div>

            <!-- Right Side: Notifications & Profile -->
            <div class="flex items-center space-x-4">
                
                <!-- Notification Bell -->
                <div class="relative">
                    @php
                        // Mengambil notifikasi pengaduan terbaru (status 'Terkirim')
                        $unreadCount = \App\Models\laporan\pengaduan::where('status', 'Terkirim')->count();
                        $latestNotifications = \App\Models\laporan\pengaduan::with('user')
                                                ->where('status', 'Terkirim')
                                                ->latest()
                                                ->take(5)
                                                ->get();
                    @endphp

                    <button @click="notificationOpen = !notificationOpen; showBadge = false" @click.away="notificationOpen = false" class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative">
                        <span class="sr-only">View notifications</span>
                        <i class="bi bi-bell text-xl"></i>
                        
                        <!-- Badge Count -->
                        @if($unreadCount > 0)
                            <span x-show="showBadge" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" class="absolute top-0 right-0 block h-4 w-4 rounded-full ring-2 ring-white bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Notifications -->
                    <div x-show="notificationOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                         style="display: none;">
                        
                        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                            <p class="text-sm font-medium text-gray-700">Notifikasi Pengaduan</p>
                        </div>

                        <div class="max-h-64 overflow-y-auto">
                            @forelse($latestNotifications as $notif)
                                <a href="{{ route('admin.pengaduan.show', $notif->id_pengaduan) }}" class="block px-4 py-3 hover:bg-gray-50 transition duration-150 ease-in-out border-b border-gray-50 last:border-0">
                                    <p class="text-sm text-gray-800 font-semibold truncate">{{ $notif->judul }}</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Dari: <span class="font-medium">{{ $notif->user->nama ?? 'User' }}</span></p>
                                    <p class="text-xs text-gray-400 mt-1 flex items-center">
                                        <i class="bi bi-clock mr-1"></i> {{ $notif->created_at->diffForHumans() }}
                                    </p>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                    <i class="bi bi-check-circle text-2xl mb-2 block text-green-500"></i>
                                    Tidak ada pengaduan baru.
                                </div>
                            @endforelse
                        </div>

                        <div class="px-4 py-2 border-t border-gray-100 text-center bg-gray-50 rounded-b-md">
                            <a href="{{ route('admin.pengaduan.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                                Lihat Semua Pengaduan <i class="bi bi-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative ml-3">
                    <div>
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" type="button" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <div class="flex items-center">
                                <span class="mr-3 hidden md:block text-sm font-medium text-gray-700">
                                    {{ Auth::user()->nama ?? 'Administrator' }}
                                </span>
                                <img class="h-8 w-8 rounded-full object-cover border border-gray-200" 
                                     src="{{ Auth::user()->profile_photo_url }}" 
                                     alt="">
                            </div>
                        </button>
                    </div>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                         role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                         style="display: none;">
                        
                        <div class="px-4 py-2 border-b border-gray-100 md:hidden">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->nama ?? 'Administrator' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:no-underline" style="text-decoration: none !important;" role="menuitem" tabindex="-1" id="user-menu-item-0">
                            <i class="bi bi-person mr-2 text-gray-400"></i> Profile
                        </a>
                        
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:no-underline" style="text-decoration: none !important;" role="menuitem" tabindex="-1" id="user-menu-item-2">
                            <i class="bi bi-box-arrow-right mr-2 text-red-400"></i> Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu (Vertical) -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-b border-gray-200 shadow-lg"
         style="display: none;">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-grid mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.datamahasiswa.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-person-fill mr-2"></i> Data Mahasiswa
            </a>
            <a href="{{ route('admin.pengaduan.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-chat-dots-fill mr-2"></i> Pengaduan
            </a>
            <a href="{{ route('admin.sanksi.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i> Sanksi
            </a>
            <a href="{{ route('admin.prestasi.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-trophy-fill mr-2"></i> Prestasi
            </a>
            <a href="{{ route('admin.berita.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-newspaper mr-2"></i> Berita
            </a>
            <div class="border-t border-gray-200 my-2"></div>
            <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Manajemen Data
            </div>
            <a href="{{ route('admin.spk.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-list-task mr-2"></i> Daftar Keputusan (SPK)
            </a>
            <div class="border-t border-gray-200 my-2"></div>
            <a href="{{ url('/') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-house-door mr-2"></i> Kembali ke Website
            </a>
        </div>
    </div>
</nav>

<!-- Form Logout (Hidden) -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
