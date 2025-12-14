<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/Logo hima.png') }}" type="image/png">

    <title>@yield('title', 'Beranda') - Himpunan Mahasiswa</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- Anda bisa ganti ke Poppins jika lebih suka --}}
    {{-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> --}}

    <!-- Scripts & Styles (Menggunakan Vite seperti Breeze) -->
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
    {{-- Pastikan file resources/css/app.css Anda mengimpor Tailwind --}}

    {{-- Untuk CSS tambahan dari halaman spesifik --}}
    @stack('styles')

    {{-- Jika TIDAK menggunakan Vite, gunakan CDN Tailwind (hapus @vite di atas) --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    {{-- <style> body { font-family: 'Figtree', sans-serif; } </style> --}}

</head>
<body class="font-sans antialiased bg-gray-100 flex flex-col min-h-screen">

    {{-- Memasukkan Navbar dari file partial --}}
    @include('partials.public.navbar')

    {{-- Bagian Hero (jika ada, untuk halaman depan) --}}
    @hasSection('hero')
        @yield('hero')
    @endif
    
    {{-- Konten Utama Halaman --}}
    <main class="flex-grow">
        <div class="@hasSection('hero') @else mt-16 @endif"> {{-- mt-16 = tinggi navbar default Breeze --}}
             @yield('content')
        </div>
    </main>

    {{-- Memasukkan Footer dari file partial --}}
    @include('partials.public.footer')

    {{-- Untuk JS tambahan dari halaman spesifik --}}
    @stack('scripts')
</body>
</html>
