@extends('layouts.guest')

@section('title', 'Access denied | The Reading Room')

@section('content')
<section class="error-page container">
    <div class="error-card">
        <span class="error-icon" aria-hidden="true"><i class="fa-solid fa-lock"></i></span>
        <span class="eyebrow">A staff-only shelf</span>
        <p class="error-code">403</p>
        <h1>You don't have access to this page.</h1>
        <p class="error-description">
            Member records and library management tools are only available to administrators.
            You can keep browsing the book collection with your current account.
        </p>
        <div class="error-actions">
            <a href="{{ route('books.index') }}" class="btn btn-primary">
                <i class="fa-solid fa-book-open me-2"></i>Browse books
            </a>
            <a href="{{ url('/') }}" class="btn btn-outline-primary">Back to home</a>
        </div>
    </div>
</section>
@endsection
