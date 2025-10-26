<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Product</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('produkseller.update', $item->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Name Field -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $item->name }}" placeholder="Enter name" required>
                </div>

                <!-- Description Field -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" placeholder="Enter description" rows="3" required>{{ $item->description }}</textarea>
                </div>

                <!-- Price Per Day Field -->
                <div class="form-group">
                    <label for="price_per_day">Price Per Day</label>
                    <input type="number" class="form-control" id="price_per_day" name="price_per_day" value="{{ $item->price_per_day }}" placeholder="Enter price per day" required>
                </div>

                <div class="form-group">
                    <label for="images">Images</label>
                    <input type="file" class="form-control" id="images" name="images[]" multiple >
                </div>

                <!-- Display Existing Images -->
                <div class="form-group">
                    <label>Existing Images</label>
                    <div class="row">
                        @foreach (json_decode($item->images ?? '[]') as $image)
                            <div class="col-md-3">
                                <img src="{{ Storage::url($image) }}" class="img-fluid mb-2" alt="Product Image">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Available Field -->
                <div class="form-group">
                    <label for="available">Available</label>
                    <select class="form-control" id="available" name="available" required>
                        <option value="">Select availability</option>
                        <option value="yes" {{ $item->available == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="no" {{ $item->available == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select class="form-control" id="kategori_id" name="kategori_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ $item->kategori_id == $kategori->id ? 'selected' : '' }}>{{ $kategori->namakategori }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Stok Field -->
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="{{ $item->stok }}" placeholder="Enter stok" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>