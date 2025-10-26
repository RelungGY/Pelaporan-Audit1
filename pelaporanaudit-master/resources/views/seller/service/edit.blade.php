<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Kategori</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('service.update', $service->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama Pelanggan:</label>
            <input type="text" name="nama_pelanggan" class="form-control" value="{{ $service->nama_pelanggan }}" required>
        </div>

        <div class="form-group">
            <label>Nomor Telepon:</label>
            <input type="text" name="nomor_telepon" class="form-control" value="{{ $service->nomor_telepon }}">
        </div>

        <div class="form-group">
            <label>Pilih Item:</label>
            <select name="item_id" class="form-control">
                <option value="">-- Pilih Item --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ $service->item_id == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tanggal Pemesanan:</label>
            <input type="date" name="tanggal_pemesanan" class="form-control" value="{{ $service->tanggal_pemesanan }}" required>
        </div>

        <div class="form-group">
            <label>Tanggal Event:</label>
            <input type="date" name="tanggal_event" class="form-control" value="{{ $service->tanggal_event }}">
        </div>

        <div class="form-group">
            <label>Jenis Layanan:</label>
            <input type="text" name="jenis_layanan" class="form-control" value="{{ $service->jenis_layanan }}" required>
        </div>

        <div class="form-group">
            <label>Jumlah Pesanan:</label>
            <input type="number" name="jumlah_pesanan" class="form-control" value="{{ $service->jumlah_pesanan }}" min="1" required>
        </div>

        <div class="form-group">
            <label>Total Harga:</label>
            <input type="number" name="total_harga" class="form-control" value="{{ $service->total_harga }}" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Status Pembayaran:</label>
            <select name="status_pembayaran" class="form-control" required>
                <option value="Belum Dibayar" {{ $service->status_pembayaran == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="Sudah Dibayar" {{ $service->status_pembayaran == 'Sudah Dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
            </select>
        </div>

        <div class="form-group">
            <label>Keterangan:</label>
            <textarea name="keterangan" class="form-control">{{ $service->keterangan }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>