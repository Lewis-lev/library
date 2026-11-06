@php
    // Fallback for direct view access; REMOVE if your controller always sets this variable!
    if (!isset($isVerified)) {
        // Default to user's email_verified_at, or adjust this logic per your requirements
        $isVerified = auth()->user() && auth()->user()->email_verified_at;
    }

    $verifyUrl = route('verification.notice');
@endphp

@extends('layouts.borrower')
@section('title', 'Profile Detail')

@section('content')
<link rel="stylesheet" href="{{ asset('css/editProfile.css') }}">
<div class="container profile-edit-page">
    <div class="data-page-heading">
        <div>
            <span class="eyebrow"><i class="fa-solid fa-user me-2"></i>Your account</span>
            <h1>My profile</h1>
            <p>Manage your contact details and account security.</p>
        </div>
    </div>

    <!-- Show session alerts, if any -->
    @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="profileAlert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="profileAlert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card profile-summary-card">
        <div class="profile-summary-identity">
            <img
                src="{{ auth()->user()->profile_picture ? asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/' . auth()->user()->profile_picture) : asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/default-profile.webp') }}"
                class="profile-summary-avatar"
                alt="Profile picture">
            <div>
                <span class="eyebrow">Library member</span>
                <h2>{{ auth()->user()->name }}</h2>
                <span class="member-role-badge"><i class="fa-solid fa-bookmark me-1"></i>Borrower</span>
            </div>
            @if($isVerified)
                <button id="editProfileBtn" class="btn btn-primary profile-edit-toggle" onclick="toggleEditProfile(true)">
                    <i class="fa-solid fa-pen me-2"></i>Edit profile
                </button>
            @else
                <button id="editProfileBtn" class="btn btn-primary profile-edit-toggle" onclick="showVerifyError()" type="button">
                    <i class="fa-solid fa-pen me-2"></i>Edit profile
                </button>
            @endif
        </div>
        <div class="profile-summary-details">
            <div class="profile-summary-detail">
                <span><i class="fa-solid fa-envelope me-2"></i>Email address</span>
                <strong>{{ auth()->user()->email }}</strong>
            </div>
            <div class="profile-summary-detail">
                <span><i class="fa-solid fa-phone me-2"></i>Phone number</span>
                <strong>{{ auth()->user()->phone_number ?? 'Not provided' }}</strong>
            </div>
            <div class="profile-summary-detail">
                <span><i class="fa-solid fa-location-dot me-2"></i>Address</span>
                <strong>{{ auth()->user()->address ?? 'Not provided' }}</strong>
            </div>
        </div>
    </div>

    <div class="card profile-editor-card mx-auto mt-4"
        style="@if ($errors->any()) display:block; @else display:none; @endif"
        id="editProfileCard">
        <div class="card-body">
            <h5 class="mb-3 text-center">Edit Profile</h5>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('PATCH')

                <div class="mb-3 text-center">
                    <label for="profile_picture" class="profile-picture-label" tabindex="0" aria-label="Change profile picture">
                        <img
                            id="profilePicturePreview"
                            src="{{ auth()->user()->profile_picture ? asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/' . auth()->user()->profile_picture) : asset(path: 'https://pub-94f23dc765bc4b62a5ef536b35ffa982.r2.dev/img/profile_pict/default-profile.webp') }}"
                            class="rounded-circle mb-2 profile-picture-img"
                            alt="Profile Picture">
                        <span class="profile-picture-overlay" id="profilePictureOverlay">
                            Change
                        </span>
                    </label>
                    <input
                        type="file"
                        class="form-control d-none @error('profile_picture') is-invalid @enderror"
                        style="display:none"
                        id="profile_picture"
                        name="profile_picture"
                        accept="image/jpeg,image/png,image/gif,image/webp"
                        onchange="previewProfilePicture(this)">
                    @error('profile_picture')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Username</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                        name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                        name="phone_number" id="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}">
                    @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                        name="address" id="address">{{ old('address', auth()->user()->address) }}</textarea>
                    @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                @if ($errors->any() && ( $errors->has('password') || $errors->has('password_confirmation') || $errors->has('current_password') ))
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach (['current_password','password','password_confirmation'] as $field)
                        @foreach ($errors->get($field) as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="mb-3">
                    <!-- Button to show password change fields -->
                    <button type="button"
                        class="btn btn-outline-warning"
                        id="showPasswordSectionBtn"
                        onclick="togglePasswordSection(true)">
                        Change Password
                    </button>
                </div>

                <div id="passwordSection" style="display: none;">
                    <div class="mb-3">
                        <label for="current_password" class="block">Current Password</label>
                        <input id="current_password" type="password" name="current_password" class="form-control" autocomplete="current-password">
                        @error('current_password')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="block">New Password</label>
                        <input id="password" type="password" name="password" class="form-control" autocomplete="new-password">
                        @error('password')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="block">Confirm New Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-secondary" onclick="togglePasswordSection(false)">Cancel Change Password</button>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="toggleEditProfile(false)">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>

</div>
@push('scripts')
<script>
    window.verifyUrl = @json($verifyUrl);
    window.editProfileShowErrors = {{ $errors->any() ? 'true' : 'false' }};
    window.editProfileShowPasswordSection = {{ ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation')) ? 'true' : 'false' }};
</script>
<script src="{{ asset('js/editProfile.js') }}"></script>
@endpush
@endsection
