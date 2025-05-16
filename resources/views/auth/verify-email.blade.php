@extends('layouts.guest')

@section('title', __('Verify Email'))

@section('content')
<style>

    body {
                background: linear-gradient(120deg, #c9d6ff 0%, #e2e2e2 100%);
    }
    .verify-hero-bg {
        min-height: 88vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    .verify-card {
        background: #fff;
        border-radius: 32px;
        box-shadow: 0 6px 48px rgba(68,91,254,0.15);
        padding: 2.2rem 2.5rem 2rem 2.5rem;
        margin: 32px auto;
        animation: fadein 0.6s cubic-bezier(.49,.41,.39,1.14);
    }
    @keyframes fadein {
        from { opacity: 0; transform: translateY(32px); }
        to {   opacity: 1; transform: translateY(0); }
    }
    .envelope-anim {
        width: 86px; height: 86px;
        margin: 0 auto 1.3em auto;
        display: block;
    }
    .verify-title {
        font-weight: 700;
        font-size: 2rem;
        letter-spacing: .01em;
        color: #3a50e8;
        margin-bottom: .2em;
        text-align: center;
    }
    .verify-desc {
        font-size: 1.07em;
        text-align: center;
        color: #616480;
    }
    .verify-success {
        background: #eafbe7;
        color: #217a46;
        border: 1px solid #b2efcf;
        font-size: 1.05em;
        padding: 0.85em 1.25em;
        border-radius: .9em;
        margin-bottom: 1em;
        text-align: center;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(120,200,120,.04);
    }
    .verify-btns {
        display: flex;
        gap: 1.1em;
        margin-top: 2em;
        justify-content: center;
    }
    .verify-btns .btn-primary {
        background: linear-gradient(99deg, #4973ff 0%, #726cff 100%);
        border: none;
        font-weight: 600;
        padding-inline: 1.7em;
        transition: filter .13s;
    }
    .verify-btns .btn-primary:hover {
        filter: brightness(1.1);
    }
    .verify-btns .btn-link {
        color: #f1416b;
        font-weight: 600;
        text-decoration: none;
        transition: color .13s;
    }
    .verify-btns .btn-link:hover {
        color: #b52545;
        text-decoration: underline;
    }
</style>
<div class="verify-hero-bg">
    <div class="verify-card w-70">
        <!-- Animated Envelope SVG -->
        <svg class="envelope-anim" viewBox="0 0 100 100" fill="none">
            <rect x="10" y="25" width="80" height="50" rx="10" fill="#f5f6f8" stroke="#4973ff" stroke-width="3"/>
            <polyline points="10,25 50,60 90,25" fill="none" stroke="#4973ff" stroke-width="3"/>
            <polyline points="10,75 50,48 90,75" fill="none" stroke="#4973ff" stroke-width="2.7"/>
            <g>
                <animateTransform attributeName="transform" type="translate"
                  values="0 0; 0 -6; 0 0" keyTimes="0; 0.5; 1" dur="1.5s" repeatCount="indefinite"/>
            </g>
        </svg>
        <div class="verify-title">
            {{ __('Verify Your Email Address') }}
        </div>
        <div class="verify-desc mb-3">
            {{ __("Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent you.") }}
            <br>
            <span style="font-weight:500;">{{ __("Didn't receive it? Check your spam folder!") }}</span>
        </div>
        @if (session('status') == 'verification-link-sent')
            <div class="verify-success">
                {{ __('A new verification link has been sent to your email.') }}
            </div>
        @endif

        <div class="verify-btns">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane me-1"></i>
                    {{ __('Resend Email') }}
                </button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link">
                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i>
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
