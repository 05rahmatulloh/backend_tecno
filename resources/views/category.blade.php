@extends('products.layouts')

@section('content')



<h2>Create Category</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('categories.store') }}">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Category Name</label>
        <input type="text" name="name" class="form-control" required>
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>


@endsection
