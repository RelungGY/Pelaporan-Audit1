<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Input Member</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h1>Form Input Member</h1>

<!-- Form untuk menyimpan data member -->
<form action="{{ route('store.member') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- User ID -->
    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

    <!-- Nama Toko -->
    <div class="form-group">
        <label for="nama_toko">Nama Toko:</label>
        <input type="text" id="nama_toko" name="nama_toko" required>
    </div>

    <!-- Alamat -->
    <div class="form-group">
        <label for="alamat">Alamat:</label>
        <textarea id="alamat" name="alamat" rows="3" required></textarea>
    </div>

    <!-- Nomor Telepon -->
    <div class="form-group">
        <label for="no_telp">Nomor Telepon:</label>
        <input type="text" id="no_telp" name="no_telp" required>
    </div>

    <!-- Upload Gambar -->
    <div class="form-group">
        <label for="image">Upload Gambar:</label>
        <input type="file" id="image" name="image" accept="image/*">
    </div>

    <!-- Submit Button -->
    <div class="form-group">
        <button type="submit">Simpan Data</button>
    </div>
</form>

</body>
</html>
