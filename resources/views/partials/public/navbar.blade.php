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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/profile') }}">Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/berita') }}">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/prestasi') }}">Prestasi Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/pengaduan') }}">Laporan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>