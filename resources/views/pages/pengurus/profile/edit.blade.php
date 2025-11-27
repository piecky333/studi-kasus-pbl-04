@extends('layouts.pengurus')

@section('title', 'Profile')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Update Profile Information --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Profile Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Update your account's profile information and email address.") }}
                            </p>
                        </header>

                        <form method="POST" action="{{ route('pengurus.profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- FOTO PROFIL --}}
                            <div x-data="{ photoPreview: null }">
                                <x-input-label for="photo" :value="__('Foto Profil')" />

                                {{-- Foto profil saat ini --}}
                                <div class="mt-2" x-show="!photoPreview">
                                    @if ($user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="rounded-full h-20 w-20 object-cover">
                                    @else
                                        <span class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gray-200">
                                            <span class="text-3xl font-medium text-gray-500">
                                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                                            </span>
                                        </span>
                                    @endif
                                </div>

                                {{-- Preview ketika upload baru --}}
                                <div class="mt-2" x-show="photoPreview" style="display: none;">
                                    <span class="block rounded-full w-20 h-20 bg-cover bg-center"
                                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                    </span>
                                </div>

                                {{-- Input file --}}
                                <input id="photo" name="photo" type="file" class="mt-2 block w-full text-sm text-gray-500
                                       file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                       file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                                       hover:file:bg-blue-100"
                                       @change="const reader = new FileReader(); reader.onload = (e) => photoPreview = e.target.result; reader.readAsDataURL($event.target.files[0]);" />

                                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                            </div>

                            {{-- NAMA --}}
                            <div>
                                <x-input-label for="nama" :value="__('Nama')" />
                                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $user->nama)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                            </div>

                            {{-- EMAIL --}}
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            {{-- NOMOR TELEPON --}}
                            <div>
                                <x-input-label for="no_telpon" :value="__('Nomor Telepon')" />
                                <x-text-input id="no_telpon" name="no_telpon" type="tel" class="mt-1 block w-full" :value="old('no_telpon', $user->no_telpon)" />
                                <x-input-error class="mt-2" :messages="$errors->get('no_telpon')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            {{-- Update Password --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>
    </div>
@endsection
