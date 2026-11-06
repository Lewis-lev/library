@extends('layouts.guest')

@section('title', 'Welcome to the Library')

@section('content')

<link rel="stylesheet" href="{{ asset('css/index.css') }}">

<div class="home-hero">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-7">
                <span class="eyebrow"><i class="fa-solid fa-leaf me-2"></i>Your next chapter starts here</span>
                <h1>Good books.<br><span>Ready when you are.</span></h1>
                <p class="home-intro">
                    Find your next favorite in our online library. Browse the shelves, borrow with a few clicks,
                    and keep every great read close.
                </p>
                <div class="home-actions">
                    <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-magnifying-glass me-2"></i>Explore the collection
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">Create an account</a>
                    @endguest
                </div>
                <div class="home-trust">
                    <span><i class="fa-solid fa-book-open me-2"></i>A library that travels with you</span>
                    <span><i class="fa-solid fa-clock me-2"></i>Borrow on your schedule</span>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="home-book-art" aria-hidden="true">
                    <div class="book-art-note"><i class="fa-solid fa-quote-left"></i></div>
                    <div class="book-art-stack">
                        <div class="book-art book-art-one"><i class="fa-solid fa-book-open"></i><span>Find a story<br>worth keeping</span></div>
                        <div class="book-art book-art-two"></div>
                        <div class="book-art book-art-three"></div>
                    </div>
                    <div class="book-art-caption">Open a book. Go somewhere new.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="home-features container">
    <div class="section-heading">
        <span class="eyebrow">A better way to read</span>
        <h2>Your library, made simple.</h2>
        <p>From discovering a title to tracking your return date, everything you need is in one place.</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <article class="feature-card">
                <span class="feature-icon"><i class="fa-solid fa-compass"></i></span>
                <h3>Find your next read</h3>
                <p>Explore a growing collection and discover books for every kind of curious mind.</p>
            </article>
        </div>
        <div class="col-md-4">
            <article class="feature-card">
                <span class="feature-icon"><i class="fa-solid fa-book-bookmark"></i></span>
                <h3>Borrow with ease</h3>
                <p>Request a book online and keep your borrowing history right at your fingertips.</p>
            </article>
        </div>
        <div class="col-md-4">
            <article class="feature-card">
                <span class="feature-icon"><i class="fa-solid fa-seedling"></i></span>
                <h3>Make time to read</h3>
                <p>Choose a return date that works for you and settle into your next great story.</p>
            </article>
        </div>
    </div>
</section>
@endsection
