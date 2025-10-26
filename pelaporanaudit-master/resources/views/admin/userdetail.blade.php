
<div class="container mt-4">
    <h2>Detail User</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
            <th>Phone</th>
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
                <img src="{{ asset('storage/' . $user->foto_ttd) }}" alt="Foto TTD" width="150">
            </td>
        </tr>
        <tr>
            <th>Status Verifikasi</th>
            <td>{{ $user->status_verifikasi }}</td>
        </tr>
    </table>

    <h4>Update Status Verifikasi</h4>
    <form action="{{ route('admin.user.update-status', $user->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="status_verifikasi">Status Verifikasi</label>
            <select name="status_verifikasi" id="status_verifikasi" class="form-control">
                <option value="Verified" {{ $user->status_verifikasi == 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="reject" {{ $user->status_verifikasi == 'reject' ? 'selected' : '' }}>Reject</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
