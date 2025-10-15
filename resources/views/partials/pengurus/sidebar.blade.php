<ul class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('pengurus.dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PENGURUS</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->is('pengurus/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('pengurus.dashboard') }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Manajemen Data -->
    <div class="sidebar-heading text-light small">Manajemen Data</div>

    <!-- Kelola Divisi -->
    <li class="nav-item {{ request()->is('pengurus/divisi*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('pengurus.divisi.index') }}">
            <i class="bi bi-diagram-3"></i>
            <span>Kelola Divisi</span>
        </a>
    </li>

    <!-- Kelola Jabatan -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-person-badge"></i>
            <span>Kelola Jabatan</span>
        </a>
    </li>

    <!-- Kelola Pengurus -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-people"></i>
            <span>Kelola Pengurus</span>
        </a>
    </li>

    <!-- Kelola Keuangan -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-cash-stack"></i>
            <span>Kelola Keuangan</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Kembali ke Website -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="bi bi-house-door"></i>
            <span>Kembali ke Website</span>
        </a>
    </li>

</ul>
