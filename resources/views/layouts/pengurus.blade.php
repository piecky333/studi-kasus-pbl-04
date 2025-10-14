<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Panel Pengurus - @yield('title', 'HIMA-TI')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        {{--  --}}
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/pengurus/divisi') }}">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Pengurus</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->is('pengurus/divisi*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/pengurus/divisi') }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>Divisi</span>
                </a>
            </li>

            <!-- Nav Item - Berita -->
            <li class="nav-item {{ request()->is('pengurus/berita*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/pengurus/berita') }}">
                    <i class="bi bi-newspaper"></i>
                    <span>Berita</span>
                </a>
            </li>

            <!-- Nav Item - Pengaduan -->
            <li class="nav-item {{ request()->is('pengurus/pengaduan*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('/pengurus/pengaduan') }}">
                    <i class="bi bi-envelope-exclamation"></i>
                    <span>Pengaduan</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Kembali -->
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                    <i class="bi bi-house-door"></i>
                    <span>Kembali ke Website</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

        </ul>
        {{-- End Sidebar Pengurus --}}

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                {{-- Topbar --}}
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <h5 class="m-0 fw-bold text-primary">Panel Pengurus</h5>
                </nav>
                {{-- End Topbar --}}

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

</body>
</html>
