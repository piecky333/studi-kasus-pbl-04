<nav x-data="{ open: false, dropdownOpen: false }" class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-[1050] transition-all duration-300 ease-in-out">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left Side: Brand / Title -->
            <div class="flex items-center">
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
                                @if (Auth::user()->avatar)
                                    <img class="h-8 w-8 rounded-full object-cover border border-gray-200" 
                                         src="{{ Str::startsWith(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar) }}" 
                                         alt="Avatar">
                                @else
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 border border-indigo-200">
                                        <span class="text-xs font-bold leading-none text-indigo-700">
                                            {{ strtoupper(substr(Auth::user()->nama ?? 'P', 0, 1)) }}
                                        </span>
                                    </span>
                                @endif
                                
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

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150" role="menuitem" tabindex="-1" id="user-menu-item-0">
                            <i class="fas fa-user mr-2 text-gray-400"></i> Profil Saya
                        </a>
                        
                        <div class="border-t border-gray-100 my-1"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150" role="menuitem" tabindex="-1" id="user-menu-item-2">
                                <i class="fas fa-sign-out-alt mr-2 text-red-400"></i> Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


