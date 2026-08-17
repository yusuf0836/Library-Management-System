@extends('layouts.app')

@section('title', 'My Profile | Library Management System')
@section('page-title', 'My Profile')
@section('page-subtitle', 'Manage your account information, profile photo, and password')

@section('content')
    <style>
        .profile-image {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border: 4px solid #dbeafe;
            border-radius: 50%;
        }

        .profile-placeholder {
            width: 130px;
            height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #dbeafe;
            border-radius: 50%;
            background: #1d4ed8;
            color: white;
            font-size: 42px;
            font-weight: bold;
        }
    </style>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="mb-4">Profile Information</h5>

                    <form
                        action="{{ route('profile.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            @if (auth()->user()->photo)
                                <img
                                    src="{{ asset('storage/' . auth()->user()->photo) }}"
                                    alt="Profile Photo"
                                    class="profile-image"
                                >
                            @else
                                <div class="profile-placeholder mx-auto">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">
                                Profile Photo
                            </label>

                            <input
                                id="photo"
                                type="file"
                                name="photo"
                                class="form-control @error('photo') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <small class="text-muted">
                                JPG, JPEG, PNG, or WEBP. Maximum file size: 2 MB.
                            </small>

                            @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Full Name
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', auth()->user()->name) }}"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email Address
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', auth()->user()->email) }}"
                                required
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        @if ($user->role === 'member' && $user->member)
                            <hr class="my-4">

                            <h6 class="mb-3">Library Membership Information</h6>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Member Code</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ $user->member->member_code }}"
                                        readonly
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Membership Status</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ $user->member->is_active ? 'Active' : 'Inactive' }}"
                                        readonly
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Department</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ $user->member->department ?: 'N/A' }}"
                                        readonly
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Joining Date</label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ $user->member->joined_at->format('d M, Y') }}"
                                        readonly
                                    >
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    Phone Number
                                </label>

                                <input
                                    id="phone"
                                    type="text"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->member->phone) }}"
                                    placeholder="01XXXXXXXXX"
                                >

                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="address" class="form-label">
                                    Address
                                </label>

                                <textarea
                                    id="address"
                                    name="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Write your address..."
                                >{{ old('address', $user->member->address) }}</textarea>

                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label">Account Role</label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ ucfirst(auth()->user()->role) }}"
                                readonly
                            >
                        </div>

                        <button class="btn btn-primary" type="submit">
                            Save Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="mb-4">Change Password</h5>

                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                Current Password
                            </label>

                            <input
                                id="current_password"
                                type="password"
                                name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                required
                            >

                            @error('current_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                New Password
                            </label>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirm New Password
                            </label>

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required
                            >
                        </div>

                        <button class="btn btn-warning" type="submit">
                            Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection