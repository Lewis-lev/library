@extends('layouts.guest')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <div class="container-xxl" style="min-height:90vh">
        @if(session('error'))
            <div id="success-alert" class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <strong>{!! session('error') !!}</strong>
            </div>
        @endif
        <a href="{{ route('books.index') }}" class="btn btn-primary mt-4 mb-2" style="font-weight:500;">
            <i class="fa fa-arrow-left"></i> Browse more books!
        </a>
        <div class="ol-book-hero">
            <div class="ol-book-cover">
                @if ($book->image)
                    <img src="{{ asset('https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/book_images/' . $book->image) }}"
                        alt="{{ $book->title }}" style="width: 100%; height: auto; object-fit: cover;">
                @else
                    <img src="{{ asset('default-book.jpg') }}" alt="No cover" style="width: 100%; height: auto;">
                @endif
            </div>
            <div class="ol-book-meta">
                <div class="ol-title">{{ $book->title }}</div>
                <div class="ol-author">
                    @if(!empty($book->author))
                        by {{ $book->author }}
                    @endif
                </div>
                <table class="ol-meta-table table mb-2">
                    <tbody>
                        <tr>
                            <th>Publisher</th>
                            <td>{{ $book->publisher ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Stock</th>
                            <td>{{ $book->quantity ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Code</th>
                            <td>{{ $book->code ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Genres</th>
                            <td>
                                @if ($book->genres && $book->genres->count())
                                    @foreach($book->genres as $genre)
                                        <span class="ol-tag">{{ $genre->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="ol-desc-block">
                    <strong>Description:</strong><br>
                    {!! nl2br(e($book->description ?? 'No description provided.')) !!}
                </div>

                {{-- Borrow Button Section --}}
                <div class="borrow-btn-section">
                    @auth
                        @if(auth()->user()->role === 'borrower')
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#borrowConfirmModal-{{ $book->book_id }}">Borrow</button>
                            <!-- Borrow Modal -->
                            <div class="modal fade" id="borrowConfirmModal-{{ $book->book_id }}" tabindex="-1"
                                aria-labelledby="borrowConfirmLabel{{ $book->book_id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('books.borrow', $book->book_id) }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="borrowConfirmLabel{{ $book->book_id }}">Borrow Book</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
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
    <script src="{{ asset('js/alert.js') }}"></script>
@endsection
