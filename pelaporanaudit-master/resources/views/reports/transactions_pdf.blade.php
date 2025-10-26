<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Transaksi</h2>
    <p>Periode: {{ $startDate }} - {{ $endDate }}</p>
    <table>
        <thead>
            <tr>
                <th>Item ID</th>
                <th>Admin ID</th>
                <th>Service ID</th>
                <th>Seller ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Deadline</th>
                <th>Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->item_id }}</td>
                    <td>{{ $transaction->admin_id }}</td>
                    <td>{{ $transaction->service_id }}</td>
                    <td>{{ $transaction->seller_id }}</td>
                    <td>{{ $transaction->start_date }}</td>
                    <td>{{ $transaction->end_date }}</td>
                    <td>{{ $transaction->deadline }}</td>
                    <td>{{ $transaction->total_amount }}</td>
                    <td>{{ $transaction->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
