@extends('layouts.guest')

@section('title', 'Welcome to the Library')

@section('content')

<style>
    body {
        background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%) !important;
    }
    .cool-hero {
        background: rgba(255,255,255,0.90);
        border-radius: 16px;
        box-shadow: 0 10px 28px rgba(52, 144, 220, 0.11), 0 2px 4px rgba(82, 124, 253, 0.11);
        overflow: hidden;
        padding: 3rem 2.5rem 3rem 2.5rem;
        position: relative;
        margin-top: 50px;
        margin-bottom: 50px;
        animation: floatIn 1.2s cubic-bezier(0.6, 0.8, 0.4, 1) both;
    }
    .cool-hero h1 {
        font-size: 2.6rem;
        font-weight: 700;
        letter-spacing: -1px;
        color: #283044;
    }
    .cool-hero p.lead {
        color: #52606d;
        font-size: 1.22rem;
        margin-bottom: 1.7rem;
    }
    .cool-action-btn {
        transition: transform 0.18s;
    }
    .cool-action-btn:hover {
        transform: scale(1.07) translateY(-2px);
        box-shadow: 0 5px 18px #58a6ff50;
    }
    .get-started {
        background: linear-gradient(92deg,#16c1fe,#638fff);
        color: #fff;
        border: none;
    }
    .demo-btn {
        color: #638fff !important;
        border: 1.5px solid #92bbff;
        background: transparent;
    }
    .poweredby {
        font-size: 0.97rem;
        margin-top: 2.5rem;
        color: #658fad;
        letter-spacing: 0.2px;
        opacity: 0.7;
    }
    @keyframes floatIn {
        from {opacity:0;transform:translateY(50px);}
        to {opacity:1;transform:none;}
    }

    /* Responsive Design for 360px width (phones) */
    @media (max-width: 480px) {
        body {
            font-size: 15px;
        }
        .cool-hero {
            padding: 1.5rem 0.7rem 1.8rem 0.7rem;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .cool-hero h1 {
            font-size: 1.5rem;
        }
        .cool-hero p.lead {
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .cool-action-btn,
        .get-started,
        .demo-btn {
            width: 100%;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
            margin-bottom: 0.5rem;
        }
        .mb-4 {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 0.3rem;
            margin-bottom: 1.2rem !important;
        }
        .cool-hero img {
            width: 90vw !important;
            max-width: 98vw !important;
            min-width: 0;
        }
        .poweredby {
            font-size: 0.84rem;
            margin-top: 1.5rem;
            word-break: break-all;
        }
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="cool-hero text-center">
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
