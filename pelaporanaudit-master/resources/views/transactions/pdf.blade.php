<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f4f4f4; }
        img { display: block; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Detail Transaksi</h2>
    </div>
    <p><strong>ID Transaksi:</strong> {{ $transaction->id }}</p>
    <p><strong>Tanggal Transaksi:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($transaction->status_confirm) }}</p>
    <p><strong>Total Harga:</strong> Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
    <hr>
    <h4>Informasi Item</h4>
    <p><strong>Nama Item:</strong> {{ $transaction->item->name }}</p>
    <p><strong>Harga per Hari:</strong> Rp{{ number_format($transaction->item->price_per_day, 0, ',', '.') }}</p>
    <p><strong>Deskripsi:</strong> {{ $transaction->item->description }}</p>

    <div>
        <strong>Gambar:</strong><br>
        @php
            $images = json_decode($transaction->item->images, true);
        @endphp
        @if ($images)
            @foreach ($images as $image)
                @php
                    $imagePath = public_path('storage/' . $image);
                    $imageData = file_exists($imagePath) ? base64_encode(file_get_contents($imagePath)) : null;
                @endphp
                @if ($imageData)
                    <img src="data:image/png;base64,{{ $imageData }}" alt="{{ $transaction->item->name }}" width="150" style="margin-right: 10px; margin-bottom: 10px;">
                @endif
            @endforeach
        @else
            <p class="text-muted">Tidak ada gambar.</p>
        @endif
    </div>
</body>
</html>
