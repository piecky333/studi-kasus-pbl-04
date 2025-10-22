<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi HIMA')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        nav.navbar {
            background-color: #004080;s
        }

        .nav-item{
            margin-left: 15px
        }
        nav a.nav-link {
            color: hsl(0, 0%, 100%) !important;
        }

        nav a.nav-link:hover {
            color: #ffd700 !important;
        }

        footer {
            background-color: #002855;
            color: #ddd;
            padding: 30px 0;
            /* Hapus margin-top agar tidak ada jarak aneh jika halaman pendek */
        }

        footer a {
            color: #ffd700;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
    
    {{-- UNTUK MENERIMA CSS DARI HALAMAN LAIN --}}
    @stack('styles')
    
</head>

<body>

    @include ('partials.public.navbar')

    {{-- BARIS INI PENTING: Untuk menampung hero section layar penuh --}}
    @yield('hero')
    
    {{-- Konten Halaman Lainnya akan masuk ke sini --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer>
        <div class="container text-center">
            <p class="mb-2 fw-semibold">© {{ date('Y') }} Himpunan Mahasiswa Teknologi Informasi</p>
            <p>
                <a href="{{ url('/profil') }}">Tentang Kami</a> |
                <a href="{{ url('/kontak') }}">Kontak</a> |
                <a href="{{ url('/kebijakan') }}">Kebijakan Privasi</a>
            </p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- UNTUK MENERIMA JAVASCRIPT DARI HALAMAN LAIN --}}
    @stack('scripts')
</body>

</html>

