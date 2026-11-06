@extends('layouts.admin')

@section('title', 'Browse Books')

@section('content')
    <div class="container book-catalog">
        {{-- Alert --}}
        @if(session('success'))
            <div id="success-alert" class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <strong>{{ session('success') }}</strong>
            </div>
        @endif
        @if(session('error'))
            <div id="success-alert" class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <strong>{!! session('error') !!}</strong>
            </div>
        @endif

        <div class="catalog-heading">
            <div>
                <span class="eyebrow"><i class="fa-solid fa-book-open me-2"></i>The collection</span>
                <h1>Find your next read</h1>
                <p>Browse the shelves and borrow a book to enjoy at home.</p>
            </div>
            <div class="catalog-count">
                <span>{{ $books->count() }}</span>
                <small>{{ \Illuminate\Support\Str::plural('book', $books->count()) }} in the collection</small>
            </div>
        </div>

        <!-- Search & Sort -->
        <div class="catalog-toolbar">
            <form action="{{ route('books.index') }}" method="GET" class="catalog-search">
                <label class="visually-hidden" for="book-search">Search books</label>
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input id="book-search" type="search" name="search" class="form-control"
                    placeholder="Search by book title..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <form action="{{ route('books.index') }}" method="GET" class="catalog-sort">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <label for="book-sort">Sort by</label>
                <select id="book-sort" name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="">Sort by</option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A-Z</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z-A</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </form>
        </div>

        {{-- Actions for different user roles --}}
        @guest
            <div class="catalog-notice">
                <i class="fa-solid fa-circle-info"></i>
                <span>Browsing as a guest? <a href="{{ route('login') }}">Sign in</a> to request a book.</span>
            </div>
        @else
            @if(auth()->user()->role === 'admin')
                <div class="catalog-admin-action">
                    <a href="{{ route('books.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-1"></i> Add a book
                    </a>
                </div>
            @elseif(auth()->user()->role === 'borrower')
                <div class="catalog-notice">
                    <i class="fa-solid fa-bookmark"></i>
                    <span>Welcome back, <strong>{{ auth()->user()->name }}</strong>. Your next read is waiting.</span>
                </div>
            @endif
        @endguest

        <!-- Books Grid Section -->
        <div class="catalog-section-heading">
            <h2>{{ request('search') ? 'Search results' : 'On the shelves' }}</h2>
            @if(request('search'))
                <a href="{{ route('books.index') }}" class="catalog-clear-search">Clear search</a>
            @endif
        </div>
        @if($books->isNotEmpty())
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4">
            @foreach ($books as $book)
                <div class="col d-flex">
                    <article class="card book-card {{ $book->quantity < 1 ? 'book-card-unavailable' : '' }} flex-fill">
                        <a class="book-cover-link" href="{{ route('books.show', $book->book_id) }}"
                            aria-label="View {{ $book->title }}">
                            @if ($book->image)
                                <img src="{{ asset('https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/' . ltrim($book->image, '/')) }}"
                                    class="book-cover" alt="Cover of {{ $book->title }}" loading="lazy"
                                    onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                                <span class="book-cover-placeholder" aria-hidden="true" hidden>
                                    <i class="fa-solid fa-book-open"></i>
                                    <span>The Reading Room</span>
                                </span>
                            @else
                                <span class="book-cover-placeholder" aria-hidden="true">
                                    <i class="fa-solid fa-book-open"></i>
                                    <span>The Reading Room</span>
                                </span>
                            @endif
                            <span class="book-cover-open">View details <i class="fa-solid fa-arrow-right"></i></span>
                        </a>
                        <div class="card-body d-flex flex-column">
                            <div class="book-card-meta">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <span class="book-code">{{ $book->code }}</span>
                                @endif
                            @endauth
                            <span class="book-availability">
                                <i class="fa-solid fa-circle"></i>
                                {{ $book->quantity > 0 ? 'Available to borrow' : 'Currently unavailable' }}
                            </span>
                            </div>
                            <h3 class="book-card-title">
                                <a href="{{ route('books.show', $book->book_id) }}">{{ $book->title }}</a>
                            </h3>
                            <p class="book-card-author">{{ $book->author ?: 'Author not listed' }}</p>
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <span class="book-stock" id="quantity-badge-{{ $book->book_id }}">
                                        <i class="fa-solid fa-boxes-stacked me-1"></i>{{ $book->quantity }} in stock
                                    </span>
                                @endif
                            @endauth

                            <div class="book-card-actions mt-auto">
                                @auth
                                    @if(auth()->user()->role === 'borrower')
                                        @if($book->quantity > 0)
                                            <button class="btn btn-primary w-100" data-bs-toggle="modal"
                                                data-bs-target="#borrowConfirmModal-{{ $book->book_id }}">Borrow</button>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>Out of stock</button>
                                        @endif
                                        @if($book->quantity > 0)
                                        <div class="modal fade" id="borrowConfirmModal-{{ $book->book_id }}" tabindex="-1"
                                            aria-labelledby="borrowConfirmLabel{{ $book->book_id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('books.borrow', $book->book_id) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="borrowConfirmLabel{{ $book->book_id }}">Borrow
                                                                    Book</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to borrow "<b>{{ $book->title }}</b>"?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <label for="borrow_duration-{{ $book->book_id }}">How long do you want to borrow?</label>
                                                            <select name="borrow_duration" id="borrow_duration-{{ $book->book_id }}" class="form-select" required>
                                                                <option value="3">3 days</option>
                                                                <option value="7" selected>7 days</option>
                                                                <option value="14">14 days</option>
                                                                <option value="30">30 days</option>
                                                            </select>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Yes, Borrow</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    @elseif(auth()->user()->role === 'so')
                                        <a href="#" class="btn btn-info btn-sm">SO Action</a>
                                    @elseif(auth()->user()->role === 'admin')
                                        <a href="{{ route('books.edit', $book) }}" class="btn btn-outline-primary flex-fill">
                                            <i class="fa-solid fa-pen me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger" aria-label="Delete {{ $book->title }}"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Sign in to borrow</a>
                                @endauth
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
        @else
            <div class="catalog-empty-state">
                <span class="feature-icon"><i class="fa-solid fa-book-open"></i></span>
                <h3>No books found</h3>
                <p>{{ request('search') ? 'Try another title or clear your search to see the full collection.' : 'The collection is being prepared. Please check back soon.' }}</p>
                @if(request('search'))
                    <a href="{{ route('books.index') }}" class="btn btn-primary">Browse all books</a>
                @endif
            </div>
        @endif

        <script src="{{ asset('js/alert.js') }}"></script>
@endsection
