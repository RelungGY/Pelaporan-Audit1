<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-custom-biru-muda">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand text-white" href="#!"><b>Sewa</b><b>.</b><b>In</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active text-white' : 'text-secondary' }}" aria-current="page" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('produk*') ? 'active text-white' : 'text-secondary' }}" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Produk
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ url('/allprodukuser') }}">All Products</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex">
                @if (Auth::check())
                


                <a class="btn btn-outline-dark" href="{{ url('/keranjanguser') }}">
                    <i class="bi-cart-fill me-1"></i>
                    Cart
                    @php
                    $unpaidCount = \App\Models\Transaction::where('status', 'unpaid')->where('user_id', Auth::id())->count();
                    @endphp
                    <span class="badge bg-dark text-white ms-1 rounded-pill">{{ $unpaidCount }}</span>
                </a>
                @php
                $user = Auth::user();
                @endphp

                @if ($user->hukuman == 'banned')
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var bannedModalShow = new bootstrap.Modal(document.getElementById('bannedModalShow'));
                        bannedModalShow.show();
                    });
                </script>
                @endif

                <div class="btn-group ms-2">
                    <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-person-fill me-1"></i>
                        Profile
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ url('/profileuser') }}">Lihat Profile</a></li>
                        @if ($user->hukuman != 'banned')
                            @if ($user->isseller == 'yes')
                                <li><a class="dropdown-item" href="{{ url('/homeseller?viewAsSeller=true') }}">Halaman Seller</a></li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#becomeSellerModal">Jadi Seller</a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>

                <!-- Banned Modal -->
                <div class="modal fade" id="bannedModalShow" tabindex="-1" aria-labelledby="bannedModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bannedModalLabel">Akun Diblokir</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Akun Anda telah diblokir. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <form action="{{ route('logout') }}" method="POST" class="ms-2">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form> --}}

                @else
                <a class="btn btn-outline-dark ms-2" href="{{ url('/login') }}">
                    <i class="bi-box-arrow-in-right me-1"></i>
                    Login
                </a>
                @endif
            </form>
        </div>
    </div>
</nav>

<!-- Header -->
@if (!request()->is('product/*') && !request()->is('keranjanguser') && !request()->is('profileuser') && !request()->is('profile/*'))
<header class="bg-dark">
    <div class="container px-4 px-lg-5">
        <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1734217673456-f93860a3fd23?q=80&w=1770&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="d-block w-100" alt="..." height="500px">
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1734375119887-460f4b97dfaa?q=80&w=1931&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="d-block w-100" alt="..." height="500px">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</header>
@endif
<div class="modal fade" id="becomeSellerModal" tabindex="-1" aria-labelledby="becomeSellerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="becomeSellerModalLabel">Syarat Menjadi Seller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
                <ul>
                    <li>Memiliki produk yang akan dijual</li>
                    <li>Setuju dengan syarat dan ketentuan platform</li>
                    <li>Melengkapi informasi profil dengan benar</li>
                </ul>
                <p>Dengan menjadi seller, Anda akan dapat mengunggah produk dan mengelola penjualan Anda sendiri.</p>
            </div>

            <!-- Footer Modal -->
            <div class="modal-footer">
                <form action="{{ route('jadi.member') }}" >
                    
                    <button type="submit" class="btn btn-primary">Saya Setuju & Jadi Seller</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
