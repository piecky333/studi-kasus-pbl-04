<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
        
        <div class="relative flex flex-col w-full max-w-4xl bg-white shadow-2xl rounded-2xl md:flex-row">

            <div class="relative flex flex-col justify-center p-6 text-center text-white bg-blue-600 rounded-2xl md:w-96 md:p-8 md:rounded-l-2xl md:rounded-r-none">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-700 rounded-2xl md:rounded-l-2xl md:rounded-r-none"></div>
                <div class="relative z-10">
                    <h2 class="mb-3 text-3xl font-bold">Lupa Password?</h2>
                    <p class="max-w-sm mx-auto text-sm text-blue-200">
                        Tidak masalah. Cukup masukkan email Anda dan kami akan kirimkan link untuk reset password.
                    </p>
                    
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/forgot-password-4268397-3551744.png"
                         alt="Ilustrasi Lupa Password" 
                         class="mx-auto mt-6 w-auto h-48 md:h-64">
                </div>
            </div>

            <div class="flex flex-col justify-center p-6 md:p-12 w-full">
                <span class="mb-3 text-4xl font-bold">Reset Password</span>
                
                <span class="font-light text-gray-400 mb-8">
                    {{ __('Beri tahu kami alamat email Anda dan kami akan mengirimkan link untuk memilih password baru.') }}
                </span>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div>
                        <x-input-label for="email" value="Email" class="font-semibold" />
                        <x-text-input id="email" class="block w-full px-4 py-2 mt-2 border rounded-md" type="email" name="email" :value="old('email')" required autofocus placeholder="example@gmail.com"/>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col space-y-2 mt-6">
                        <button type="submit" class="w-full px-6 py-3 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            {{ __('Kirim Link Reset Password') }}
                        </button>
                    </div>

                    <div class="flex justify-center mt-6">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>