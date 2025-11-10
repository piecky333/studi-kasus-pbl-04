<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles (Menggunakan Vite seperti Breeze) -->
    @vite(['resources/css/app.css', 'resources/js/app.js']) 

    {{-- Untuk CSS tambahan dari halaman spesifik --}}
    @stack('styles')

    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

</head>
<body class="font-sans antialiased bg-gray-100 flex flex-col min-h-screen">

    {{-- Memasukkan Navbar dari file partial --}}
    @include('partials.user.navbar')

    {{-- Bagian Hero (jika ada, untuk halaman depan) --}}
    @hasSection('hero')
        @yield('hero')
    @endif
    
    {{-- Konten Utama Halaman --}}
    <main class="flex-grow">
        <div class="@hasSection('hero') @else mt-16 @endif"> {{-- mt-16 = tinggi navbar default Breeze --}}
             {{ $slot }}
        </div>
    </main>

    {{-- Untuk JS tambahan dari halaman spesifik --}}
    @stack('scripts')
</body>
</html>
