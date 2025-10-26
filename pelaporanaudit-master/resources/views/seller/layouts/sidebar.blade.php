    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-custom-biru-muda sidebar sidebar-dark accordion" id="accordionSidebar">

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
            <li class="nav-item ">
                <a class="nav-link" href="{{ url('/homeseller?viewAsSeller=true') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="{{ url('/produkseller') }}">
                    <i class="fa-solid fa-box"></i>
                    <span>Produk Saya</span></a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="{{ url('/pesanansaya') }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Pesanan Saya</span></a>
            </li>

           
            
            <li class="nav-item ">
                <a class="nav-link" href="{{ url('/kategori') }}">
                <i class="fa-solid fa-list"></i>                   
                 <span>Kategori</span></a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item ">
                <a class="nav-link" href="{{ url('/service') }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Pelayanan Offline</span></a>
            </li>
            <hr class="sidebar-divider"> 
            <li class="nav-item ">
                <a class="nav-link" href="{{ url('/') }}">
                    <i class="fa-solid fa-home"></i>
                    <span>Halaman User</span></a>
            </li>



        </ul>
        <!-- End of Sidebar -->

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            document.getElementById('toggleStatus').addEventListener('click', function(e) {
                e.preventDefault();
                let status = this.classList.contains('active') ? 'inactive' : 'active';
                fetch('/toggleStatus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.classList.toggle('active');
                        this.querySelector('i').classList.toggle('fa-toggle-on');
                        this.querySelector('i').classList.toggle('fa-toggle-off');
                        this.querySelector('span').textContent = status.charAt(0).toUpperCase() + status.slice(1);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        </script>
