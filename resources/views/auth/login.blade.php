<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 ">
        <div class="relative flex flex-col m-2 space-y-4 bg-white shadow-2xl rounded-2xl md:flex-row md:space-y-0 ">

            <!-- Kolom Kiri (Branding & Ilustrasi) -->
            <div
                class="relative flex flex-col justify-center p-8 text-center text-white bg-blue-600 rounded-2xl md:w-96 md:rounded-l-2xl md:rounded-r-none">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-700 rounded-2xl md:rounded-l-2xl md:rounded-r-none">
                </div>
                <div class="relative z-10">
                    <h2 class="mb-4 text-3xl font-bold">Manajemen Kemahasiswaan</h2>
                    <p class="max-w-sm mx-auto text-sm text-blue-200">
                        Platform terpusat untuk mengelola semua data dan kegiatan mahasiswa dengan efisien.
                    </p>
                    <img src="{{ asset('img/student_management.png') }}"
                        alt="Ilustrasi Manajemen Mahasiswa" class="mx-auto mt-2 w-auto h-20 md:h-64">
                </div>
            </div>

             <!-- Kolom kanan (login form) -->
            <div class="flex flex-col justify-center p-8 md:p-14">
                <span class="mb-3 text-4xl font-bold flex justify-center">Masuk</span>
                <span class="font-light text-gray-400 mb-8">
                    Selamat datang! Silakan masukkan detail Anda.
                </span>

                <x-auth-session-status class="mb-4" :status="session('status')" />


                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <x-input-label for="email" value="Email" class="font-semibold" />
                        <x-text-input id="email" class="block w-full px-4 py-2 mt-2 border rounded-md" type="email"
                            name="email" :value="old('email')" required autofocus autocomplete="username"
                            placeholder="example@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    
                    <div class="mt-4">
                        <x-input-label for="password" value="Password" class="font-semibold" />
                        <x-text-input id="password" class="block w-full px-4 py-2 mt-2 border rounded-md"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="Masukkan password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex justify-between w-full py-4">
                        <div class="mr-24">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="text-blue-600 border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    name="remember">
                                <span class="ml-2 text-sm text-gray-600">{{ __('ingat saya') }}</span>
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-gray-600 hover:text-blue-600"
                                href="{{ route('password.request') }}">
                                {{ __('Lupa Kata Sandi?') }}
                            </a>
                        @endif
                    </div>

                    <div class="flex flex-col space-y-2">
                        <button type="submit"
                            class="w-full px-6 py-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            {{ __('Masuk') }}
                        </button>
                    </div>

                    <div class="relative flex items-center justify-center w-full my-6">
                        <div class="absolute w-full border-t border-gray-300"></div>
                        <span class="relative z-10 px-4 text-sm text-gray-500 bg-white">atau</span>
                    </div>

                    <a href="{{ route('google.redirect') }}"
                        class="flex items-center justify-center w-full py-2 space-x-2 border border-gray-300 rounded-md hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 48 48">
                            <path fill="#FFC107"
                                d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
                            </path>
                            <path fill="#FF3D00"
                                d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
                            </path>
                            <path fill="#4CAF50"
                                d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
                            </path>
                            <path fill="#1976D2"
                                d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571l6.19,5.238C42.02,35.136,44,30.021,44,24C44,22.659,43.862,21.35,43.611,20.083z">
                            </path>
                        </svg>
                        <span class="font-semibold text-gray-700">Masuk dengan Google</span>
                    </a>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                    
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>