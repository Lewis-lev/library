@extends('layouts.admin')

@section('title', 'Add New Book')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Add New Book</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Book Title</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   id="title" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                   id="author" value="{{ old('author') }}" required>
            @error('author')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="publisher" class="form-label">Publisher</label>
            <input type="text" name="publisher" class="form-control @error('publisher') is-invalid @enderror"
                   id="publisher" value="{{ old('publisher') }}" required>
            @error('publisher')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Stock</label>
            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                   id="quantity" value="{{ old('quantity') }}" required>
            @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Book Cover</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror"
                   id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">
                Allowed formats: JPG, PNG, GIF, WebP. Maximum size: 2MB
            </small>
        </div>

        <button type="submit" class="btn btn-success">Add Book</button>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
