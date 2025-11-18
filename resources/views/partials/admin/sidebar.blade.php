<ul class="navbar-nav sidebar sidebar-dark accordion" id="Navigasi">

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

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        MANAJEMEN DATA
    </div>
    
    <!-- Tautan Utama ke Daftar Keputusan -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.spk.index') }}">
            <i class="fas fa-fw bi bi-list-task"></i>
            <span>Daftar Keputusan (SPK)</span>
        </a>
    </li>

    {{-- MENU STATIS SPK (MENGGUNAKAN ID 1 SEBAGAI PLACEHOLDER) --}}
    
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.spk.manage.kriteria', 1) }}">
            <i class="fas fa-fw bi bi-card-checklist"></i>
            <span>Data Kriteria</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.spk.manage.alternatif', 1) }}">
            <i class="fas fa-fw bi bi-people-fill"></i>
            <span>Data Alternatif</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.spk.manage.penilaian', 1) }}">
            <i class="fas fa-fw bi bi-pencil-square"></i>
            <span>Data Penilaian</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.spk.calculate.proses', 1) }}">
            <i class="fas fa-fw bi bi-calculator-fill"></i>
            <span>Data Perhitungan</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.spk.manage.hasil', 1) }}">
            <i class="fas fa-fw bi bi-bar-chart-line-fill"></i>
            <span>Data Hasil Akhir</span>
        </a>
    </li>
    {{-- AKHIR MENU STATIS SPK --}}


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    
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