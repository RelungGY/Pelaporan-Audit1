
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-md-3 p-4">
                <h5>Filter</h5>
                
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Produk</a>
                    </li>
                
                </ul>

                <!-- Filter Sections -->
                <div class="accordion" id="filterAccordion">
                    <!-- Kategori -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#kategoriCollapse">
                                Kategori
                            </button>
                        </h2>
                        <div id="kategoriCollapse" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                @foreach($kategori as $kategori)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="kategori-{{ $kategori->id }}" value="{{ $kategori->id }}">
                                    <label class="form-check-label" for="kategori-{{ $kategori->id }}">{{ $kategori->namakategori }}</label>
                                </div>
                            @endforeach
                                
                            </div>
                        
                        </div>
                    </div>

                    

                    <!-- Harga -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hargaCollapse">
                                Harga
                            </button>
                        </h2>
                        <div id="hargaCollapse" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" placeholder="Harga Minimum" id="minPriceInput">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>

            <!-- Product Listing -->
            <div class="col-md-9 p-4">
                <div class="d-flex justify-content-end align-items-center mb-4">
                    {{-- <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari produk..."> --}}
                    <select id="filterSelect" class="form-select w-auto">
                        <option value="default" selected>Paling Sesuai</option>
                        <option value="terbaru">Terbaru</option>
                        <option value="harga_tertinggi">Harga Tertinggi</option>
                        <option value="harga_terendah">Harga Terendah</option>
                    </select>
                </div>
                
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="productContainer">
                    @foreach($data['items'] as $item)
                    <div class="col">
                        <div class="card">
                            <img src="{{ $item->images ? asset('storage/' . json_decode($item->images)[0]) : 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg' }}" 
                                 alt="{{ $item->name }}" class="card-img-top">
                            <div class="card-body">
                                <span class="badge badge-terlaris">{{ $item->badge }}</span>
                                <h6 class="card-title mt-2">{{ $item->name }}</h6>
                                <p class="card-text fw-bold">Rp{{ number_format($item->price_per_day, 0, ',', '.') }}</p>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating me-2">
                                            <i class="fas fa-star"></i>
                                            {{-- <span>{{ $item.rating ?? '0.0'}}</span> --}}
                                        </div>
                                        {{-- <span class="text-muted">({{ $item.sold_count ?? 0}} terjual)</span> --}}
                                    </div>
                                    {{-- <small class="text-muted">{{ $item.brand ?? ''}}</small> --}}
                                    <div class="text-center">
                                        @if(Auth::check() && Auth::user()->hukuman === 'banned')
                                            <button type="button" class="btn btn-outline-dark mt-auto" data-bs-toggle="modal" data-bs-target="#bannedModal">
                                                Detail
                                            </button>
                                        @else
                                            <a href="{{ route('product.detail', ['id' => $item['id']]) }}" class="btn btn-outline-dark text-decoration-none">      Detail
                                            </a>     
                                        @endif
                                    </div>      
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
            </div>
        </div>
    </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
     $(document).ready(function () {
    // Fungsi untuk mengirimkan filter produk
    function filterProducts() {
        const sort = $('#filterSelect').val(); // Ambil nilai filter sorting
        const searchQuery = $('#searchInput').val(); // Ambil nilai pencarian (opsional)
        
        // Ambil semua kategori yang dipilih
        const selectedCategories = [];
        $('.form-check-input:checked').each(function () {
            selectedCategories.push($(this).val());
        });

        // Kirim permintaan AJAX
        $.ajax({
            url: '{{ route("filter.products") }}',
            type: 'GET',
            data: {
                sort: sort,
                search: searchQuery,
                categories: selectedCategories,
            },
            success: function (response) {
                // Kosongkan produk lama
                $('#productContainer').empty();

                // Render produk baru
                response.forEach(item => {
                    $('#productContainer').append(`
                        <div class="col">
                            <div class="card">
                                <img src="${item.images ? `/storage/${JSON.parse(item.images)[0]}` : 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg'}" 
                                     alt="${item.name}" class="card-img-top">
                                <div class="card-body">
                                    <span class="badge badge-terlaris">${item.badge ?? ''}</span>
                                    <h6 class="card-title mt-2">${item.name}</h6>
                                    <p class="card-text fw-bold">Rp${new Intl.NumberFormat('id-ID').format(item.price_per_day)}</p>
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating me-2">
                                            <i class="fas fa-star"></i>
                                            <span>${item.rating ?? '0.0'}</span>
                                        </div>
                                        <span class="text-muted">(${item.sold_count ?? 0} terjual)</span>
                                    </div>
                                    <small class="text-muted">${item.brand ?? ''}</small>
                                </div>
                            </div>
                        </div>
                    `);
                });
            },
            error: function () {
                alert('Terjadi kesalahan saat memuat produk.');
            }
        });
    }

    // Ketika filter kategori berubah
    $('.form-check-input').on('change', filterProducts);

    // Ketika filter select berubah
    $('#filterSelect').on('change', filterProducts);
});

    </script>
    <script>
        let debounceTimer; // Timer untuk debounce

$('#minPriceInput').on('input', function () {
    const value = $(this).val(); // Ambil nilai input

    // Validasi angka
    if (!$.isNumeric(value) && value !== '') {
        alert('Harap masukkan angka yang valid!');
        $(this).val('');
        return;
    }

    // Debounce untuk menunda permintaan AJAX
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const minPrice = value;

        // Jika input kosong, jangan kirim request
        if (minPrice === '') return;

        // Lakukan permintaan AJAX
        $.ajax({
            url: '{{ route("filter.products") }}',
            type: 'GET',
            data: {
                min_price: minPrice,
            },
            success: function (response) {
                // Kosongkan kontainer produk lama
                $('#productContainer').empty();

                // Render produk baru
                response.forEach(item => {
                    $('#productContainer').append(`
                        <div class="col">
                            <div class="card">
                                <img src="${item.images ? `/storage/${JSON.parse(item.images)[0]}` : 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg'}" 
                                     alt="${item.name}" class="card-img-top">
                                <div class="card-body">
                                    <span class="badge badge-terlaris">${item.badge ?? ''}</span>
                                    <h6 class="card-title mt-2">${item.name}</h6>
                                    <p class="card-text fw-bold">Rp${new Intl.NumberFormat('id-ID').format(item.price_per_day)}</p>
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating me-2">
                                            <i class="fas fa-star"></i>
                                            <span>${item.rating ?? '0.0'}</span>
                                        </div>
                                        <span class="text-muted">(${item.sold_count ?? 0} terjual)</span>
                                    </div>
                                    <small class="text-muted">${item.brand ?? ''}</small>
                                </div>
                            </div>
                        </div>
                    `);
                });
            },
            error: function () {
                alert('Terjadi kesalahan saat memuat produk.');
            }
        });
    }, 300); // Tunda 300ms
});

    </script>
    