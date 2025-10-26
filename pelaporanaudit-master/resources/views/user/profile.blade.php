@if(Auth::check())
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar mb-4">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ Auth::user()->images ? asset('storage/' . Auth::user()->images) : 'https://via.placeholder.com/40' }}" class="rounded-circle me-2" alt="Profile" width="40">
                        <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                        @if (Auth::user()->hukuman !== 'banned')
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary ms-3">Edit</a>
                        @endif
                        <a href="#" class="btn btn-outline-secondary ms-3 position-relative" data-bs-toggle="modal" data-bs-target="#notificationModal">
                            <i class="bi bi-bell-fill"></i>
                                                        @if(isset($data['notificationCount']) && $data['notificationCount'] > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $data['notificationCount'] }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            @endif
                        </a>
                        @if (Auth::user()->hukuman === 'banned')
                            @php
                                $fine = \App\Models\Fine::where('user_id', Auth::id())->where('status', 'pending')->first();
                            @endphp
                            @if ($fine)
                                <span class="badge bg-warning">Permintaan sedang diajukan</span>
                            @else
                                <!-- Tombol untuk memunculkan modal -->
                                <a href="#" class="btn btn-outline-danger ms-3 position-relative" data-bs-toggle="modal" data-bs-target="#bannedModalpembayaran">
                                    <i class="bi bi-x-circle"></i> Ajukan Pembayaran Denda
                                </a>
                            @endif
                        @endif
                    </div>
                    

                    @if (Auth::user()->hukuman !== 'banned')
                        @if (strtolower(Auth::user()->status_verifikasi) === 'pending')
                            <span class="badge bg-warning">Verifikasi Pending</span>
                        @elseif (strtolower(Auth::user()->status_verifikasi) === 'verified')
                            <span class="badge bg-success">Terverifikasi</span>
                        @elseif (strtolower(Auth::user()->status_verifikasi) === 'rejected')
                            <span class="badge bg-danger">Verifikasi Ditolak</span>
                        @endif
                        <!-- Tombol Verifikasi Data -->
                        {{-- <button class="btn btn-outline-success ms-3" data-bs-toggle="modal" data-bs-target="#verifikasiModal">
                            <i class="fas fa-check-circle"></i> Verifikasi Data
                        </button> --}}
                    @endif

                <hr>
                <a href="{{ route('logout') }}" class="btn btn-outline-danger ms-3"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                     @csrf
                </form>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9">
                <h4 class="mb-4">Daftar Transaksi</h4>
                
                <!-- Search and Filter -->
                <div class="row mb-4">
                 
                    {{-- <div class="col-md-4">
                        <button class="btn btn-outline-secondary w-100">
                            <i class="far fa-calendar"></i> Pilih Tanggal Transaksi
                        </button>
                    </div> --}}
                </div>
                
                <!-- Status Pills -->
                <div class="d-flex mb-4">
                    <div class="status-pill active">Semua</div>
                    <div class="status-pill">Belum Dibayar</div>
                    <div class="status-pill">Sudah Dibayar</div>
                    <div class="status-pill">Sedang Dikirim</div>
                    <div class="status-pill">Selesai</div>
                    <div class="status-pill">Dikembalikan</div>
                </div>
                
                <!-- Transactions List -->
                @if($transactions->isEmpty())
                    <!-- Empty State -->
                    <div class="empty-state">
                        <img src="https://via.placeholder.com/300" alt="No Transactions">
                        <h4>Oops, nggak ada transaksi yang sesuai filter</h4>
                        <p class="text-muted mb-4">Coba reset atau ubah filter kamu, ya.</p>
                    </div>
                @else
                    <div class="transactions-list">
                        
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Gambar</th>
                                <th scope="col">Nama Item</th>
                                <th scope="col">Tanggal Pinjam</th>
                                <th scope="col">Tanggal Kembalikan</th>
                                <th scope="col">Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            {{-- @php
                                dd($transaction->user_id);
                            @endphp --}}
                                <tr>
                                    <td>@if ($transaction->item->images)
                                        @php
                                            // Decode JSON dan ambil gambar pertama
                                            $images = json_decode($transaction->item->images, true);
                                            $firstImage = $images[0] ?? null; // Ambil gambar pertama atau null jika tidak ada
                                        @endphp

                                        @if ($firstImage)
                                            <img src="{{ asset('storage/' . $firstImage) }}" alt="{{ $transaction->item->name }}" width="50">
                                        @else
                                            <img src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="{{ $transaction->item->name }}" width="50">
                                        @endif
                                    @else
                                        <img src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="{{ $transaction->item->name }}" width="50">
                                    @endif</td>
                                    <td>{{ $transaction->item->name }}</td>
                                    <td>{{ $transaction->start_date }}</td>
                                    <td>{{ $transaction->deadline }}</td>
                                    <td><span class="badge bg-success">{{ $transaction->status }}</span></td>
                                    <td>
                                        @php
                                            $isDeadlineTomorrow = \Carbon\Carbon::now()->toDateString() == $transaction->deadline;
                                            $isDeadlineExceeded = \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($transaction->deadline));
                                        @endphp
                                    
                                        @if ($isDeadlineTomorrow && $transaction->status === 'Selesai Dikirim')
                                            <button 
                                                class="btn btn-warning btn-sm alert-button" 
                                                data-id="{{ $transaction->id }}"  data-item-name="{{ $transaction->item->name }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#uploadModal">
                                                <i class="bi bi-file-earmark-arrow-up"></i> 
                                            </button>
                                        @elseif ($isDeadlineExceeded && $transaction->status === 'Selesai Dikirim')
                                            <button 
                                                class="btn btn-danger btn-sm alert-button" 
                                                data-id="{{ $transaction->id }}"  data-item-name="{{ $transaction->item->name }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#returnReminderModal">
                                                <i class="bi bi-exclamation-triangle"></i> 
                                            </button>
                                        @elseif(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($transaction->deadline), false) < -3 && $transaction->status === 'Selesai Dikirim')
                                        <button 
                                                class="btn btn-dark btn-sm alert-button" 
                                                data-id="{{ $transaction->id }}" data-item-name="{{ $transaction->item->name }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#bannedModal">
                                                <i class="bi bi-x-circle"></i> 
                                            </button>
                                        @elseif ($transaction->status === 'dikembalikan')
                                            <button 
                                                class="btn btn-success btn-sm alert-button" 
                                                data-id="{{ $transaction->id }}" data-item-name="{{ $transaction->item->name }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#successModal">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        @else
                                            <button 
                                                class="btn btn-secondary btn-sm" 
                                                disabled>
                                                <i class="bi bi-file-earmark-arrow-up"></i> 
                                            </button>
                                        @endif
                                    </td>
                                    
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                @endif
            </div>      
        </div>
    </div>

    <!-- Modal Reminder Pengembalian -->
    <div class="modal fade" id="returnReminderModal" tabindex="-1" aria-labelledby="returnReminderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnReminderModalLabel">Pengingat Pengembalian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Nama Item: <span id="reminderItemName"></span></p>
                    <p>Harap segera mengembalikan item ini karena sudah melewati batas waktu pengembalian.</p>
         
                    <form id="uploadForm" action="{{ route('transactions.uploadProofR') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="transaction_id" id="transactionId">
                        <div class="mb-3">
                            <label for="bukti_pengembalianr" class="form-label">Upload Bukti</label>
                            <input class="form-control" type="file" id="bukti_pengembalian" name="bukti_pengembalian" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Success Dikembalikan -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Pengembalian Sukses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Nama Item: <span id="successItemName"></span></p>
                    <p>Terima kasih telah mengembalikan item tepat waktu. Kami berharap Anda puas dengan layanan kami.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

                        <!-- Modal -->
                        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="notificationModalLabel">Notifikasi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if($data['notification']->isEmpty())
                                            <p class="text-center">Tidak ada notifikasi baru.</p>
                                        @else
                                            <ul class="list-group">
                                                @foreach($data['notification'] as $notification)
                                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                                        <div class="ms-2 me-auto">
                                                            <div class="fw-bold">{{ $notification->title }}</div>
                                                            {{ $notification->message }}
                                                        </div>
                                                        @if(!$notification->is_read)
                                                            <form action="{{ route('notifications.markAsReadUser', $notification->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-sm btn-success">Tandai sebagai Dibaca</button>
                                                            </form>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Modal Banned -->
                        <div class="modal fade" id="bannedModal" tabindex="-1" aria-labelledby="bannedModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="bannedModalLabel">Peringatan Banned</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Nama Item: <span id="bannedItemName"></span></p>
                                        <p>Anda telah melewati batas waktu pengembalian lebih dari 3 hari. Akun Anda akan dibanned jika tidak segera mengembalikan item ini.</p>
                                        <form id="uploadForm" action="{{ route('transactions.uploadProofB') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="transaction_id" id="transactionId">
                                            <div class="mb-3">
                                                <label for="bukti_pengembalian" class="form-label">Upload Bukti</label>
                                                <input class="form-control" type="file" id="bukti_pengembalian" name="bukti_pengembalian" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Upload</button>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Bukti Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Nama Item: <span id="itemName"></span></p>
                <form id="uploadForm" action="{{ route('transactions.uploadProof') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="transaction_id" id="transactionId">
                    <div class="mb-3">
                        <label for="bukti_pengembalian" class="form-label">Upload Bukti</label>
                        <input class="form-control" type="file" id="bukti_pengembalian" name="bukti_pengembalian" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Data -->
<div class="modal fade" id="verifikasiModal" tabindex="-1" aria-labelledby="verifikasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifikasiModalLabel">Verifikasi Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.verifikasi') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="foto_wajah" class="form-label">Foto Wajah</label>
                        <input type="file" class="form-control" id="foto_wajah" name="foto_wajah" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto_ktp" class="form-label">Foto KTP</label>
                        <input type="file" class="form-control" id="foto_ktp" name="foto_ktp" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto_ttd" class="form-label">Foto Tanda Tangan</label>
                        <input type="file" class="form-control" id="foto_ttd" name="foto_ttd" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Kirim Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="bannedModalpembayaran" tabindex="-1" aria-labelledby="bannedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bannedModalLabel">Ajukan Pembayaran Denda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Silakan baca syarat dan ketentuan berikut:</p>
                <ul>
                    <li>Denda hanya bisa dibayarkan melalui rekening berikut: <strong>123-456-789 (Bank ABC)</strong>.</li>
                    <li>Nominal denda sebesar <strong>Rp100,000</strong>.</li>
                    <li>Unggah bukti pembayaran yang valid dalam format JPG, PNG, atau PDF.</li>
                    <li style="color: red"><strong>Catatan:</strong> Pengerjaan akan memakan waktu maksimal <strong>7 hari</strong>.</li>
                </ul>
                <form id="fineForm" action="{{ route('fines.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="paymentProof" class="form-label">Unggah Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="paymentProof" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Bukti</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
            const statusPills = document.querySelectorAll('.status-pill');
            const transactionsList = document.querySelector('.transactions-list');
            const searchInput = document.querySelector('.search-input');
            
            // Updated status mapping between UI and backend
            const statusMapping = {
                'Semua': 'all',
                'Belum Dibayar': 'unpaid',
                'Sudah Dibayar': 'paid',
                'Sedang Dikirim': ['dikirim', 'perlu dikirim'],  // Array of backend statuses
                'Selesai': 'Selesai Dikirim',
                'Dikembalikan': 'dikembalikan'
            };

            // Reverse mapping for displaying status badges
            const displayStatusMapping = {
                'dikirim': 'Sedang Dikirim',
                'perlu dikirim': 'Sedang Dikirim'
            };

            // Set initial active state
            const initialActivePill = document.querySelector('.status-pill.active');
            if (initialActivePill) {
                const initialStatus = statusMapping[initialActivePill.textContent.trim()];
                fetchTransactions(initialStatus);
            }

            // Add click event for status pills
            statusPills.forEach(pill => {
                pill.addEventListener('click', function() {
                    // Remove active class from all pills
                    statusPills.forEach(p => p.classList.remove('active'));
                    // Add active class to clicked pill
                    this.classList.add('active');
                    
                    const uiStatus = this.textContent.trim();
                    const backendStatus = statusMapping[uiStatus];
                    fetchTransactions(backendStatus);
                });
            });

            // Add search functionality
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const activeStatus = document.querySelector('.status-pill.active').textContent.trim();
                    const backendStatus = statusMapping[activeStatus];
                    fetchTransactions(backendStatus, this.value);
                }, 300);
            });

            function fetchTransactions(status, search = '') {
                const queryParams = new URLSearchParams();
                
                // Handle array of statuses
                if (Array.isArray(status)) {
                    status.forEach(s => queryParams.append('status[]', s));
                } else if (status !== 'all') {
                    queryParams.append('status', status);
                }
                
                if (search) {
                    queryParams.append('search', search);
                }

                fetch(`/transactions/filter?${queryParams.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        renderTransactions(data);
                    })
                    .catch(error => console.error('Error fetching transactions:', error));
            }

            function getBadgeColor(status) {
                const colorMap = {
                    'paid': 'bg-success',
                    'unpaid': 'bg-danger',
                    'dikirim': 'bg-primary',
                    'perlu dikirim': 'bg-primary', // Same color as 'dikirim'
                    'dikembalikan': 'bg-info'
                };
                return colorMap[status.toLowerCase()] || 'bg-secondary';
            }

            function renderTransactions(transactions) {
                let html = '';

                if (transactions.length === 0) {
                    html = `
                        <div class="empty-state text-center py-5">
                            <img src="https://via.placeholder.com/300" alt="No Transactions" class="mb-3">
                            <h4>Oops, nggak ada transaksi yang sesuai filter</h4>
                            <p class="text-muted mb-4">Coba reset atau ubah filter kamu, ya.</p>
                        </div>
                    `;
                } else {
                    html = `
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Gambar</th>
                                    <th scope="col">Nama Item</th>
                                    <th scope="col">Tanggal Pinjam</th>
                                    <th scope="col">Tanggal Kembalikan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    const today = new Date();
                    const tomorrow = new Date(today);
                    transactions.forEach(transaction => {
    const badgeClass = getBadgeColor(transaction.status);
    const displayStatus = displayStatusMapping[transaction.status] || transaction.status;

    const deadlineDate = new Date(transaction.deadline);
    const isDeadlineTomorrow = deadlineDate.toDateString() === tomorrow.toDateString();
    const isDeadlineExceeded = today > deadlineDate;
    const isDeadlineExceededByMoreThanThreeDays = Math.abs((today - deadlineDate) / (1000 * 60 * 60 * 24)) > 3;

    const isDikembalikan = transaction.status.trim().toLowerCase() === 'dikembalikan';

    // Debugging

    html += `
        <tr>
            <td><img src="${transaction.item_image || 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg'}" 
                    alt="${transaction.item_name}" 
                    width="50"
                    class="img-thumbnail"></td>
            <td>${transaction.item_name}</td>
            <td>${transaction.start_date}</td>
            <td>${transaction.deadline}</td>
            <td><span class="badge ${badgeClass}">${displayStatus}</span></td>
            <td>
                ${
                    isDikembalikan ? `
                    <button 
                        class="btn btn-success btn-sm alert-button" 
                        data-id="${transaction.id}" 
                        data-item-name="${transaction.item_name}"
                        data-bs-toggle="modal" 
                        data-bs-target="#successModal">
                        <i class="bi bi-check-circle"></i>
                    </button>` 
                    : isDeadlineTomorrow && transaction.status === "Selesai Dikirim" ? `
                    <button 
                        class="btn btn-warning btn-sm alert-button" 
                        data-id="${transaction.id}" 
                        data-item-name="${transaction.item_name}"
                        data-bs-toggle="modal" 
                        data-bs-target="#uploadModal">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                    </button>` 
                    : isDeadlineExceededByMoreThanThreeDays && transaction.status === "Selesai Dikirim" ? `
                    <button 
                        class="btn btn-dark btn-sm alert-button" 
                        data-id="${transaction.id}" 
                        data-item-name="${transaction.item_name}"
                        data-bs-toggle="modal" 
                        data-bs-target="#bannedModal">
                        <i class="bi bi-x-circle"></i>
                    </button>` 
                    : isDeadlineExceeded && transaction.status === "Selesai Dikirim" ? `
                    <button 
                        class="btn btn-danger btn-sm alert-button" 
                        data-id="${transaction.id}" 
                        data-item-name="${transaction.item_name}"
                        data-bs-toggle="modal" 
                        data-bs-target="#returnReminderModal">
                        <i class="bi bi-exclamation-triangle"></i>
                    </button>` 
                    : `
                    <button 
                        class="btn btn-secondary btn-sm alert-button" 
                        data-id="${transaction.id}" 
                        disabled>
                        <i class="bi bi-file-earmark-arrow-up"></i>
                    </button>`
                }
            </td>
        </tr>
    `;
});


                    html += `
                            </tbody>
                        </table>
                    `;
                }

                transactionsList.innerHTML = html;
            }
        });
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const uploadModal = document.getElementById('uploadModal');

        uploadModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang membuka modal
            const transactionId = button.getAttribute('data-id');
            const itemName = button.getAttribute('data-item-name');

            // Isi form modal dengan data yang diambil
            const modalTransactionId = uploadModal.querySelector('#transactionId');
            const modalItemName = uploadModal.querySelector('#itemName');

            modalTransactionId.value = transactionId;
            modalItemName.textContent = itemName;
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bannedModal = document.getElementById('bannedModal');

        bannedModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang membuka modal
            const itemName = button.getAttribute('data-item-name');

            // Isi modal dengan data yang diambil
            const transactionId = button.getAttribute('data-id');

// Isi form modal dengan data yang diambil
const modalTransactionId = bannedModal.querySelector('#transactionId');
// Isi modal dengan data yang diambil

            const modalItemName = bannedModal.querySelector('#bannedItemName');
            modalItemName.textContent = itemName;
modalTransactionId.value = transactionId;

console.log('Transaction ID:', transactionId);
console.log('Item Name:', itemName);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successModal = document.getElementById('successModal');

        successModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang membuka modal
            const itemName = button.getAttribute('data-item-name');
            const transactionId = button.getAttribute('data-id');

            // Isi form modal dengan data yang diambil
            const modalTransactionId = uploadModal.querySelector('#transactionId');

            // Isi modal dengan data yang diambil
            const modalItemName = successModal.querySelector('#successItemName');
            modalItemName.textContent = itemName;
            modalTransactionId.value = transactionId;

        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const returnReminderModal = document.getElementById('returnReminderModal');

        returnReminderModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang membuka modal
            const itemName = button.getAttribute('data-item-name');
            const transactionId = button.getAttribute('data-id');

            // Isi form modal dengan data yang diambil
            const modalTransactionId = returnReminderModal.querySelector('#transactionId');
            // Isi modal dengan data yang diambil
            const modalItemName = returnReminderModal.querySelector('#reminderItemName');
            modalItemName.textContent = itemName;
            modalTransactionId.value = transactionId;
            
            console.log('Transaction ID:', transactionId);
            console.log('Item Name:', itemName);
            
        });
    });
</script>

    @else
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="alert alert-warning text-center">
                        <h4 class="alert-heading">Perhatian!</h4>
                        <p>Anda harus login untuk melihat halaman ini.</p>
                        <hr>
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    </div>
                </div>
            </div>
        </div>
    @endif