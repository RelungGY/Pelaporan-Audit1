<!-- Section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            @if (isset($data['items']))
                @foreach ($data['items'] as $item)
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <a href="{{ route('product.detail', ['id' => $item['id']]) }}" class="text-decoration-none text-dark">

                            @if ($item->images)
                                @php
                                    // Decode JSON dan ambil gambar pertama
                                    $images = json_decode($item->images, true);
                                    $firstImage = $images[0] ?? null; // Ambil gambar pertama atau null jika tidak ada
                                @endphp

                                @if ($firstImage)
                                    <img class="card-img-top" src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item['name'] }}" />
                                @else
                                    <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="{{ $item['name'] }}" />
                                @endif
                            @else
                                <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="{{ $item['name'] }}" />
                            @endif
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">{{ $item['name'] }}</h5>
                                    <!-- Product price-->
                                    {{ $item['price_per_day'] }}
                                </div>
                            </div>
                        </a>

                           
                            <!-- Modal trigger button -->
                            
                            <!-- Modal trigger button -->
                            <div class="text-center">
                                @if(Auth::check() && Auth::user()->hukuman === 'banned')
                                    <button type="button" class="btn btn-outline-dark mt-auto" data-bs-toggle="modal" data-bs-target="#bannedModal">
                                        Detail
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-dark mt-auto" data-bs-toggle="modal" data-bs-target="#itemModal{{ $item['id'] }}">
                                        Detail
                                    </button>
                                @endif
                            </div>

                            <!-- Banned Modal -->
                            <div class="modal fade" id="bannedModal" tabindex="-1" aria-labelledby="bannedModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="bannedModalLabel">Akun Diblokir</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Akun Anda telah diblokir. Silakan hubungi admin untuk informasi lebih lanjut.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="itemModal{{ $item['id'] }}" tabindex="-1" aria-labelledby="itemModalLabel{{ $item['id'] }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="itemModalLabel{{ $item['id'] }}">{{ $item['name'] }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- Carousel for multiple images -->
                                                    <div id="carouselExampleIndicators{{ $item->id }}" class="carousel slide" data-bs-ride="carousel">
                                                        <!-- Carousel Indicators -->
                                                        <div class="carousel-indicators">
                                                            @if ($item->images && is_array(json_decode($item->images)))
                                                                @foreach (json_decode($item->images) as $key => $image)
                                                                    <button type="button" 
                                                                            data-bs-target="#carouselExampleIndicators{{ $item->id }}" 
                                                                            data-bs-slide-to="{{ $key }}" 
                                                                            class="{{ $key == 0 ? 'active' : '' }}" 
                                                                            aria-current="{{ $key == 0 ? 'true' : 'false' }}" 
                                                                            aria-label="Slide {{ $key + 1 }}"></button>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Carousel Inner -->
                                                        <div class="carousel-inner">
                                                            @if ($item->images && is_array(json_decode($item->images)))
                                                                @foreach (json_decode($item->images) as $key => $image)
                                                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                                        <img src="{{ Storage::url($image) }}" class="d-block w-100" alt="Product Image {{ $key + 1 }}" height="250px">
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="carousel-item active">
                                                                    <img src="https://via.placeholder.com/800x400?text=No+Image" class="d-block w-100" alt="No Image Available">
                                                                </div>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Carousel Controls -->
                                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators{{ $item->id }}" data-bs-slide="prev">
                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Previous</span>
                                                        </button>
                                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators{{ $item->id }}" data-bs-slide="next">
                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Next</span>
                                                        </button>
                                                    </div>
                            
                                                    <!-- Thumbnail Navigation -->
                                                    <div class="d-flex mt-2 thumbnail-nav">
                                                        @if ($item->images && is_array(json_decode($item->images)))
                                                            @foreach (json_decode($item->images) as $key => $image)
                                                                <div class="thumbnail-item mx-1" onclick="$('#carouselExampleIndicators{{ $item->id }}').carousel({{ $key }})">
                                                                    <img src="{{ Storage::url($image) }}" class="img-thumbnail" alt="Thumbnail {{ $key + 1 }}" height="100px">
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                            
                                                <div class="col-md-6">
                                                    <!-- Product Details -->
                                                    <div class="product-details">
                                                        <div class="ratings mb-2">
                                                            <span class="rating-score">{{ $item->rating ?? '4.9' }}</span>
                                                            <div class="stars">
                                                                @for($i = 0; $i < 5; $i++)
                                                                    <i class="fas fa-star text-warning"></i>
                                                                @endfor
                                                            </div>
                                                            <span class="reviews-count">{{ $item->reviews_count ?? '2,3RB' }} Penilaian</span>
                                                            <span class="sales-count">{{ $item->sales_count ?? '4,5RB' }} Terjual</span>
                                                        </div>
                            
                                                        <div class="price-section mb-3">
                                                            <h3 class="text-danger mb-0" data-price="{{ $item->price_per_day }}">
                                                                Rp{{ number_format($item->price_per_day, 0, ',', '.') }}
                                                            </h3>
                                                            @if($item->original_price)
                                                                <small class="text-muted text-decoration-line-through">Rp{{ number_format($item->original_price, 0, ',', '.') }}</small>
                                                            @endif
                                                        </div>
                                                        


                                                        <div class="description mt-4">
                                                            <h6>Deskripsi Produk</h6>
                                                            <p>{{ $item['description'] }}</p>
                                                        </div>
                            
                                                    
                            
                                                        <!-- Quantity Selection -->
                                                        <div class="quantity-section mb-3">
                                                            <h6>Tambah hari</h6>
                                                            <div class="input-group" style="width: 150px;">
                                                                <button class="btn btn-outline-secondary" type="button">-</button>
                                                                <input type="text" class="form-control text-center" value="1">
                                                                <button class="btn btn-outline-secondary" type="button">+</button>
                                                            </div>
                                                        </div>
                            
                                                        <!-- Action Buttons -->
                                                        <!-- Total Price Calculation -->
                                                        <div class="total-price-section mb-3">
                                                            <h6>Total Harga</h6>
                                                            <div class="input-group" style="width: 150px;">
                                                                <input type="text" class="form-control text-center total-price" value="Rp{{ number_format($item->price_per_day, 0, ',', '.') }}" readonly>
                                                            </div>
                                                        </div>

                                                        
                                                        <div class="action-buttons">
                                                            @if(Auth::check())
                                                                @if (strtolower(Auth::user()->status_verifikasi) === 'verified')
                                                                    <button class="btn btn-outline-danger btn-lg w-100 mb-2" 
                                                                            data-item-id="{{ $item['id'] }}">
                                                                        <i class="fas fa-shopping-cart"></i> Masukkan Keranjang
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-outline-danger btn-lg w-100 mb-2" 
                                                                            data-bs-toggle="modal" data-bs-target="#verificationModal">
                                                                        <i class="fas fa-shopping-cart"></i> Masukkan Keranjang
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <a href="{{ route('login') }}" class="btn btn-outline-danger btn-lg w-100 mb-2">
                                                                    <i class="fas fa-shopping-cart"></i> Masukkan Keranjang
                                                                </a>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                            
                                            <!-- Product Description -->
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        
                        </div>
                    </div>
                @endforeach
            @endif
            
        </div>
    </div>
</section>

<div class="modal fade" id="alreadyRentedModal" tabindex="-1" aria-labelledby="alreadyRentedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alreadyRentedModalLabel">Informasi Sewa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Anda tidak dapat menyewa barang ini lagi karena masih dalam status sewa. Harap kembalikan barang terlebih dahulu sebelum menyewa ulang.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<!-- Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationModalLabel">Verifikasi Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda perlu memverifikasi akun Anda sebelum dapat menambahkan produk ke keranjang.</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('profileuser') }}" class="btn btn-primary">Verifikasi Sekarang</a>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const isUserLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    const loginUrl = "{{ route('login') }}";
</script>

<script>
    $(document).on('click', '.btn-outline-danger', function (e) {
    e.preventDefault();

    // Pastikan pengguna login
    if (!isUserLoggedIn) {
        window.location.href = loginUrl;
        return;
    }

    // Ambil ID barang dari tombol
    const itemId = $(this).data('item-id');

    // Panggil API untuk pengecekan status sewa
    $.ajax({
        url: "{{ route('transaction.check') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            item_id: itemId
        },
        success: function (response) {
            if (response.exists) {
                // Tampilkan modal jika barang sudah disewa dan belum dikembalikan
                $('#alreadyRentedModal').modal('show');
                return; // Hentikan proses di sini
            }

            // Jika barang belum disewa, lanjutkan proses ke keranjang
            addToCart(itemId);
        },
        error: function (xhr) {
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan.';
            alert('Error: ' + message);
        }
    });
});

// Fungsi untuk menambahkan barang ke keranjang
function addToCart(itemId) {
    // Ambil elemen parent dari tombol yang diklik
    const productDetails = $(`.btn-outline-danger[data-item-id="${itemId}"]`).closest('.product-details');

    // Ambil data jumlah barang (quantity) dan harga per hari (pricePerDay)
    const quantity = parseInt(productDetails.find('.quantity-section input').val(), 10);
    const pricePerDay = parseFloat(productDetails.find('.price-section h3').data('price'));

    // Validasi nilai input
    if (isNaN(quantity) || quantity <= 0 || isNaN(pricePerDay)) {
        alert('Jumlah barang atau harga tidak valid!');
        return;
    }

    // Hitung total harga
    const totalPrice = quantity * pricePerDay;

    // Kirim data ke server via AJAX
    $.ajax({
        url: "{{ route('cart.add') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            item_id: itemId,
            quantity: quantity,
            total_price: totalPrice
        },
        success: function (response) {
            if (response.success) {
                alert(response.message);
                location.reload(); // Auto reload setelah berhasil menambahkan ke keranjang
            } else {
                alert('Gagal menambahkan ke keranjang: ' + (response.message || 'Tidak diketahui.'));
            }
        },
        error: function (xhr) {
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan.';
            alert('Error: ' + message);
        }
    });
}

</script>
