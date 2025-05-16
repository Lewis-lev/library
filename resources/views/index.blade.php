@extends('layouts.guest')

@section('title', 'Welcome to the Library')

@section('content')

<link rel="stylesheet" href="{{ asset('css/index.css') }}">

<div class="container centered-hero-container">
    <div class="row justify-content-center w-100">
        <div class="col-lg-8" >

            <div class="align-middle cool-hero text-center">
                <h1>
                    Welcome to <span style="color: #2563eb;">LibraryApp</span>
                </h1>
                <p class="lead">
                    Dive into a world of knowledge and entertainment.<br>
                    <b>Borrow your favorite books, anytime!</b>
                </p>
                <div class="mb-4">
                    <a href="{{ route('login') }}" class="btn get-started cool-action-btn mr-4 me-2 px-5 py-2 fw-semibold shadow">
                        <i class="fa fa-sign-in-alt me-2"></i>Get Started
                    </a>
                    <a href="{{ route('register') }}" class="btn demo-btn cool-action-btn px-4 py-2">
                        <i class="fa fa-user-plus me-2"></i>Register
                    </a>
                </div>
                <div class="poweredby mt-5">
                    <span>Made by <b>Madani Shofa</b> using Laravel&nbsp;&middot;&nbsp;LibraryApp &copy; {{ date('Y') }}</span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
