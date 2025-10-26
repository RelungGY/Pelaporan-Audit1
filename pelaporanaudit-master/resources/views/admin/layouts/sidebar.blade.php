    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-custom-biru-muda sidebar sidebar-dark accordion"  id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Sewa<sup>.</sup>In</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ url('/admin') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ url('/pelaporan') }}">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Pelaporan Data</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="{{ url('/kategoriadmin') }}">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Kategori</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ url('/produkadmin') }}">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Produk</span></a>
            </li>


            <hr class="sidebar-divider">
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('admin.verifyUser') }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Verifikasi User</span></a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="{{ route('admin.banneduser') }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Banned User</span></a>
            </li>

          

        </ul>
        <!-- End of Sidebar -->