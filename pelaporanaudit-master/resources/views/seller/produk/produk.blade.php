 <!-- Begin Page Content -->
 <div class="container-fluid"> 
    {{-- berarti tutup ini da d file lain? --}}
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Produk Data</h1>
    
        <!-- Topbar Search -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    {{-- <input type="text" class="form-control bg-light border-1 small bg-white" placeholder="Search for..."
                        aria-label="Search" aria-describedby="basic-addon2"> --}}
                    {{-- <div class="input-group-append">
                        <button class="btn bg-custom-biru-muda" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div> --}}
                </div>
            </form>
    
            <!-- Button Tambah Produk -->
            <div>
                <a href="{{url ('/tambahprodukseller')}}" class="btn btn-custom-outline">Tambah Produk</a>
            </div>
        </div>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
     
            <div class="card-header py-3">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link active" data-filter="all" href="#">Semua</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" data-filter="nonaktif" href="#">Nonaktif</a>
                    </li> --}}
                </ul>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Kategori</th>
                                <th>Description</th>
                                <th>Stok</th>
                                <th>Price/day</th>
                                <th>Available</th>
                                <th>Images</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($data['items']))
                                    @foreach ($data['items'] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->kategori->namakategori }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->stok }}</td>
                                        <td>Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td>
                                        <td>{{ $item->available ? 'Yes' : 'No' }}</td>
                                        <td>
                                            @if ($item->images)
                                                @php
                                                    // Decode JSON dan ambil gambar pertama
                                                    $images = json_decode($item->images, true);
                                                    $firstImage = $images[0] ?? null; // Ambil gambar pertama atau null jika tidak ada
                                                @endphp
    
                                                @if ($firstImage)
                                                    <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item->name }}" width="50">
                                                @else
                                                    <img src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="{{ $item->name }}" width="50">
                                                @endif
                                            @else
                                                <img src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="{{ $item->name }}" width="50">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('/produkseller/'.$item->id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="{{ url('/hapusprodukseller/'.$item->id) }}" class="btn btn-danger btn-sm">Hapus</a>

                                        
                                        @if ($item->stok == 0)
                                            <i class="fas fa-exclamation-circle text-danger" onclick="showStockAlert({{ $item->id }})"></i>
                                        @endif

                                        
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
    
                            
                        </tbody>
                    </table>
                    @if (isset($data['items']))
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="dataTables_info">
                                Showing {{ $data['items']->firstItem() }} to {{ $data['items']->lastItem() }} 
                                of {{ $data['items']->total() }} entries
                            </div>
                            <div>
                                @if ($data['items']->hasPages())
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($data['items']->onFirstPage())
                                            <li class="paginate_button page-item previous disabled">
                                                <span class="page-link"><i class="fas fa-angle-left"></i></span>
                                            </li>
                                        @else
                                            <li class="paginate_button page-item previous">
                                                <a class="page-link" href="{{ $data['items']->previousPageUrl() }}" rel="prev">
                                                    <i class="fas fa-angle-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($data['items']->getUrlRange(1, $data['items']->lastPage()) as $page => $url)
                                            @if ($page == $data['items']->currentPage())
                                                <li class="paginate_button page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="paginate_button page-item">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($data['items']->hasMorePages())
                                            <li class="paginate_button page-item next">
                                                <a class="page-link" href="{{ $data['items']->nextPageUrl() }}" rel="next">
                                                    <i class="fas fa-angle-right"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="paginate_button page-item next disabled">
                                                <span class="page-link"><i class="fas fa-angle-right"></i></span>
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    
    
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // $(window).on('load', function() {
                function fetchProducts(filter = 'all', query = '') {
                    $.ajax({
                        url: "{{ url('/fetchproducts') }}",
                        method: 'GET',
                        data: { filter: filter, query: query },
                        success: function(response) {
                            if (Array.isArray(response.products)) {
                                $('tbody').html(
                                    response.products.map(product => {
                                        // Decode JSON images
                                        let images = [];
                                        try {
                                            images = JSON.parse(product.images) || [];
                                        } catch (e) {
                                            console.error('Error decoding images JSON:', e);
                                        }
                                        const firstImage = images[0] ? `{{ asset('storage') }}/${images[0]}` : "https://dummyimage.com/450x300/dee2e6/6c757d.jpg";
    
                                        return `
                                            <tr>
                                                <td>${response.products.indexOf(product) + 1}</td>
                                                <td>${product.name}</td>
                                                <td>${product.kategori.namakategori}</td>
                                                <td>${product.description}</td>
                                                <td>${product.stok}</td>
                                                <td>Rp ${new Intl.NumberFormat('id-ID').format(product.price_per_day)}</td>
                                                <td>${product.available ? 'Yes' : 'No'}</td>
                                                <td>
                                                    <img src="${firstImage}" alt="${product.name}" width="50">
                                                </td>
                                                <td>
                                                    <a href="/produkseller/${product.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="/hapusprodukseller/${product.id}" method="POST" style="display:inline;">
                                                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        `;
                                    }).join('')
                                );
                            } else {
                                $('tbody').html('<tr><td colspan="6">No products found</td></tr>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            $('tbody').html('<tr><td colspan="6">An error occurred while fetching products.</td></tr>');
                        }
                    });
                }
    
    
    
        
                // Panggil data awal
                fetchProducts();
        
                // Pencarian
                $('.navbar-search input').on('keyup', function() {
                    var query = $(this).val();
                    var filter = $('.nav-link.active').data('filter') || 'all';
                    fetchProducts(filter, query);
                });
        
                // Filter
                $('.nav-link').on('click', function(e) {
                    // e.preventDefault(); ini yang jdi masalah href nya jdi mati
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');
        
                    var filter = $(this).data('filter') || 'all';
                    var query = $('.navbar-search input').val();
                    fetchProducts(filter, query);
                    // sok tinggal mau d gimanain okeh suwun bang
                });
            // });
        </script>
        <script>
            function showStockAlert(productId) {
                $.ajax({
                    url: "{{ url('/productinfo') }}/" + productId, // Endpoint untuk mendapatkan informasi produk
                    method: 'GET',
                    success: function(response) {
                        // Cek apakah stok produk kosong berdasarkan response dari server

                        console.log("data response : ", response.product.stok);
                        if (response.product.stok === 0) { 
                            Swal.fire({
                                icon: 'warning',
                                title: 'Produk Kosong',
                                text: 'Produk kosong. Harap tambahkan stok.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Produk Tersedia',
                                text: 'Produk tersedia dengan stok: ' + response.product.stok,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat mengambil informasi produk.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        </script>
        
        