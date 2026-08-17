@extends('layouts.app')

@section('title', 'Edit Publisher | Library Management System')
@section('page-title', 'Edit Publisher')
@section('page-subtitle', 'Update selected publisher information')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form
                        action="{{ route('publishers.update', $publisher) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Publisher Name <span class="text-danger">*</span>
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $publisher->name) }}"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email Address
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $publisher->email) }}"
                                >

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    Phone Number
                                </label>

                                <input
                                    id="phone"
                                    type="text"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $publisher->phone) }}"
                                >

                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3 mb-4">
                            <label for="address" class="form-label">
                                Address
                            </label>

                            <textarea
                                id="address"
                                name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                rows="4"
                            >{{ old('address', $publisher->address) }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button class="btn btn-primary" type="submit">
                            Update Publisher
                        </button>

                        <a
                            class="btn btn-outline-secondary"
                            href="{{ route('publishers.index') }}"
                        >
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection