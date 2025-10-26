@if (Auth::check())
<div class="container py-5">
    <h2 class="mb-4">Keranjang</h2>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="row">
        <!-- Main Cart Content -->
        <div class="col-md-8">
            @if($transactions->isEmpty())
                <div class="empty-cart-container text-center">
                    <img src="https://via.placeholder.com/120" alt="Empty Cart" class="cart-img">
                    <h3>Wah, keranjang belanjamu kosong</h3>
                    <p class="text-muted mb-4">Yuk, isi dengan barang-barang impianmu!</p>
                    <a href="" class="btn btn-primary">Mulai Belanja</a>
                </div>
            @else
                <div class="cart-items">
                    @foreach($transactions as $transaction)
                        <div class="cart-item d-flex align-items-center mb-4 border-bottom pb-3">
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" name="selected_items[]" value="{{ $transaction->id }}" id="transaction-{{ $transaction->id }}" data-amount="{{ $transaction->total_amount }}">
                                <label class="form-check-label" for="transaction-{{ $transaction->id }}"></label>
                            </div>
                            
                            <div class="cart-item-img me-3">
                                <!-- Gambar Barang -->
                                @php
                                    $images = json_decode($transaction->item->images, true);
                                    $firstImage = $images[0] ?? null;
                                @endphp
                                @if ($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $transaction->item->name }}" width="50">
                                @else
                                    <img src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="{{ $transaction->item->name }}" width="50">
                                @endif
                            </div>
                            <div class="cart-item-details flex-grow-1">
                                <h5 class="mb-2">{{ $transaction->item->name }}</h5>
                                <p class="text-muted mb-1">Harga: Rp{{ number_format($transaction->item->price_per_day, 0, ',', '.') }}</p>
                                <p class="text-muted">Jumlah: {{ $transaction->total_amount / $transaction->item->price_per_day  }}</p>
                            </div>
                            <div class="cart-item-actions">
                                <p class="fw-bold mb-0">Rp{{ number_format($transaction->total_amount) }}</p>
                                <form action="{{ route('cart.remove', $transaction->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mt-2">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Cart Summary -->
        <div class="col-md-4">
            @if(!$transactions->isEmpty())
            <div class="cart-summary p-3 bg-light rounded">
                <h5 class="mb-4">Ringkasan Belanja</h5>
                <div class="d-flex justify-content-between mb-3">
                <span>Sub Total</span>
                <span id="subtotal">Rp0</span>
                </div>
                <button class="btn btn-custom-outline w-100">Beli</button>
            </div>
            @endif
        </div>

       
    </div>
</div>

<!-- Modal untuk Upload Bukti Pembayaran -->
<!-- Modal untuk Upload Bukti Pembayaran -->
<div class="modal fade" id="uploadProofModal" tabindex="-1" aria-labelledby="uploadProofModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('upload.payment.proof') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadProofModalLabel">Unggah Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Barang yang Dibayar:</label>
                        <div>
                            @foreach($transactions as $transaction)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="item_ids[]" value="{{ $transaction->item->id }}" id="item-{{ $transaction->item->id }}">
                                    <label class="form-check-label" for="item-{{ $transaction->item->id }}">
                                        {{ $transaction->item->name }} (Rp{{ number_format($transaction->total_amount) }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Unggah</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    $('.btn-custom-outline').click(function (e) {
    e.preventDefault();

    // Ambil ID produk yang dichecklist
    let selectedItems = [];
    $('input[name="selected_items[]"]:checked').each(function () {
        selectedItems.push($(this).val());
    });

    // Validasi: Pastikan ada produk yang dipilih
    if (selectedItems.length === 0) {
        alert('Silakan pilih produk yang ingin dibeli!');
        return;
    }

    // Kirim data ke server
    $.ajax({
        url: '{{ route("cart.checkout") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            selected_items: selectedItems
        },
        success: function (response) {
            snap.pay(response.snap_token, {
                onSuccess: function (result) {
                    alert('Transaksi berhasil! Silakan screenshot halaman ini sebagai bukti pembayaran Anda.');
                    $('#uploadProofModal').modal('show');
                },
                onPending: function (result) {
                    alert('Menunggu pembayaran...');
                },
                onError: function (result) {
                    alert('Terjadi kesalahan!');
                }
            });
        },
        error: function () {
            alert('Terjadi kesalahan, silakan coba lagi.');
        }
    });
});

</script>
<script>
    $('#uploadProofModal form').submit(function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.success) {
                alert(response.message);
                // Auto-download invoice
                window.location.href = response.download_url;
                $('#uploadProofModal').modal('hide');
                // Refresh the page after a short delay to ensure the download starts
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        },
        error: function (xhr) {
            alert('Gagal mengunggah bukti pembayaran. Silakan coba lagi.');
        }
    });
});

</script>
<script>
    $(document).ready(function() {
    $('input[name="selected_items[]"]').change(function() {
        let subtotal = 0;
        $('input[name="selected_items[]"]:checked').each(function() {
        let transactionId = $(this).val();
        let transactionAmount = parseFloat($('input#transaction-' + transactionId).data('amount'));
        subtotal += transactionAmount;
        });
        $('#subtotal').text('Rp' + subtotal.toLocaleString('id-ID'));
    });
    });
</script>
@else
<div class="container py-5 text-center">
    <h2 class="mb-4">Keranjang</h2>
    <p class="text-muted mb-4">Silakan <a href="{{ route('login') }}">login</a> untuk melihat keranjang belanja Anda.</p>
</div>
@endif
