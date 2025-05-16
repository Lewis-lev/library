@extends('layouts.borrower')

@section('title', 'Dashboard')

@section('content')
    <style>
        .dashboard-hero {
            background: linear-gradient(120deg, #63e7fa 0%, #33cfff 50%, #006aff 100%);
            color: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 18px #00cfff22;
            padding: 2rem 2.5rem 2rem 2.5rem;
            margin-bottom: 2rem;
            text-shadow: 0 1px 4px #0099c955;
        }

        .dashboard-hero h2 {
            font-weight: 700;
            font-size: 2.1rem;
            margin-bottom: 0.6rem;
        }

        .dashboard-hero .user-avatar {
            width: 58px;
            height: 58px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 1.2rem;
            border: 2.5px solid #fff5;
            background: #fff;
            box-shadow: 0 1px 6px #00cfff44;
        }

        .dashboard-cards-row {
            gap: 1.3rem;
        }

        .dashboard-card {
            flex: 1 1 180px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 14px #003dff14;
            padding: 1.2rem 1.1rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            min-width: 170px;
            transition: box-shadow .18s, border .2s;
            border-left: 6px solid #33cfff;
        }

        .dashboard-card.overdue {
            border-left-color: #fa485e;
            background: linear-gradient(90deg, #ffe0e3 50%, #fff 100%);
        }

        .dashboard-card .icon {
            font-size: 1.65rem;
            margin-bottom: 0.2rem;
            color: #33cfff;
        }

        .dashboard-card.overdue .icon {
            color: #fa485e;
        }

        .dashboard-card .stat-label {
            font-weight: 600;
            color: #222;
        }

        .dashboard-card .stat-value {
            font-size: 1.9rem;
            font-weight: bold;
            color: #222;
            margin-bottom: 0.1em;
        }

        .dashboard-actions {
            margin-top: 2.2rem;
        }

        .dashboard-actions a.btn {
            margin-right: 1rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(92deg, #16c1fe, #638fff) !important;
            border: none;
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 2px 10px #70e6ffd7;
            transition: transform .19s;
        }

        .dashboard-actions a.btn:hover {
            transform: scale(1.04) translateY(-2px);
            box-shadow: 0 8px 18px #63bfff40;
        }

        /* New Styles for tables, notifications, responsive */
        .dashboard-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 2rem 0 1rem;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px #008fff12;
            overflow: hidden;
        }

        .dashboard-table th,
        .dashboard-table td {
            padding: 0.75rem 1.1rem;
            border-bottom: 1px solid #eee;
        }

        .dashboard-table th {
            background: #f5fafc;
            font-weight: bold;
            color: #009be0;
        }

        .dashboard-table tr:last-child td {
            border-bottom: none;
        }

        .dashboard-notification {
            background: linear-gradient(90deg, #ff8c91 0%, #fff6c2 100%);
            color: #722;
            border-radius: 10px;
            padding: 1rem 2rem;
            margin: 1.3rem 0 2rem;
            box-shadow: 0 2px 10px #ffcb8c13;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        @media (max-width: 600px) {
            .dashboard-hero {
                padding: 1.2rem 0.7rem;
                font-size: 1rem;
            }

            .dashboard-cards-row {
                flex-direction: column !important;
                gap: 0.7rem;
            }

            .dashboard-card {
                width: 100%;
            }

            .dashboard-table th,
            .dashboard-table td {
                padding: 0.45rem 0.7rem;
                font-size: 0.97em;
            }
        }
    </style>

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
