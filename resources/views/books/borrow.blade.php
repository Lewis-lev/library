@extends('layouts.admin')

@section('title', 'Borrow Book')
@section('content')

    <div class="container-fluid mt-5">
        <h2 class="mb-4 text-center fw-bold"><i class="fa-solid fa-book"></i> Log Borrow</h2>

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

        <div class="card shadow">
            <div class="card-body p-3">
                <table id="myTable" class="table table-striped table-bordered table-hover">
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
                                    <td class="text-center"><b>{{ $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('d M Y') : '-' }}</td>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<br><br><br>
<br><br><br>
<br>
@endsection
