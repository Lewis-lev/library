@extends('layouts.guest')

@section('content')

<div class="container">
    <h2 class="mb-4">Books</h2>

    <!-- Search & Sort -->
    <div class="d-flex justify-content-between mb-3">
        <form action="{{ route('books.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search books..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>

        </form>

        <form action="{{ route('books.index') }}" method="GET">
            <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="">Sort by</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A-Z</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z-A</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            </select>
        </form>
    </div>

    @if(auth()->user()->role === 'admin')
    <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">
        <i class="fa-solid fa-plus"></i> Add New Book
    </a>
    @endif

    <!-- Trending Books Section -->
    <h3 class="mt-4">Books</h3>
    <div class="d-flex align-items-center">
        <button class="btn btn-primary me-2" onclick="booksScroll(-1)">&#9665;</button>
        <div id="trendingContainer" class="d-flex overflow-hidden" style="gap: 16px; width: 100%; white-space: nowrap;">
            @foreach ($books as $book)
            <div class="card text-center" style="width: 200px; min-width: 200px; flex-shrink: 0;">
                @if ($book->image)
                <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top" style="height: 270px; object-fit: cover;">
                @else
                <img src="{{ asset('default-book.jpg') }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h6 class="card-title">{{ $book->title }}</h6>
                    <p class="text-muted">Available</p>
                    <a href="#" class="btn btn-primary btn-sm">View Details</a>
                </div>
            </div>
            @endforeach
        </div>
        <button class="btn btn-primary ms-2" onclick="booksScroll(1)">&#9655;</button>
    </div>

<!-- JavaScript for Scroll Buttons -->
<script>
    function booksScroll(direction) {
        const container = document.getElementById('trendingContainer');
        const scrollAmount = 1000;
        container.scrollBy({
            left: direction * scrollAmount,
            behavior: 'smooth'
        });
    }
</script>

@endsection
