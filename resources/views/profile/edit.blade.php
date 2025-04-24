<style>
    .profile-picture-label {
        cursor: pointer;
        display: inline-block;
        position: relative;
    }

    .profile-picture-img {
        width: 96px;
        height: 96px;
        object-fit: cover;
        border: 2px solid #eee;
        transition: box-shadow 0.2s, transform 0.2s;
        border-radius: 50%;
        background: #fafafa;
    }

    .profile-picture-label:hover .profile-picture-img,
    .profile-picture-label:focus .profile-picture-img {
        box-shadow: 0 0 0 4px #bee3f8;
        transform: scale(1.04);
        z-index: 2;
    }

    .profile-picture-overlay {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        backdrop-filter: blur(1.5px);
        color: #fff;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.23s;
        font-size: 1.07em;
        font-weight: 500;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.35);
        z-index: 1;
        user-select: none;
    }

    .profile-picture-label:hover .profile-picture-overlay,
    .profile-picture-label:focus .profile-picture-overlay {
        opacity: 1;
        pointer-events: all;
    }

    .profile-picture-overlay svg {
        margin-bottom: 5px;
        color: #cbd5e1;
    }
</style>

@extends('layouts.admin')
@section('title', 'Profile Detail')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">My Profile</h2>

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

    <div class="card mx-auto" style="max-width:1000px;">
        <div class="card-body text-center">

            <!-- PROFILE IMAGE -->
            <img
                src="{{ auth()->user()->profile_picture ? asset('storage/profile_pict/' . auth()->user()->profile_picture) : asset('storage/profile_pict/default-profile.jpg') }}"
                class="rounded-circle mb-3"
                style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #eee;"
                alt="Profile Picture">

            <!-- PROFILE DATA (show only, not editable) -->
            <table class="table table-borderless text-start mb-4">
                <tr>
                    <th>Name</th>
                    <td>{{ auth()->user()->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ auth()->user()->email }}</td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td>{{ auth()->user()->phone_number ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ auth()->user()->address ?? '-' }}</td>
                </tr>
            </table>

            <!-- SHOW EDIT BUTTON -->
            <button id="editProfileBtn" class="btn btn-primary mb-2" onclick="toggleEditProfile(true)">Edit Profile</button>
        </div>
    </div>

    <!-- HIDDEN EDIT FORM - toggled by JS below -->
    <div class="card mx-auto mt-4"
        style="max-width:1000px; @if ($errors->any()) display:block; @else display:none; @endif"
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
                            src="{{ auth()->user()->profile_picture ? asset('storage/profile_pict/' . auth()->user()->profile_picture) : asset('storage/profile_pict/default-profile.jpg') }}"
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
                    <label for="name" class="form-label">Full Name</label>
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
@endsection

@push('scripts')
<script>
    function toggleEditProfile(edit) {
        document.getElementById('editProfileCard').style.display = edit ? 'block' : 'none';
        document.getElementById('editProfileBtn').style.display = edit ? 'none' : 'inline-block';
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function togglePasswordSection(show) {
        document.getElementById('passwordSection').style.display = show ? 'block' : 'none';
        document.getElementById('showPasswordSectionBtn').style.display = show ? 'none' : 'inline-block';
        if (!show) {
            document.getElementById('current_password').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Edit Profile Form
        @if($errors->any())
        document.getElementById('editProfileCard').style.display = 'block';
        document.getElementById('editProfileBtn').style.display = 'none';
        // Auto scroll to bottom
        setTimeout(function() {
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        }, 200);
        @endif

        // Password Section
        @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
        document.getElementById('passwordSection').style.display = 'block';
        var showBtn = document.getElementById('showPasswordSectionBtn');
        if (showBtn) showBtn.style.display = 'none';
        @endif
    });
</script>
<script>
    // Overlay on hover
    const label = document.querySelector('label[for="profile_picture"]');
    const img = document.getElementById('profilePicturePreview');
    function previewProfilePicture(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script>
    // Auto-close the alert after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        // Handles multiple alerts, if both "status" and "error" present.
        document.querySelectorAll('.alert[role="alert"]').forEach(function(alert){
            setTimeout(function() {
                // Bootstrap's .alert('close') needs jQuery for BS4, but in BS5 you can just remove .show
                alert.classList.remove('show');
                // After fade out, remove from dom for cleanliness
                setTimeout(() => alert.remove(), 300);
            }, 3000);
        });
    });
</script>
@endpush
