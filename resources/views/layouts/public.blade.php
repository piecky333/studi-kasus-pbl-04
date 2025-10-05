
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi HIMA')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        nav.navbar {
            background-color: #004080;
        }
        nav a.nav-link {
            color: #fff !important;
            font-weight: 500;
        }
        nav a.nav-link:hover {
            color: #ffd700 !important;
        }
        footer {
            background-color: #002855;
            color: #ddd;
            padding: 30px 0;
            margin-top: 50px;
        }
        footer a {
            color: #ffd700;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ url('/') }}">Sistem Informasi HIMA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/divisi') }}">Divisi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/profil') }}">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/berita') }}">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/pendaftaran') }}">Pendaftaran</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/prestasi') }}">Prestasi Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/laporan') }}">Laporan</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten Halaman --}}
    <main class="container py-5">
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
            <p>Jl. Pendidikan No.1, Kampus Teknologi Informasi, Universitas X</p>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
