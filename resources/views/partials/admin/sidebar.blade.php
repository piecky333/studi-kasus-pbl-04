<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Sistem manajemen</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Manajemen akun -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw bi bi-people-fill"></i>
            <span>Manajemen akun</span></a>
    </li>

    <!-- Nav Item - anggota % divisi Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw bi bi-diagram-3-fill"></i>
            <span>Anggota & divisi</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="login.html">Daftar anggota HIMA-TI</a>
                <a class="collapse-item" href="register.html">Struktur Divisi</a>
                <a class="collapse-item" href="forgot-password.html">Anggota per Divisi</a>
                {{-- <div class="collapse-divider"></div>
                <h6 class="collapse-header">Other Pages:</h6>
                <a class="collapse-item" href="404.html">404 Page</a>
                <a class="collapse-item" href="blank.html">Blank Page</a> --}}
            </div>
        </div>
    </li>

    <!-- Nav Item - prestasi -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw bi bi-trophy-fill"></i>
            <span>prestasi</span></a>
    </li>

    <!-- Nav Item - Berita -->
    <li class="nav-item ">
       <a class="nav-link" href="{{ route('admin.berita.index') }}">
            <i class="fas fa-fw bi bi-newspaper"></i>
            <span>Berita</span></a>
    </li>

    <!-- Nav Item - Laporan -->
    <li class="nav-item ">
        <a class="nav-link" href="{{ route('admin.pengaduan.index') }}">
            <i class="fas fa-fw bi bi-chat-dots-fill"></i>
            <span>Pengaduan</span></a>
    </li>

    <!-- Nav Item - Pengaturan -->
    <li class="nav-item ">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw bi bi-gear-fill"></i>
            <span>pengaturan</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>