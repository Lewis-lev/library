@extends('layouts.admin')

@section('title', 'Edit Book')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Edit Book</h2>

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

    <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Book Title</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                id="title" value="{{ old('title', $book->title) }}" required>
            @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                id="author" value="{{ old('author', $book->author) }}" required>
            @error('author')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="genres" class="form-label">Genres</label>
            <select name="genres[]" id="genres" class="form-select" multiple required>
                @foreach($allGenres as $genre)
                <option value="{{ $genre->id }}"
                    {{ isset($book) && $book->genres->contains($genre->id) ? 'selected' : '' }}>
                    {{ $genre->name }}
                </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold Ctrl (Cmd on Mac) to choose multiple genres.</small>
        </div>

        <div class="mb-3">
            <label for="publisher" class="form-label">Publisher</label>
            <input type="text" name="publisher" class="form-control @error('publisher') is-invalid @enderror"
                id="publisher" value="{{ old('publisher', $book->publisher) }}" required>
            @error('publisher')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Stock</label>
            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                id="quantity" value="{{ old('quantity', $book->quantity) }}" required>
            @error('quantity')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Book Cover</label>
            @if($book->image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $book->image) }}" alt="Current Cover" style="max-height:120px;">
            </div>
            @endif
            <input type="file" class="form-control @error('image') is-invalid @enderror"
                id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
            @error('image')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">
                Allowed formats: JPG, PNG, GIF, WebP. Maximum size: 2MB.
                Leave empty to keep the current cover.
            </small>
        </div>

        <button type="submit" class="btn btn-success">Update Book</button>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
