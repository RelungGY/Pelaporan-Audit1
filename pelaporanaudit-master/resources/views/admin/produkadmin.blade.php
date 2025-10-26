
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Produk Admin</h1>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
           
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->name }}</td>
                                <td>
                                    <span class="{{ $data->status === 'disetujui' ? 'badge bg-success' : ($data->status === 'ditolak' ? 'badge bg-danger' : 'badge bg-warning') }}">
                                        {{ ucfirst($data->status ?? 'pending') }}
                                    </span>      
                                </td>
                                <td>
                                    @if ($data->items_count > 0)
                                        <span class="text-muted">Kategori Masih Terpakai</span>
                                    @else
                                        <button class="btn btn-success btn-sm" onclick="updateStatus({{ $data->id }}, 'disetujui')">✔</button>
                                        <button class="btn btn-danger btn-sm" onclick="updateStatus({{ $data->id }}, 'ditolak')">✖</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStatus(id, status) {
            fetch('{{ route('produkadmin.updateStatus') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id, status })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
