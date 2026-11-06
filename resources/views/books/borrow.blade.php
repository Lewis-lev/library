@extends('layouts.admin')

@section('title', 'Borrow Book')
@section('content')

    <div class="container-fluid admin-data-page">
        <div class="data-page-heading">
            <div>
                <span class="eyebrow"><i class="fa-solid fa-book-open me-2"></i>Circulation</span>
                <h1>Borrow requests</h1>
                <p>Review requests, update loan status, and track returns.</p>
            </div>
        </div>

        @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="mb-3 d-flex justify-content-end">
                <form action="{{ route('borrows.deleteAll') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all borrow records? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete All
                    </button>
                </form>
            </div>
        @endif

        <div class="card data-table-card">
            <div class="data-table-toolbar">
                <label class="table-search">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <span class="visually-hidden">Search borrow requests</span>
                    <input type="search" class="form-control" placeholder="Search requests..."
                        data-table-label="request"
                        data-table-filter="borrow-requests-table" aria-controls="borrow-requests-table">
                </label>
                <span class="table-search-count" data-table-count="borrow-requests-table" aria-live="polite">
                    {{ $borrows->count() }} {{ \Illuminate\Support\Str::plural('request', $borrows->count()) }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table id="borrow-requests-table" class="table table-striped table-hover align-middle">
                    <caption class="visually-hidden">Library book borrowing requests</caption>
                    <thead class="table-dark">
                        <tr>
                            <th>Borrow Code</th>
                            <th>Book Title</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th style="min-width: 110px;">Borrow Date</th>
                            <th style="min-width: 110px;">Return Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($borrows as $borrow)
                        <tr>
                                    <td>{{ $borrow->borrow_code }}</td>
                                    <td>{{ $borrow->book ? $borrow->book->title : 'N/A' }}</td>
                                    <td>{{ $borrow->user ? $borrow->user->name : 'N/A' }}</td>
                                    <td>{{ $borrow->user ? $borrow->user->email : 'N/A' }}</td>
                                    <td>{{ $borrow->user ? $borrow->user->phone_number : 'N/A' }}</td>
                                    <td>
                                        @if($borrow->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($borrow->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($borrow->status === 'returned')
                                            <span class="badge bg-info text-dark">Returned</span>
                                        @elseif($borrow->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $borrow->created_at ? $borrow->created_at->format('d M Y H:i') : '-' }}</td>
                                    <td class="text-center">{{ $borrow->borrow_date ? \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y H:i') : '-' }}</td>
                                    <td class="text-center">{{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') : '-' }}</td>
                                    <td>
                                    @if($borrow->status === 'pending')
                                        <form action="{{ route('borrows.approve', $borrow->borrow_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm" title="Approve"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('borrows.reject', $borrow->borrow_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Reject"><i class="fas fa-times"></i></button>
                                        </form>
                                    @elseif($borrow->status === 'approved')
                                        <form action="{{ route('borrows.return', $borrow->borrow_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-primary btn-sm" title="Mark as Returned"><i class="fas fa-undo"></i></button>
                                        </form>
                                    @endif
                                    @if(in_array($borrow->status, ['rejected','returned']))
                                        <form action="{{ route('borrows.destroy', $borrow->borrow_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this borrow record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                    </td>
                                </tr>
                        @endforeach
                        @if($borrows->isEmpty())
                            <tr>
                                <td colspan="10" class="table-empty-state">No borrow requests yet.</td>
                            </tr>
                        @else
                            <tr data-table-no-match hidden>
                                <td colspan="10" class="table-empty-state">No requests match your search.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/table-filter.js') }}" defer></script>
@endsection
