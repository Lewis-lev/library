<style>
    /* DataTable Customizations */
    table.dataTable tbody tr.selected {
            background-color: #bbeafe !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #e3f2fd !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.1em 0.75em;
            border-radius: 0.25em;
            margin: 0 0.1em;
        }

        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate {
            margin-bottom: 1em;
        }

        .dataTables_filter input[type="search"] {
            border-radius: 2em;
            outline: none;
            border: 1px solid #87d2f7;
            padding: 0.3em 1em;
            margin-left: 8px;
        }

        @media (max-width: 575.98px) {
            #myTable {
                font-size: 0.8rem;
            }

            #myTable th,
            #myTable td {
                padding: 0.3rem !important;
            }
        }
</style>


@extends('layouts.admin')

@section('title', 'Borrow Book')
@section('content')

    <div class="container-fluid mt-5">
        <h2 class="mb-4 text-center fw-bold"><i class="fa-solid fa-book"></i> Log Borrow</h2>

        <div class="card shadow">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Borrow Code</th>
                                <th>Books Title</th>
                                <th>Borrower Username</th>
                                <th>Borrower Email</th>
                                <th style="width:130px;">Borrower Phone Number</th>
                                <th>Borrower Address</th>
                                <th>Status</th>
                                <th>
                                    @if(request()->has('status'))
                                        {{ ucfirst(request('status')) }} Date
                                    @else
                                        Date
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($borrows as $borrow)
                                <tr>
                                    <td class="align-middle">{{ $borrow->borrow_code }}</td>
                                    <td class="align-middle">{{ $borrow->book->title ?? 'N/A' }}</td>
                                    <td class="align-middle">{{ $borrow->user->name ?? 'N/A' }}</td>
                                    <td class="align-middle">{{ $borrow->user->email ?? 'N/A' }}</td>
                                    <td class="align-middle">{{ $borrow->user->phone_number ?? 'N/A' }}</td>
                                    <td class="align-middle">{{ $borrow->user->address ?? 'N/A' }}</td>
                                    <td class="align-middle">
                                        @if(auth()->check() && auth()->user()->role === 'admin')
                                            @if($borrow->status === 'pending')
                                                <form action="{{ route('borrows.approve', $borrow->borrow_id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success btn-sm mb-1">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('borrows.reject', $borrow->borrow_id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm mb-1">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </form>
                                            @elseif($borrow->status === 'approved')
                                                <form action="{{ route('borrows.return', $borrow->borrow_id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-primary btn-sm mb-1">
                                                        <i class="fas fa-undo"></i> Return
                                                    </button>
                                                </form>
                                            @elseif($borrow->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @elseif($borrow->status === 'returned')
                                                <span class="badge bg-info text-dark">Returned</span>
                                            @endif
                                        @else
                                            <span class="badge bg-{{
                                                $borrow->status === 'pending' ? 'warning' :
                                                ($borrow->status === 'approved' ? 'success' :
                                                ($borrow->status === 'rejected' ? 'danger' :
                                                ($borrow->status === 'returned' ? 'info text-dark' : 'secondary'))) }}">
                                                {{ ucfirst($borrow->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($borrow->status === 'pending')
                                            {{ $borrow->created_at ? $borrow->created_at->format('Y-m-d H:i') : '-' }}
                                        @elseif($borrow->status === 'approved')
                                            {{ $borrow->borrow_date ? \Carbon\Carbon::parse($borrow->borrow_date)->format('Y-m-d H:i') : '-' }}
                                        @elseif($borrow->status === 'returned')
                                            {{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('Y-m-d H:i') : '-' }}
                                        @elseif($borrow->status === 'rejected')
                                            {{ $borrow->updated_at ? $borrow->updated_at->format('Y-m-d H:i') : '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
