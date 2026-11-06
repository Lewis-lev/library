@extends('layouts.admin')

@section('title', 'Member Profile')

@section('content')
<div class="container member-profile-page">
    <a href="{{ url()->previous() }}" class="profile-back-link">
        <i class="fa-solid fa-arrow-left me-2"></i>Back to members
    </a>

    <article class="member-profile-card">
        <div class="member-profile-banner">
            <span class="member-profile-banner-mark"><i class="fa-solid fa-book-open"></i></span>
        </div>
        <div class="member-profile-content">
            <img
                src="{{ $user->profile_picture ? asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/' . $user->profile_picture) : asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/default-profile.webp') }}"
                class="member-profile-avatar"
                alt="Profile picture of {{ $user->name }}"
            >
            <div class="member-profile-heading">
                <span class="eyebrow">Library member</span>
                <h1>{{ $user->name }}</h1>
                <div class="member-profile-badges">
                    <span class="member-role-badge">
                        <i class="fa-solid fa-user me-1"></i>{{ ucfirst($user->role) }}
                    </span>
                    @if($user->email_verified_at)
                        <span class="member-status-verified">
                            <i class="fa-solid fa-circle-check me-1"></i>Email verified
                        </span>
                    @else
                        <span class="member-status-pending">
                            <i class="fa-solid fa-clock me-1"></i>Email not verified
                        </span>
                    @endif
                </div>
            </div>

            <div class="member-profile-divider"></div>
            <dl class="member-profile-details">
                <div class="member-profile-detail">
                    <dt><i class="fa-solid fa-envelope"></i>Email address</dt>
                    <dd><a href="mailto:{{ $user->email }}">{{ $user->email ?? 'Not provided' }}</a></dd>
                </div>
                <div class="member-profile-detail">
                    <dt><i class="fa-solid fa-phone"></i>Phone number</dt>
                    <dd>{{ $user->phone_number ?? 'Not provided' }}</dd>
                </div>
                <div class="member-profile-detail">
                    <dt><i class="fa-solid fa-location-dot"></i>Address</dt>
                    <dd>{{ $user->address ?? 'Not provided' }}</dd>
                </div>
                <div class="member-profile-detail">
                    <dt><i class="fa-solid fa-calendar-days"></i>Member since</dt>
                    <dd>{{ $user->created_at ? $user->created_at->format('d M Y') : 'Not available' }}</dd>
                </div>
            </dl>
        </div>
    </article>
</div>
@endsection
