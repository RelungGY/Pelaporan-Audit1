
<div class="container">
    <h1>Daftar Pesanan</h1>
    <a href="{{ route('service.create') }}" class="btn btn-primary mb-3">Tambah Pesanan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
            <th>Nama Pelanggan</th>
            <th>Jenis Layanan</th>
            <th>Total Harga</th>
            <th>Status Pembayaran</th>
            <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemesanan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_pelanggan }}</td>
                    <td>{{ $item->jenis_layanan }}</td>
                    <td>{{ 'Rp ' . number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $item->status_pembayaran }}</td>
                    <td>
                        <a href="{{ route('service.show', $item->id) }}" class="btn btn-info">Detail</a>
                        <a href="{{ route('service.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                        {{-- <form action="{{ route('service.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus service?')">Hapus</button>
                        </form> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


@if(session('download'))
        <script>
            window.onload = function() {
                const fileName = "{{ session('download') }}";
                const link = document.createElement('a');
                link.href = "{{ asset('storage') }}/" + fileName;
                link.download = fileName;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        </script>
    @endif