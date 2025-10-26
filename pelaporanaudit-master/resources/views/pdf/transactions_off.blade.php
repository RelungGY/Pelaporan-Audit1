<!DOCTYPE html>
<html>
<head>
    <title>Transactions Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Transactions Report</h1>
    <table>
        <thead>
            <tr>
            <th>ID</th>
            <th>Nama Pelanggan</th>
            <th>Item ID</th>
            <th>Nomor Telepon</th>
            <th>Tanggal Pemesanan</th>
            <th>Tanggal Event</th>
            <th>Jenis Layanan</th>
            <th>Total Harga</th>
            <th>Status Pembayaran</th>
            <th>Keterangan</th>
            <th>Jumlah Pesanan</th>
            <th>Status Pengembalian</th>
            <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($service as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->nama_pelanggan }}</td>
                <td>{{ $transaction->item_id }}</td>
                <td>{{ $transaction->nomor_telepon }}</td>
                <td>{{ $transaction->tanggal_pemesanan }}</td>
                <td>{{ $transaction->tanggal_event }}</td>
                <td>{{ $transaction->jenis_layanan }}</td>
                <td>Rp. {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                <td>{{ $transaction->status_pembayaran }}</td>
                <td>{{ $transaction->keterangan }}</td>
                <td>{{ $transaction->jumlah_pesanan }}</td>
                <td>{{ $transaction->status_pengembalian }}</td>
                <td>{{ $transaction->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
