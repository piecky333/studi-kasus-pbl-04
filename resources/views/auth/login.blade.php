<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="relative flex flex-col m-6 space-y-8 bg-white shadow-2xl rounded-2xl md:flex-row md:space-y-0 max-w-4xl w-full">

            <div class="relative flex flex-col justify-center p-8 md:p-14 text-white bg-blue-600 rounded-2xl md:w-1/2 md:rounded-l-2xl md:rounded-r-none overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-800 rounded-2xl md:rounded-l-2xl md:rounded-r-none opacity-90"></div>
                <div class="relative z-10 text-center md:text-left">
                    <h2 class="mb-4 text-3xl font-bold tracking-tight">Manajemen Kemahasiswaan</h2>
                    <p class="mb-8 text-blue-100 font-light leading-relaxed">
                        Platform terintegrasi untuk pengelolaan data dan prestasi mahasiswa yang efisien dan transparan.
                    </p>
                    <div class="hidden md:block">
                        <img src="{{ asset('img/student_management.png') }}" alt="Illustration" class="w-full max-w-xs mx-auto drop-shadow-lg transform hover:scale-105 transition-transform duration-300">
                    </div>
                </div>
            </div>

            <div class="flex flex-col justify-center p-8 md:p-14 md:w-1/2">
                <div class="mb-8 text-center">
                    <h3 class="text-3xl font-bold text-gray-800">Selamat Datang</h3>
                    <p class="text-gray-500 mt-2">Silakan masuk ke akun Anda</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="w-full pl-10 pr-4 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                placeholder="nama@email.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-6" x-data="{ show: false }">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                class="w-full pl-10 pr-12 py-3 text-gray-700 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                placeholder="Masukkan password">
                            
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-2 flex items-center pr-1 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer p-2">
                                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 font-semibold shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        Masuk
                    </button>

                    <div class="relative flex items-center justify-center w-full mt-8 mb-6">
                        <div class="absolute w-full border-t border-gray-200"></div>
                        <span class="relative z-10 px-3 text-sm text-gray-500 bg-white">Atau masuk dengan</span>
                    </div>

                    <a href="{{ route('google.redirect') }}"
                        class="flex items-center justify-center w-full px-4 py-3 text-gray-700 transition-colors duration-200 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span class="font-medium">Google</span>
                    </a>

                    <p class="mt-8 text-sm text-center text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-500 hover:underline">
                            Daftar sekarang
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>