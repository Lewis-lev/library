
@extends('layouts.admin')

@section('title', 'User List')
@section('content')

    <div class="container-fluid admin-data-page">
        <div class="data-page-heading">
            <div>
                <span class="eyebrow"><i class="fa-solid fa-users me-2"></i>Library members</span>
                <h1>Member directory</h1>
                <p>Review member details and account verification.</p>
            </div>
            <div class="data-page-count">
                <span>{{ $user->count() }}</span>
                <small>{{ \Illuminate\Support\Str::plural('member', $user->count()) }}</small>
            </div>
        </div>

        {{-- Error message for undeletable admin --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="outline: none;"></button>
            </div>
        @endif

        <div class="card data-table-card">
            <div class="data-table-toolbar">
                <label class="table-search">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <span class="visually-hidden">Search members</span>
                    <input type="search" class="form-control" placeholder="Search members..."
                        data-table-label="member"
                        data-table-filter="members-table" aria-controls="members-table">
                </label>
                <span class="table-search-count" data-table-count="members-table" aria-live="polite">
                    {{ $user->count() }} {{ \Illuminate\Support\Str::plural('member', $user->count()) }}
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table id="members-table" class="table table-striped table-hover align-middle">
                    <caption class="visually-hidden">Library member directory</caption>
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">User ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Verified</th>
                            <th scope="col">Registered</th>
                            <th scope="col" style="min-width:120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user as $u)
                        <tr>
                            <td><span class="table-id">#{{ $u->user_id }}</span></td>
                            <td>
                                <div class="member-cell">
                                    <span class="member-avatar">
                                        @if($u->profile_picture)
                                            <img
                                                src="{{ asset('https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/' . $u->profile_picture) }}"
                                                alt=""
                                                loading="lazy"
                                                onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                                        @endif
                                        <span @if($u->profile_picture) hidden @endif>
                                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($u->name, 0, 1)) }}
                                        </span>
                                    </span>
                                    <span class="member-name">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td><a class="table-email" href="mailto:{{ $u->email }}">{{ $u->email }}</a></td>
                            <td>{{ $u->phone_number ?? '-' }}</td>
                            <td>
                                @if($u->email_verified_at)
                                    <span class="badge member-status-verified"><i class="fa fa-check-circle me-1"></i>Verified</span>
                                @else
                                    <span class="badge member-status-pending"><i class="fa fa-clock me-1"></i>Pending</span>
                                @endif
                            </td>
                            <td>
                                {{ $u->created_at ? $u->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td>
                                <a href="{{ route('user.view', ['user_id' => $u->user_id]) }}" class="btn btn-outline-primary btn-sm" title="View Details" aria-label="View {{ $u->name }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('user.delete', $u->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" aria-label="Delete {{ $u->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="table-empty-state">No members found.</td>
                        </tr>
                        @endforelse
                        @if($user->isNotEmpty())
                            <tr data-table-no-match hidden>
                                <td colspan="7" class="table-empty-state">No members match your search.</td>
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
