
@extends('layouts.borrower')

@section('title', 'Borrow History')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center fw-bold"><i class="fa-solid fa-book"></i> Borrow History</h2>

    <div class="card shadow mb-4">
        <div class="card-body">
            <ul class="nav nav-pills mb-4 justify-content-center" id="historyTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="all-tab" data-bs-toggle="pill" href="#all" role="tab">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pending-tab" data-bs-toggle="pill" href="#pending" role="tab">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="approved-tab" data-bs-toggle="pill" href="#approved" role="tab">Borrowed/Approved</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="returned-tab" data-bs-toggle="pill" href="#returned" role="tab">Returned</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="rejected-tab" data-bs-toggle="pill" href="#rejected" role="tab">Rejected</a>
                </li>
            </ul>

            @php
                // Group the records for quick access in the tabs
                $allBorrows = Auth::user()->borrows()->with('book')->orderByDesc('created_at')->get();
                $pending = $allBorrows->where('status', 'pending');
                $approved = $allBorrows->where('status', 'approved');
                $returned = $allBorrows->where('status', 'returned');
                $rejected = $allBorrows->where('status', 'rejected');
                $tabGroups = [
                    'all' => $allBorrows,
                    'pending' => $pending,
                    'approved' => $approved,
                    'returned' => $returned,
                    'rejected' => $rejected,
                ];
            @endphp

            <div class="tab-content" id="historyTabsContent">
                @foreach($tabGroups as $tabKey => $borrows)
                <div class="tab-pane fade {{ $tabKey == 'all' ? 'show active' : '' }}" id="{{ $tabKey }}" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-dark">
                            <tr>
                                <th>Borrow Code</th>
                                <th>Book Title</th>
                                <th>Status</th>
                                <th class="text-center">Borrow Date</th>
                                <th class="text-center">Return Date</th>
                                <th class="text-center">Requested</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($borrows as $borrow)
                                <tr>
                                    <td>{{ $borrow->borrow_code }}</td>
                                    <td>{{ $borrow->book ? $borrow->book->title : 'N/A' }}</td>
                                    <td>
                                        @if($borrow->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($borrow->status === 'approved')
                                            <span class="badge bg-success">Borrowed</span>
                                        @elseif($borrow->status === 'returned')
                                            <span class="badge bg-info text-dark">Returned</span>
                                        @elseif($borrow->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $borrow->borrow_date ? \Carbon\Carbon::parse($borrow->borrow_date)->format('Y-m-d H:i') : '-' }}</td>
                                    <td class="text-center">{{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('Y-m-d H:i') : '-' }}</td>
                                    <td class="text-center">{{ $borrow->created_at ? $borrow->created_at->format('Y-m-d H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No records found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Remember tab via localStorage (optional)
    document.addEventListener('DOMContentLoaded', function() {
        var currentTab = localStorage.getItem('borrowHistoryTab');
        if (currentTab && document.getElementById(currentTab)) {
            var tab = new bootstrap.Tab(document.getElementById(currentTab));
            tab.show();
        }
        document.querySelectorAll('#historyTabs .nav-link').forEach(function(link) {
            link.addEventListener('shown.bs.tab', function (e) {
                localStorage.setItem('borrowHistoryTab', e.target.id);
            });
        });
    });
</script>
@endpush
@endsection
