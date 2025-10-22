<!-- Topbar Admin -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Judul Kiri (Versi Admin) -->
    <h5 class="ml-3 mb-0 fw-bold text-primary">Panel Admin</h5>

    <!-- Topbar Navbar (Ini adalah wrapper untuk semua item di kanan) -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Information (Ini adalah dropdown-nya) -->
        <li class="nav-item dropdown no-arrow">
            
            <!-- (A) TRIGGER DROPDOWN -->
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
                <!-- Nama User (Admin) -->
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    {{-- Kita asumsikan Admin juga punya kolom 'nama' --}}
                    {{ Auth::user()->nama ?? 'Nama Admin' }}
                </span>
                
                <!-- Foto Profil (Placeholder dengan inisial) -->
                <img class="img-profile rounded-circle"
                     src="https://placehold.co/60x60/4E73DF/white?text={{ substr(Auth::user()->nama ?? 'A', 0, 1) }}"
                     alt="Foto Profil"
                     style="width: 32px; height: 32px; object-fit: cover;">
            </a>

            <!-- (B) MENU DROPDOWN -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                
                <!-- Pilihan 1: Profile -->
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                
                <div class="dropdown-divider"></div>

                <!-- Pilihan 2: Logout -->
                <a class="dropdown-item" href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul> 

</nav>
<!-- End of Topbar -->


<!-- form log out -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
