@extends('layouts.guest')

@section('title', 'Page not found | The Reading Room')

@section('content')
<section class="error-page container">
    <div class="error-card">
        <span class="error-icon" aria-hidden="true"><i class="fa-solid fa-book-open"></i></span>
        <span class="eyebrow">Looks like this page is checked out</span>
        <p class="error-code">404</p>
        <h1>We couldn't find that page.</h1>
        <p class="error-description">
            The link may be out of date, or the page may have moved. Let’s find another good read.
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
