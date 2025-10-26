<div class="container py-5">
    <h2 class="mb-4">Detail Transaksi</h2>

    <div class="card mb-4">
        <div class="card-header">
            Informasi Transaksi
        </div>
        <div class="card-body">
            <p><strong>ID Transaksi:</strong> {{ $transaction->id }}</p>
            <p><strong>Tanggal Transaksi:</strong> {{ $transaction->created_at->format('d M Y, H:i') }}</p>
            <p>
                <strong>Status:</strong><span class="badge 
                @if($transaction->status_confirm === 'pending') bg-warning 
                @elseif($transaction->status_confirm === 'confirmed') bg-success 
                @else bg-danger @endif">
                {{ ucfirst($transaction->status_confirm) }}
            </span>


            </p>
            <p><strong>Total Harga:</strong> Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            Informasi Item
        </div>
        <div class="card-body">
            <p><strong>Nama Item:</strong> {{ $transaction->item->name }}</p>
            <p><strong>Harga per Hari:</strong> Rp{{ number_format($transaction->item->price_per_day, 0, ',', '.') }}</p>
            <p><strong>Jumlah:</strong> {{ $transaction->total_amount / $transaction->item->price_per_day }}</p>
            <p><strong>Deskripsi:</strong> {{ $transaction->item->description }}</p>
            <div>
                <strong>Gambar:</strong><br>
                @php
                    $images = json_decode($transaction->item->images, true);
                @endphp
                @if ($images)
                    @foreach ($images as $image)
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $transaction->item->name }}" width="100" class="me-2">
                    @endforeach
                @else
                    <p class="text-muted">Tidak ada gambar.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            Bukti Pembayaran
        </div>
        <div class="card-body">
            @if ($transaction->bukti_pembayaran)
                <p><strong>Bukti Pembayaran:</strong></p>
                <img src="{{ asset('storage/' . $transaction->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="img-fluid mb-3" width="300">
            @else
                <p class="text-muted">Belum ada bukti pembayaran.</p>
            @endif
        </div>
    </div>

</div>