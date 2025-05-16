
@extends('layouts.admin')

@section('title', 'User List')
@section('content')

    <div class="container-fluid mt-5">
        <h2 class="mb-4 text-center fw-bold">
            <i class="fa-solid fa-users"></i> User List
        </h2>

        {{-- Error message for undeletable admin --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="outline: none;"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body p-3">
                <table id="myTable" class="table table-striped table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">User ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Registered</th>
                            <th scope="col" style="min-width:120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user as $u)
                        <tr>
                            <td>{{ $u->user_id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->phone_number ?? '-' }}</td>
                            <td>
                                {{ $u->created_at ? $u->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td>
                                <a href="{{ route('user.view', ['user_id' => $u->user_id]) }}" class="btn btn-info btn-sm" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('user.delete', $u->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
