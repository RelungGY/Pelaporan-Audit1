<div class="container mt-4">
    <h2>Detail Pengguna</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Detail Pengguna -->
    <table class="table">
        <tr>
            <th>Nama</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Nomor Telepon</th>
            <td>{{ $user->phone }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>{{ $user->alamat }}</td>
        </tr>
        <tr>
            <th>Foto Wajah</th>
            <td>
                <img src="{{ asset('storage/' . $user->foto_wajah) }}" alt="Foto Wajah" width="150">
            </td>
        </tr>
        <tr>
            <th>Foto KTP</th>
            <td>
                <img src="{{ asset('storage/' . $user->foto_ktp) }}" alt="Foto KTP" width="150">
            </td>
        </tr>
        <tr>
            <th>Foto Tanda Tangan</th>
            <td>
                <img src="{{ asset('storage/' . $user->foto_ttd) }}" alt="Foto Tanda Tangan" width="150">
            </td>
        </tr>
        <tr>
            <th>Status Verifikasi</th>
            <td>
                <span class="badge {{ $user->status_verifikasi === 'verified' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($user->status_verifikasi) }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Daftar Denda -->
    <h4 class="mt-5">Riwayat Denda</h4>
    @if($fines->isEmpty())
        <p>Belum ada denda yang diajukan pengguna ini.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Bukti Pembayaran</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fines as $fine)
                    <tr>
                        <td>{{ $fine->id }}</td>
                        <td>{{ $fine->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <span class="badge {{ $fine->status === 'approved' ? 'bg-success' : ($fine->status === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($fine->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ asset('storage/' . $fine->payment_proof) }}" target="_blank" class="btn btn-sm btn-secondary">Lihat Bukti</a>
                        </td>
                        <td>
                            <form action="{{ route('admin.user.update-statusbanned', $fine->id) }}" method="POST" id="statusForm-{{ $fine->id }}">
                                @csrf
                                @method('PUT')
                                <select name="status_verifikasi" class="form-select form-select-sm status-select" data-fine-id="{{ $fine->id }}">
                                    <option value="pending" {{ $fine->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $fine->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $fine->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary mt-1">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Modal Alasan Penolakan -->
<div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectReasonModalLabel">Alasan Penolakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="rejectReasonForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Alasan</label>
                        <textarea name="message" id="rejectReason" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const fineId = this.dataset.fineId;
            const selectedValue = this.value;

            if (selectedValue === 'rejected') {
                // Set the action URL dynamically for the reject reason form
                const formAction = `{{ url('admin/user/update-statusbanned/') }}/${fineId}`;
                document.getElementById('rejectReasonForm').action = formAction;
                // Show the modal
                const rejectModal = new bootstrap.Modal(document.getElementById('rejectReasonModal'));
                rejectModal.show();
            }
        });
    });
</script>
