@extends('products.layouts')

@section('content')
<div class="container mt-5">
    <h2>Daftar Produk</h2>

    <a href="{{ route('products.create') }}" class="btn btn-success mb-3">+ Tambah Produk</a>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Gambar</th>
                <th>Alamat</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->description }}</td>
                <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                <td>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="80">
                </td>
                <td>{{ $product->address }}</td>
                <td>{{ $product->category }}</td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>

                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                        style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin ingin menghapus produk ini?')" 
                            class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada produk</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
