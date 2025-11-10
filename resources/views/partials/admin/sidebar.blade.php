<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class=""></i>
        </div>
        <div class="sidebar-brand-text mx-3">Sistem manajemen</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Laporan -->
    <li class="nav-item ">
        <a class="nav-link" href="{{ route('admin.pengaduan.index') }}">
            <i class="fas fa-fw bi bi-chat-dots-fill"></i>
            <span>Pengaduan</span></a>
    </li>
    
    {{-- <!-- Nav Item - Manajemen akun -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw bi bi-people-fill"></i>
            <span>Manajemen akun</span></a>
    </li> --}}

    {{-- <!-- Nav Item - anggota & divisi Collapse Menu -->
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
            </div>
        </div>
    </li> --}}

    <!-- Nav Item - Sanksi -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.sanksi.index') }}">
            <i class="fas fa-fw bi bi-exclamation-triangle-fill"></i>
            <span>Sanksi</span></a>
    </li>

    <!-- Nav Item - Prestasi -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.prestasi.index') }}">
            <i class="fas fa-fw bi bi-trophy-fill"></i>
            <span>Prestasi</span></a>
    </li>

    <!-- Nav Item - Berita -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.berita.index') }}">
            <i class="fas fa-fw bi bi-newspaper"></i>
            <span>Berita</span></a>
    </li>
 <!-- Kembali ke Website -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="bi bi-house-door"></i>
            <span>Kembali ke Website</span>
        </a>
    </li>
    


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
    

</ul>