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
                            <th class="text-center">Borrow Code</th>
                            <th class="text-center">Books Title</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">
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
                                <td class="align-middle">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        @if(auth()->check() && auth()->user()->role === 'admin')
                                            @if($borrow->status === 'pending')
                                                <form action="{{ route('borrows.approve', $borrow->borrow_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success btn-sm mt-3">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('borrows.reject', $borrow->borrow_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm mt-3">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                </form>
                                            @elseif($borrow->status === 'approved')
                                                <form action="{{ route('borrows.return', $borrow->borrow_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-primary btn-sm mt-3">
                                                        <i class="fas fa-undo"></i> Return
                                                    </button>
                                                </form>
                                            @elseif($borrow->status === 'rejected')
                                                <span class="badge bg-danger d-flex align-items-center px-3 py-2">Rejected</span>
                                                <form action="{{ route('borrows.destroy', $borrow->borrow_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this borrow record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm mt-3">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @elseif($borrow->status === 'returned')
                                                <span class="badge bg-info d-flex align-items-center px-3 py-2 text-dark">Returned</span>
                                                <form action="{{ route('borrows.destroy', $borrow->borrow_id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this borrow record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm mt-3">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class=" bg-{{
                                                $borrow->status === 'pending' ? 'warning' :
                                                ($borrow->status === 'approved' ? 'success' :
                                                ($borrow->status === 'rejected' ? 'danger' :
                                                ($borrow->status === 'returned' ? 'info text-dark' : 'secondary'))) }}">
                                                {{ ucfirst($borrow->status) }}
                                            </span>
                                        @endif
                                    </div>
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
@endsection
