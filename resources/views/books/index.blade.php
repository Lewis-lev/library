@extends('layouts.admin')

@section('content')
    <div class="container">
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

        <h2 class="mb-4 mt-2 text-center fw-bold"><i class="fa-solid fa-book"></i> Books</h2>

        <!-- Search & Sort -->
        <div class="d-flex justify-content-between mb-3">
            <form action="{{ route('books.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search books..."
                    value="{{ request('search') }}">
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

        {{-- Actions for different user roles --}}
        @guest
            <div class="alert alert-info mb-3">You are viewing as a <b>Guest</b>. Please login to borrow books.</div>
        @else
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">
                    <i class="fa-solid fa-plus"></i> Add New Book
                </a>
            @elseif(auth()->user()->role === 'borrower')
                <div class="alert alert-secondary mb-3">Welcome, <b>{{ auth()->user()->name }}</b>! Browse and borrow books below.</div>
            @endif
        @endguest

        <!-- Books Grid Section -->
        <h3 class="mt-4">Books list</h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-4">
            @foreach ($books as $book)
                <div class="col d-flex">
                    <div class="card text-center flex-fill">
                            @if ($book->image)
                            <a href="{{ route('books.show', $book->book_id) }}" style="color: inherit; text-decoration: none;">
                                <img src="{{ asset( path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/book_images/' . $book->image) }}" class="card-img-top book-zoom-img" style="height: 210px; object-fit: cover; cursor: pointer;" alt="Book Cover">
                            </a>
                             @else
                             <a href="{{ route('books.show', $book->book_id) }}" style="color: inherit; text-decoration: none;">
                                <img src="{{ asset('default-book.jpg') }}" class="card-img-top book-zoom-img"
                                style="height: 210px; object-fit: cover; cursor: pointer;" alt="Default Book Cover"
                                data-bs-toggle="modal" data-bs-target="#imageZoomModal" data-img="{{ asset('default-book.jpg') }}">
                             </a>
                        @endif
                        <div class="card-body d-flex flex-column">
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <h6 class="card-title text-danger">
                                        {{ $book->code }}
                                    </h6>
                                @endif
                            @endauth
                            <h6 class="card-title">{{ $book->title }}</h6>
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <span class="badge bg-primary mb-2" style="font-size: 12px;"
                                        id="quantity-badge-{{ $book->book_id }}">
                                        Stock: {{ $book->quantity }}
                                    </span>
                                @endif
                            @endauth

                            <div class="mt-auto">
                                <p class="text-muted mb-2">Available</p>
                                @auth
                                    @if(auth()->user()->role === 'borrower')
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#borrowConfirmModal-{{ $book->book_id }}">Borrow</button>
                                        <div class="modal fade" id="borrowConfirmModal-{{ $book->book_id }}" tabindex="-1"
                                            aria-labelledby="borrowConfirmLabel{{ $book->book_id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('books.borrow', $book->book_id) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="borrowConfirmLabel{{ $book->book_id }}">Borrow
                                                                    Book</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to borrow "<b>{{ $book->title }}</b>"?
                                                            </div>
                                                            <div class="modal-footer">
                                                            <label for="borrow_duration">How long do you want to borrow?</label>
                                                            <select name="borrow_duration" id="borrow_duration" class="form-select" required>
                                                                <option value="3">3 days</option>
                                                                <option value="7" selected>7 days</option>
                                                                <option value="14">14 days</option>
                                                                <option value="30">30 days</option>
                                                            </select>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success">Yes, Borrow</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif(auth()->user()->role === 'so')
                                        <a href="#" class="btn btn-info btn-sm">SO Action</a>
                                    @elseif(auth()->user()->role === 'admin')
                                        <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login to Borrow</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <script src="{{ asset('js/alert.js') }}"></script>
@endsection
