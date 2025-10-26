<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kategori Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.4em 0.6em;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Data Kategori Admin</h1>
        
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
                            @foreach($kategori as $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->namakategori }}</td>
                                <td>
                                    <span class="{{ $data->status === 'disetujui' ? 'badge bg-success' : ($data->status === 'ditolak' ? 'badge bg-danger' : 'badge bg-secondary') }}">
                                        {{ ucfirst($data->status) }}
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
            fetch('{{ route('kategoriadmin.updateStatus') }}', {
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
</body>
</html>
