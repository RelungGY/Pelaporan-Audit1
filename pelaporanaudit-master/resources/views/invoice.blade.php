<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        .details td { padding: 5px 10px; }
        .footer { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Invoice</h2>
            <p>Order ID: {{ $transaction->id }}</p>
        </div>
        <div class="details">
            <table>
                <tr>
                    <td><strong>Nama:</strong></td>
                    <td>{{ $transaction->user->name }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $transaction->user->email }}</td>
                </tr>
                <tr>
                    <td><strong>Produk:</strong></td>
                    <td>{{ $transaction->item->name }}</td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>Terima kasih telah menyewa!</p>
        </div>
    </div>
</body>
</html>
