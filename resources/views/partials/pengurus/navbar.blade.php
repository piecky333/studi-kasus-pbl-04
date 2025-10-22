<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Judul Kiri (Ini sudah ada di kode Anda) -->
    <!-- Saya ganti 'ms-3' (Bootstrap 5) menjadi 'ml-3' (Bootstrap 4) -->
    <h5 class="ml-3 mb-0 fw-bold text-primary">Panel Pengurus HIMA-TI</h5>

    <!-- Topbar Navbar (Ini adalah wrapper untuk semua item di kanan) -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Information (Ini adalah dropdown-nya) -->
        <li class="nav-item dropdown no-arrow">
            
            <!-- (A) TRIGGER DROPDOWN -->
            <!-- data-toggle="dropdown" adalah kunci untuk membuatnya bisa di-klik -->
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
                <!-- Nama User (diambil dari Auth) -->
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    {{ Auth::user()->nama ?? 'Nama Pengurus' }}
                </span>
                
                <!-- Foto Profil (Placeholder dengan inisial) -->
                <img class="img-profile rounded-circle"
                     src="https://placehold.co/60x60/4E73DF/white?text={{ substr(Auth::user()->nama ?? 'U', 0, 1) }}"
                     alt="Foto Profil"
                     style="width: 32px; height: 32px; object-fit: cover;">
            </a>

            <!-- (B) MENU DROPDOWN -->
            <!-- dropdown-menu-right membuatnya rata kanan -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                
                <!-- Pilihan 1: Profile -->
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    {{-- Ganti 'fa-user' dengan 'bi-person-circle' jika Anda pakai Bootstrap Icons --}}
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                
                <div class="dropdown-divider"></div>

                <!-- Pilihan 2: Logout -->
                <!-- Ini adalah link yang akan men-trigger form logout di bawah -->
                <a class="dropdown-item" href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{-- Ganti 'fa-sign-out-alt' dengan 'bi-box-arrow-right' jika pakai Bootstrap Icons --}}
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul> <!-- Penutup .navbar-nav .ml-auto -->

</nav>
<!-- End of Topbar -->

<!-- Log out -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
