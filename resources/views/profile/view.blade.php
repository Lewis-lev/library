
@extends('layouts.admin')

@section('title', 'User Profile')

@section('content')

<link rel="stylesheet" href="{{ asset('css/profileView.css') }}">

<div class="profile-card">
    <div class="profile-banner">
        <img
            src="{{ $user->profile_picture ? asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/' . $user->profile_picture) : asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/default-profile.webp') }}"
            class="profile-avatar"
            alt="Profile Picture"
        >
    </div>
    <div class="profile-details">
        <h2 class="mt-2 mb-1" style="font-weight: 700;">{{ $user->name }}</h2>
        <span class="profile-role-badge">
            <i class="fas fa-user-shield"></i> {{ ucfirst($user->role) }}
        </span>
        <table class="profile-info-table mt-3">
            <tr>
                <td class="icon"><i class="fas fa-envelope"></i></td>
                <td class="label">Email</td>
                <td>:</td>
                <td>{{ $user->email ?? '-' }}</td>
            </tr>
            <tr>
                <td class="icon"><i class="fas fa-phone"></i></td>
                <td class="label">Phone</td>
                <td>:</td>
                <td>{{ $user->phone_number ?? '-' }}</td>
            </tr>
            <tr>
                <td class="icon"><i class="fas fa-map-marker-alt"></i></td>
                <td class="label">Address</td>
                <td>:</td>
                <td>{{ $user->address ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
<a href="{{ url()->previous() }}" class="btn back-btn mt-3"><i class="fas fa-arrow-left"></i> Back</a>

<!-- Font Awesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
