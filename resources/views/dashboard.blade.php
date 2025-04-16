@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                Dashboard
            </div>
            <div class="card-body">
                <p class="mb-0">You're logged in!</p>
            </div>
        </div>
    </div>
</div>
@if (auth()->user()->role === 'admin')
    <p>You are logged in as an <strong>Admin</strong>.</p>
@elseif (auth()->user()->role === 'borrower')
    <p>You are logged in as a <strong>Borrower</strong>.</p>
@endif
@endsection
