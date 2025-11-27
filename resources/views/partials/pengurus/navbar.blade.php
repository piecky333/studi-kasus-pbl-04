<nav x-data="{ open: false, dropdownOpen: false, mobileMenuOpen: false }" class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-[1050] transition-all duration-300 ease-in-out">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Brand / Title -->
            <div class="flex items-center">
                {{-- Mobile Menu Button --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 mr-2">
                    <span class="sr-only">Open menu</span>
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div class="flex-shrink-0 flex items-center">
                    <span class="text-xl font-bold text-brand tracking-tight">
                        Panel Pengurus <span class="text-indigo-600">HIMA-TI</span>
                    </span>
                </div>
            </div>

            <!-- Right Side: User Dropdown -->
            <div class="flex items-center">
                <div class="ml-3 relative">
                    <div>
                        <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" type="button" 
                            class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out" 
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            
                            <div class="flex items-center space-x-3 px-2 py-1 rounded-full hover:bg-gray-50 transition-colors duration-200">
                                <span class="block text-sm font-medium text-gray-700">
                                    {{ Auth::user()->nama ?? 'Pengurus' }}
                                </span>
                                
                                {{-- Avatar Logic --}}
                                <img class="h-8 w-8 rounded-full object-cover border border-gray-200" 
                                     src="{{ Auth::user()->profile_photo_url }}" 
                                     alt="Avatar">
                                
                                <svg class="hidden md:block h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <!-- Dropdown Menu -->
                    <div x-show="dropdownOpen" 
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
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->nama ?? 'Pengurus' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>

                        <a href="{{ route('pengurus.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 hover:no-underline" style="text-decoration: none !important;" role="menuitem" tabindex="-1" id="user-menu-item-0">
                            <i class="fas fa-user mr-2 text-gray-400"></i> Profil Saya
                        </a>
                        
                        <div class="border-t border-gray-100 my-1"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150 hover:no-underline" style="text-decoration: none !important;" role="menuitem" tabindex="-1" id="user-menu-item-2">
                                <i class="fas fa-sign-out-alt mr-2 text-red-400"></i> Log Out
                            </a>
                        </form>
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
            <a href="{{ route('pengurus.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-speedometer2 mr-2"></i> Dashboard
            </a>
            <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Manajemen Data
            </div>
            <a href="{{ route('pengurus.divisi.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-diagram-3 mr-2"></i> Kelola Divisi
            </a>
            <a href="{{ route('pengurus.jabatan.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-person-badge mr-2"></i> Kelola Jabatan
            </a>
            <a href="{{ route('pengurus.pengurus.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-people mr-2"></i> Kelola Pengurus
            </a>
            <a href="{{ route('pengurus.berita.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-newspaper mr-2"></i> Kelola Berita
            </a>
            <div class="border-t border-gray-200 my-2"></div>
            <a href="{{ url('/') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                <i class="bi bi-house-door mr-2"></i> Kembali ke Website
            </a>
        </div>
    </div>
</nav>


