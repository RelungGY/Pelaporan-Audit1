<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detail Penyewaan</h1>
    </div>
    <div class="content">
        <p><strong>Nama Pelanggan:</strong> {{ $pemesanan->nama_pelanggan }}</p>
        <p><strong>Nomor Telepon:</strong> {{ $pemesanan->nomor_telepon }}</p>
        <p><strong>Tanggal Pemesanan:</strong> {{ $pemesanan->tanggal_pemesanan }}</p>
        <p><strong>Tanggal Event:</strong> {{ $pemesanan->tanggal_event }}</p>
        <p><strong>Jenis Layanan:</strong> {{ $pemesanan->jenis_layanan }}</p>
        <p><strong>Jumlah Pesanan:</strong> {{ $pemesanan->jumlah_pesanan }}</p>
        <p><strong>Total Harga:</strong> Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
        <p><strong>Status Pembayaran:</strong> {{ $pemesanan->status_pembayaran }}</p>
        <p><strong>Keterangan:</strong> {{ $pemesanan->keterangan }}</p>
        <p><strong>Status Pengembalian:</strong> {{ $pemesanan->status_pengembalian }}</p>

        <h3>Detail Item</h3>
        <p><strong>Nama Item:</strong> {{ $item->name }}</p>
        <p><strong>Deskripsi:</strong> {{ $item->description }}</p>
        <p><strong>Kategori:</strong> {{ $item->kategori->namakategori }}</p>
        @php
            $images = json_decode($item->images, true);
            $firstImage = $images[0] ?? null;
        @endphp
        @if($firstImage)
            <p><img src="{{ public_path('storage/' . $firstImage) }}" alt="{{ $item->name }}" style="width: 150px; height: auto;"></p>
        @endif
    </div>
</body>
</html>
