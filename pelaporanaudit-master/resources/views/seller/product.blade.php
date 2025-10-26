 <!-- Begin Page Content -->
 <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Produk Data</h1>

    <!-- Topbar Search -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-1 small bg-white" placeholder="Search for..."
                    aria-label="Search" aria-describedby="basic-addon2">
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
                <li class="nav-item">
                    <a class="nav-link" data-filter="nonaktif" href="#">Nonaktif</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price/day</th>
                            <th>Available</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($data['items']))
                        @foreach ($data['items'] as $item)
                            {{-- @dd($item) --}}
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td>
                                <td>{{ $item->available ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="{{ url('/produkseller/'.$item->id.'/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ url('/hapusprodukseller/'.$item->id) }}" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(window).on('load', function() {
                    function fetchProducts(filter = 'all', query = '') {
                        $.ajax({
                            url: "{{ url('/fetchproducts') }}",
                            method: 'GET',
                            data: { filter: filter, query: query },
                            success: function(response) {
                                $('tbody').html(response.products.map(product => `
                                    <tr>
                                        <td>${product.name}</td>
                                        <td>${product.description}</td>
                                        <td>Rp ${new Intl.NumberFormat('id-ID').format(product.price_per_day)}</td>
                                        <td>${product.available ? 'Yes' : 'No'}</td>
                                        <td>
                                            <a href="{{ url('/produkseller/') }}/${product.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ url('/hapusprodukseller/') }}/${product.id}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                `).join('')); // Update tabel dengan hasil dari server
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
                        e.preventDefault();
                        $('.nav-link').removeClass('active');
                        $(this).addClass('active');
            
                        var filter = $(this).data('filter') || 'all';
                        var query = $('.navbar-search input').val();
                        fetchProducts(filter, query);
                    });
                });
            </script>