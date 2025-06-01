@extends('products.layouts')

@section('content')
<div class="container">
    <h2>Edit Produk</h2>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="name">Nama Produk</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}"
                required>
        </div>

        <div class="form-group mb-3">
            <label for="description">Deskripsi Produk</label>
            <textarea name="description" id="description" class="form-control"
                rows="4">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="price">Harga Produk</label>
            <input type="number" name="price" id="price" class="form-control"
                value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="is_available">Stok Tersedia</label>
            <select name="is_available" id="is_available" class="form-control">
                <option value="1" {{ $product->is_available ? 'selected' : '' }}>Tersedia</option>
                <option value="0" {{ !$product->is_available ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="category">Kategori Produk</label>
            <input type="text" name="category" id="category" class="form-control"
                value="{{ old('category', $product->category) }}">
        </div>

        <div class="form-group mb-3">
            <label for="image">Gambar Produk</label>
            <input type="file" name="image" id="image" class="form-control">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="100" class="mt-2">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Produk</button>
    </form>
</div>
@endsection