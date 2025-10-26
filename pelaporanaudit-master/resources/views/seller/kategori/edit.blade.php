<div class="container">
    <h1>Edit Kategori</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kategori.update', $kategoris->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="namakategori">Nama Kategori</label>
            <input type="text" name="namakategori" id="namakategori" class="form-control" value="{{ $kategoris->namakategori }}" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
