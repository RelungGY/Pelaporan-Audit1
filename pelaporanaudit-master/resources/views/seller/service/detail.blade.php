
<div class="container">
    <div class="row">

        <div class="col-md-8">
            <h1>{{ $item->name }}</h1>
            <p>{{ $item->description }}</p>
            <p><strong>Price:</strong> Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p> 
             <p><strong>Category:</strong> {{ $item->kategori->namakategori }}</p>
        </div>
        <div class="col-md-4">
            @php
            // Decode JSON dan ambil gambar pertama
            $images = json_decode($item->images, true);
            $firstImage = $images[0] ?? null; // Ambil gambar pertama atau null jika tidak ada
        @endphp
            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $item->name }}" class="img-fluid" width="20%">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Details</h6>
                    <a href="{{ route('service.pdf', $pemesanan->id) }}" class="btn btn-primary">Cetak PDF</a>

                </div>
                <div class="card-body">
                    <p><strong>Nama Pelanggan:</strong> {{ $pemesanan->nama_pelanggan }}</p>
                    <p><strong>Item ID:</strong> {{ $pemesanan->item_id }}</p>
                    <p><strong>Nomor Telepon:</strong> {{ $pemesanan->nomor_telepon }}</p>
                    <p><strong>Tanggal Pemesanan:</strong> {{ $pemesanan->tanggal_pemesanan }}</p>
                    <p><strong>Tanggal Event:</strong> {{ $pemesanan->tanggal_event }}</p>
                    <p><strong>Jenis Layanan:</strong> {{ $pemesanan->jenis_layanan }}</p>
                    <p><strong>Total Harga:</strong> Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>  <p><strong>Status Pembayaran:</strong> {{ $pemesanan->status_pembayaran }}</p>
                    <p><strong>Keterangan:</strong> {{ $pemesanan->keterangan }}</p>
                    <p><strong>Jumlah Pesanan:</strong> {{ $pemesanan->jumlah_pesanan }}</p>
                    <p><strong>Status Pengembalian:</strong> {{ $pemesanan->status_pengembalian }}</p>
                </div>
            </div>
            <h3>Reviews</h3>
            {{-- @foreach($pemesanan->reviews as $review)
                <div class="review">
                    <p><strong>{{ $review->user->name }}</strong> ({{ $review->created_at->format('d M Y') }})</p>
                    <p>{{ $review->comment }}</p>
                    <p><strong>Rating:</strong> {{ $review->rating }}/5</p>
                </div>
                <hr>
            @endforeach --}}
        </div>
    </div>
</div>
