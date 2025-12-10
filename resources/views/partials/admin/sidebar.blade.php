<style>
    @media (max-width: 767.98px) {
        #accordionSidebar {
            display: none !important;
        }
    }
    @media (min-width: 768px) {
        #accordionSidebar {
            display: flex !important;
            flex-direction: column !important;
            width: 14rem !important;
            transition: width 0.3s; /* Smooth transition */
        }
        /* Fix: Force width reduction when toggled */
        #accordionSidebar.toggled {
            width: 6.5rem !important;
        }
    }
    /* Fix sidebar headings not hiding when collapsed */
    #accordionSidebar.toggled .sidebar-heading {
        display: none !important;
    }
    
    /* Logic for Logo vs Text */
    .sidebar-brand-icon img {
        max-height: 40px; 
        width: auto;
    }

    #accordionSidebar:not(.toggled) .sidebar-brand-icon {
        display: none; 
    }
    #accordionSidebar.toggled .sidebar-brand-icon {
        display: block;
        text-align: center;
        margin: 0 auto;
    }
    #accordionSidebar.toggled .sidebar-brand-text {
        display: none;
    }
    
    /* Ensure dividers look good in small mode */
    #accordionSidebar.toggled hr.sidebar-divider {
        margin: 0 1rem 1rem !important;
    }
</style>
<ul class="navbar-nav bg-[#0d2149] sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/Logo hima.png') }}" alt="Logo" class="img-fluid">
        </div>
        <div class="sidebar-brand-text mx-3">Sistem manajemen</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Interface
    </div>

    <li class="nav-item ">
        <a class="nav-link" href="{{ route('admin.datamahasiswa.index') }}">
            <i class="fas fa-fw bi bi-person-fill"></i>
            <span>Data Mahasiswa</span></a>
    </li>

    <li class="nav-item ">
        <a class="nav-link" href="{{ route('admin.pengaduan.index') }}">
            <i class="fas fa-fw bi bi-chat-dots-fill"></i>
            <span>Pengaduan</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.sanksi.index') }}">
            <i class="fas fa-fw bi bi-exclamation-triangle-fill"></i>
            <span>Sanksi</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.prestasi.index') }}">
            <i class="fas fa-fw bi bi-trophy-fill"></i>
            <span>Prestasi</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.berita.index') }}">
            <i class="fas fa-fw bi bi-newspaper"></i>
            <span>Berita</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        MANAJEMEN DATA
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.spk.index') }}">
            <i class="fas fa-fw bi bi-list-task"></i>
            <span>Daftar Keputusan (SPK)</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">
            <i class="bi bi-house-door"></i>
            <span>Kembali ke Website</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>