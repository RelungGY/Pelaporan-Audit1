<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Pelaporan Data</h1>
    <!-- Date Range and Export Options -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
        </div>
    </div>
    <div class="row mb-4">
        
        <form action="{{ route('report.excel') }}" method="GET">
            <input type="hidden" name="start_date" id="start_date_excel">
            <input type="hidden" name="end_date" id="end_date_excel">
            <button type="submit" class="btn btn-success"  id="export_data">Download Excel</button>
        </form>

        <form action="{{ route('report.pdf') }}" method="GET">
            <input type="hidden" name="start_date" id="start_date_pdf">
            <input type="hidden" name="end_date" id="end_date_pdf">
            <button type="submit" class="btn btn-danger" id="export_data_pdf">Download PDF</button>
        </form>
        
        
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-pills card-header-pills">
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'Semua' ? 'active' : '' }} dataclick" href="#" data-status="Semua">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'Belum Dibayar' ? 'active' : '' }} dataclick" href="#" data-status="Belum Dibayar">Belum Dibayar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'Perlu Dikirim' ? 'active' : '' }} dataclick" href="#" data-status="Perlu Dikirim">Perlu Dikirim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'Dikirim' ? 'active' : '' }} dataclick" href="#" data-status="Dikirim">Dikirim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'Selesai Dikirim' ? 'active' : '' }} dataclick" href="#" data-status="Selesai Dikirim">Selesai Dikirim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'Status Pengembalian' ? 'active' : '' }} dataclick" href="#" data-status="Status Pengembalian">Status Pengembalian</a>
                </li>
            </ul>
            
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item ID</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Deadline</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Konfirmasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @if (isset($data['transactions']))
                            @foreach ($data['transactions'] as $transaction)
                                <tr data-status="{{ $transaction->status }}">
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $transaction->item->name }}</td>
                                    <td>{{ $transaction->start_date }}</td>
                                    <td>{{ $transaction->end_date }}</td>
                                    <td>{{ $transaction->deadline }}
                                        
                                    </td>
                                    <td>{{ $transaction->total_amount }}</td>
                                    <td>
                                        @if ($transaction->status === 'perlu dikirim')
                                            <form action="{{ route('transactions.updateStatusPengiriman', $transaction->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control" onchange="this.form.submit()">
                                                    <option value="perlu dikirim" selected>Perlu Dikirim</option>
                                                    <option value="dikirim">Dikirim</option>
                                                </select>
                                            </form>
                                        @elseif ($transaction->status === 'dikirim')
                                            <form action="{{ route('transactions.updateStatusPengiriman', $transaction->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control" onchange="this.form.submit()">
                                                    <option value="dikirim">Dikirim</option>
                                                    <option value="Selesai Dikirim">Selesai Dikirim</option>
                                                </select>
                                            </form>
                                        @else
                                            {{ $transaction->status }}
                                        @endif
                                    </td>
                                    <td>{{ $transaction->status_confirm }}</td>
                                    <td><a href="{{ route('transactions.detail', $transaction->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                        @if ((\Carbon\Carbon::now()->addDay()->toDateString() === $transaction->deadline) && ($transaction->status === 'Selesai Dikirim'))
                                            <button 
                                                class="btn btn-warning btn-sm alert-button" 
                                                data-id="{{ $transaction->id }}">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </button>
                                        @endif
                                    </td>
                                
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="dataTables_info">
                        Showing {{ $data['transactions']->firstItem() }} to {{ $data['transactions']->lastItem() }} 
                        of {{ $data['transactions']->total() }} entries
                    </div>
                    <div>
                        @if ($data['transactions']->hasPages())
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($data['transactions']->onFirstPage())
                                    <li class="paginate_button page-item previous disabled">
                                        <span class="page-link"><i class="fas fa-angle-left"></i></span>
                                    </li>
                                @else
                                    <li class="paginate_button page-item previous">
                                        <a class="page-link" href="{{ $data['transactions']->previousPageUrl() }}" rel="prev">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($data['transactions']->getUrlRange(1, $data['transactions']->lastPage()) as $page => $url)
                                    @if ($page == $data['transactions']->currentPage())
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
                                @if ($data['transactions']->hasMorePages())
                                    <li class="paginate_button page-item next">
                                        <a class="page-link" href="{{ $data['transactions']->nextPageUrl() }}" rel="next">
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
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    function loadPage(url) {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableBody = doc.querySelector('#dataTable tbody');
                const newPagination = doc.querySelector('.pagination');
                const newInfo = doc.querySelector('.dataTables_info');

                document.querySelector('#dataTable tbody').innerHTML = newTableBody.innerHTML;
                document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
                document.querySelector('.dataTables_info').innerHTML = newInfo.innerHTML;

                attachPaginationEvents();
            });
    }

    function attachPaginationEvents() {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                loadPage(this.href);
            });
        });
    }

    // Filter click events
    const filterLinks = document.querySelectorAll('.dataclick');
    filterLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const status = this.getAttribute('data-status');
            
            fetch(`?status=${status}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.querySelector('#dataTable tbody');
                    document.querySelector('#dataTable tbody').innerHTML = newTableBody.innerHTML;
                    
                    filterLinks.forEach(link => link.classList.remove('active'));
                    this.classList.add('active');
                });
        });
    });

    // Export button events
    document.getElementById('export_data').addEventListener('click', function () {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        document.getElementById('start_date_excel').value = startDate;
        document.getElementById('end_date_excel').value = endDate;
    });

    document.getElementById('export_data_pdf').addEventListener('click', function () {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        document.getElementById('start_date_pdf').value = startDate;
        document.getElementById('end_date_pdf').value = endDate;
    });

    attachPaginationEvents();
});


    </script>

    
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertButtons = document.querySelectorAll('.alert-button');

        alertButtons.forEach(button => {
            button.addEventListener('click', function () {
                const transactionId = this.getAttribute('data-id');
                
                if (confirm('Apakah Anda yakin ingin mengirim notifikasi ke user?')) {
                    fetch(`/transactions/alertuser/${transactionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    });
                }
            });
        });
    });
</script>
</div>
