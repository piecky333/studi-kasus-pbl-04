
@php
    /** @var \App\Models\User $user */
@endphp

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- MODIFIKASI: Menambahkan 'enctype' untuk upload file --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- ============================================= --}}
        {{-- == BAGIAN BARU: FOTO PROFIL == --}}
        {{-- ============================================= --}}
        <div x-data="{ photoPreview: null }">
            <x-input-label for="photo" :value="__('Foto Profil')" />
            
            <!-- Foto Profil Saat Ini (atau Inisial) -->
            <div class="mt-2" x-show="!photoPreview">
                @if ($user->profile_photo_path)
                    {{-- Tampilkan foto jika ada --}}
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="rounded-full h-20 w-20 object-cover">
                @else
                    {{-- Tampilkan inisial jika tidak ada foto --}}
                    <span class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gray-200">
                        <span class="text-3xl font-medium leading-none text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </span>
                @endif
            </div>
            
            <!-- Preview Foto Baru -->
            <div class="mt-2" x-show="photoPreview">
                <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <!-- Tombol Input File -->
            <input id="photo" name="photo" type="file" class="mt-2 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100"
                   @change="
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            photoPreview = e.target.result;
                        };
                        reader.readAsDataURL($event.target.files[0]);
                   "
            />
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        {{-- ============================================= --}}
        {{-- == BAGIAN NAMA (Sudah Ada) == --}}
        {{-- ============================================= --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- ============================================= --}}
        {{-- == BAGIAN EMAIL (Sudah Ada) == --}}
        {{-- ============================================= --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- ============================================= --}}
        {{-- == BAGIAN BARU: NOMOR TELEPON == --}}
        {{-- ============================================= --}}
        <div>
            <x-input-label for="no_telpon" :value="__('Nomor Telepon')" />
            <x-text-input id="no_telpon" name="no_telpon" type="tel" class="mt-1 block w-full" :value="old('no_telpon', $user->no_telpon)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('no_telpon')" />
        </div>


        {{-- ============================================= --}}
        {{-- == BAGIAN TOMBOL SIMPAN (Sudah Ada) == --}}
        {{-- ============================================= --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>