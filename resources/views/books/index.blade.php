<style>
    /* Slide down from top when appearing */
    .toast.slide-top-in {
        animation: slideTopIn .5s cubic-bezier(0.42, 0, 0.58, 1);
    }

    @keyframes slideTopIn {
        from {
            opacity: 0;
            transform: translateY(-40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Slide up when disappearing */
    .toast.slide-top-out {
        animation: slideTopOut .5s cubic-bezier(0.42, 0, 0.58, 1) forwards;
    }

    @keyframes slideTopOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }

        to {
            opacity: 0;
            transform: translateY(-40px);
        }
    }
</style>

@extends('layouts.guest')

@section('content')
    <div class="container">
        {{-- Success alert --}}
        @if(session('success'))
            <div id="success-alert" class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <strong>{{ session('success') }}</strong>
            </div>
        @endif

        <h2 class="mb-4">Books</h2>

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
                <div class="alert alert-secondary mb-3">Welcome, <b>{{ auth()->user()->name }}</b>! Browse and borrow books below.
                </div>
            @elseif(auth()->user()->role === 'so')
                <div class="alert alert-warning mb-3">Welcome, <b>SO</b>! (Customize actions for this role.)</div>
            @endif
        @endguest

        <!-- Books Grid Section -->
        <h3 class="mt-4">Books</h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-6 g-4">
            @foreach ($books as $book)
                <div class="col d-flex">
                    <div class="card text-center flex-fill">
                        @if ($book->image)
                            <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top book-zoom-img"
                                style="height: 210px; object-fit: cover; cursor: pointer;" alt="Book Cover" data-bs-toggle="modal"
                                data-bs-target="#imageZoomModal" data-img="{{ asset('storage/' . $book->image) }}">
                        @else
                            <img src="{{ asset('default-book.jpg') }}" class="card-img-top book-zoom-img"
                                style="height: 210px; object-fit: cover; cursor: pointer;" alt="Default Book Cover"
                                data-bs-toggle="modal" data-bs-target="#imageZoomModal" data-img="{{ asset('default-book.jpg') }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $book->title }}</h6>
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <span class="badge bg-primary mb-2" style="font-size: 12px;"
                                        id="quantity-badge-{{ $book->book_id }}">
                                        Stock: {{ $book->quantity }}
                                    </span>
                                @endif
                            @endauth
                            <p class="mb-1">
                                <small>
                                    Genres:
                                    {{ $book->genres->implode('name', ', ') }}
                                </small>
                            </p>
                            <p class="text-muted mb-2">Available</p>
                            <div class="mt-auto">
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
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to borrow "<b>{{ $book->title }}</b>"?
                                                        </div>
                                                        <div class="modal-footer">
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
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure?')">Delete</button>
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

        <div class="modal fade" id="imageZoomModal" tabindex="-1" aria-labelledby="imageZoomLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body p-0 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
                        <img src="" id="zoomedImage" alt="Zoomed Cover" class="img-fluid rounded shadow"
                            style="max-height: 70vh; max-width: 100%;">
                    </div>
                </div>
            </div>
        </div>

        <div id="notification-toast-container"
            style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 1055; min-width: 250px;">
            <!-- Toasts will be appended dynamically -->
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(function () {
                    var alertElement = document.getElementById('success-alert');
                    if (alertElement) {
                        // Try Bootstrap 5 programmatically
                        if (window.bootstrap && window.bootstrap.Alert && typeof window.bootstrap.Alert.getOrCreateInstance === 'function') {
                            var bsAlert = bootstrap.Alert.getOrCreateInstance(alertElement);
                            bsAlert.close();
                        } else {
                            // fallback: just hide
                            alertElement.classList.remove('show');
                            alertElement.style.display = 'none';
                        }
                    }
                }, 3000);
            });
        </script>

        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Success alert timeout code...

                // Image zoom logic
                var imageModal = document.getElementById('imageZoomModal');
                var zoomedImg = document.getElementById('zoomedImage');

                document.querySelectorAll('.book-zoom-img').forEach(function (img) {
                    img.addEventListener('click', function () {
                        var src = this.getAttribute('data-img');
                        zoomedImg.setAttribute('src', src);
                    });
                });

                // Optionally, clear src when modal hides (for privacy/performance)
                imageModal.addEventListener('hidden.bs.modal', function () {
                    zoomedImg.setAttribute('src', '');
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if(auth()->check() && auth()->user()->role == 'admin')
                    window.Echo = new Echo({
                        broadcaster: "pusher",
                        key: "{{ config('broadcasting.connections.pusher.key') }}",
                        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                        forceTLS: true
                    });

                    window.Echo.channel("admin-channel")
                        .listen(".BookBorrowed", function (e) {
                            let container = document.getElementById('notification-toast-container');
                            if (!container) return;

                            // Unique ID for each toast
                            let toastId = 'toast-' + Date.now();

                            // Message
                            let message = `<strong class="me-auto"><i class="fa-solid fa-book-open"></i> Book Borrowed!</strong>
                                    <div><b>${e.user.name}</b> just borrowed "<b>${e.book.title}</b>"</div>`;

                            // Build the toast element
                            let toastElem = document.createElement('div');
                            toastElem.className = 'toast show align-items-center text-bg-primary border-0 mb-2 shadow slide-top-in';
                            toastElem.id = toastId;
                            toastElem.setAttribute('role', 'alert');
                            toastElem.setAttribute('aria-live', 'assertive');
                            toastElem.setAttribute('aria-atomic', 'true');
                            toastElem.style.minWidth = '270px';
                            toastElem.innerHTML = `
                                      <div class="d-flex">
                                        <div class="toast-body">
                                          ${message}
                                        </div>
                                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                      </div>`;

                            container.appendChild(toastElem);

                            // Animation and remove handling
                            toastElem.addEventListener('animationend', function animIn(e) {
                                if (e.animationName === 'slideTopIn') {
                                    toastElem.classList.remove('slide-top-in');
                                    setTimeout(() => toastElem.classList.add('slide-top-out'), 3000);
                                    toastElem.removeEventListener('animationend', animIn);
                                }
                            });
                            toastElem.addEventListener('animationend', function animOut(e) {
                                if (e.animationName === 'slideTopOut') {
                                    toastElem.remove();
                                    toastElem.removeEventListener('animationend', animOut);
                                }
                            });
                            toastElem.querySelector('.btn-close').onclick = function () {
                                toastElem.classList.add('slide-top-out');
                                toastElem.classList.remove('show');
                                setTimeout(() => toastElem.remove(), 350);
                            };

                            // ----- Badge: Real Time Quantity Update -----
                            let badgeId = 'quantity-badge-' + (e.book.book_id ?? e.book.id);
                            let badge = document.getElementById(badgeId);
                            if (badge) {
                                badge.textContent = 'Stock: ' + (e.book.quantity ?? 'N/A');
                                badge.classList.add('bg-success');
                                setTimeout(() => badge.classList.remove('bg-success'), 300);
                            }
                        });
                @endif
            });
        </script>

@endsection
