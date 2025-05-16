@extends('layouts.borrower')

@section('title', 'Dashboard')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <div class="container">

        {{-- Hero Section --}}
        <div class="dashboard-hero d-flex align-items-center mb-4 mt-4 pd-4">
            <img class="user-avatar shadow-sm"
                src="{{ auth()->user()->profile_picture ? asset('storage/profile_pict/' . auth()->user()->profile_picture) : asset('storage/profile_pict/default-profile.jpg') }}"
                alt="Profile">
            <div>
                <h2>Hi, {{ auth()->user()->name ?? 'Borrower' }}!</h2>
                <div>Welcome back to your Library Dashboard.</div>
            </div>
        </div>

        {{-- Conditional Notification --}}
        @if(isset($overdueCount) && $overdueCount > 0)
            <div class="dashboard-notification">
                <i class="fa fa-exclamation-circle fa-lg"></i>
                <div>
                    You currently have <b>{{ $overdueCount }}</b> overdue {{ Str::plural('book', $overdueCount) }}.
                    Please return them as soon as possible to avoid fines.
                    <a href="{{ route('books.history') }}" class="ms-2 text-danger text-decoration-underline">View Details</a>
                </div>
            </div>
        @endif

        {{-- Cards Row --}}
        <div class="dashboard-cards-row d-flex flex-row flex-wrap mb-4">
            <div class="dashboard-card">
                <span class="icon"><i class="fa fa-book"></i></span>
                <div class="stat-label">Currently Borrowed</div>
                <div class="stat-value">
                    {{ $currentBorrowCount }}
                </div>
            </div>
            <div class="dashboard-card">
                <span class="icon"><i class="fa fa-check-circle"></i></span>
                <div class="stat-label">Completed Returns</div>
                <div class="stat-value">
                    {{ $borrowHistoryCount }}
                </div>
            </div>
            <div class="dashboard-card overdue">
                <span class="icon"><i class="fa fa-exclamation-triangle"></i></span>
                <div class="stat-label">Overdue Books</div>
                <div class="stat-value">
                    {{ $overdueCount }}
                </div>
            </div>
        </div>

        {{-- Dashboard Actions --}}
        <div class="dashboard-actions">
            <a href="{{ route('books.index') }}" class="btn px-4 py-2 shadow">
                <i class="fa fa-search me-1"></i> Browse Books
            </a>
            <a href="{{ route('books.history') }}" class="btn px-4 py-2 shadow">
                <i class="fa fa-history me-1"></i> Borrowing History
            </a>
            <a href="{{ route('profile.edit') }}" class="btn px-4 py-2 shadow">
                <i class="fa fa-user me-1"></i> My Profile
            </a>
        </div>

        {{-- Recent Borrowed Books --}}
        <div>
            <div class="dashboard-section-title">Recently Borrowed Books</div>
            @php
                $nonRejectedBorrows = $recentBorrows->filter(function ($borrow) {
                    return $borrow->status !== 'rejected';
                });
            @endphp

            @if($nonRejectedBorrows->count() > 0)
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Borrowed At</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nonRejectedBorrows as $borrow)
                            <tr>
                                <td>{{ $borrow->book->title ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') }}</td>
                                <td>
                                    @if($borrow->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($borrow->status === 'approved')
                                        <span class="badge bg-success">Borrowed</span>
                                    @elseif($borrow->status === 'returned')
                                        <span class="badge bg-info text-dark">Returned</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-muted mb-4">
                    You have not borrowed any books recently.
                </div>
            @endif
        </div>
    </div>
@endsection
