<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Penyewaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .details {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .note {
            margin-top: 20px;
            font-size: 14px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice Penyewaan</h1>
        <p><strong>{{ now()->format('d M Y') }}</strong></p>
    </div>

    <div class="details">
        <p><strong>Nama Pelanggan:</strong> {{ $pemesanan->nama_pelanggan }}</p>
        <p><strong>Nomor Telepon:</strong> {{ $pemesanan->nomor_telepon }}</p>
        <p><strong>Tanggal Pemesanan:</strong> {{ $pemesanan->tanggal_pemesanan }}</p>
        <p><strong>Tanggal Event:</strong> {{ $pemesanan->tanggal_event }}</p>
        <p><strong>Jenis Layanan:</strong> {{ $pemesanan->jenis_layanan }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $pemesanan->jumlah_pesanan }}</td>
                <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Total Harga:</strong> Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
        <p><strong>Status Pembayaran:</strong> {{ $pemesanan->status_pembayaran }}</p>
    </div>

    <div class="note">
        <p>Catatan: Harap membawa invoice ini saat pengembalian barang.</p>
    </div>
</body>
</html>
