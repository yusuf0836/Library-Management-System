@extends('layouts.app')

@section('title', 'Edit Author | Library Management System')
@section('page-title', 'Edit Author')
@section('page-subtitle', 'Update selected author information')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form
                        action="{{ route('authors.update', $author) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Author Name <span class="text-danger">*</span>
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $author->name) }}"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="biography" class="form-label">
                                Biography
                            </label>

                            <textarea
                                id="biography"
                                name="biography"
                                class="form-control @error('biography') is-invalid @enderror"
                                rows="6"
                            >{{ old('biography', $author->biography) }}</textarea>

                            @error('biography')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button class="btn btn-primary" type="submit">
                            Update Author
                        </button>

                        <a
                            class="btn btn-outline-secondary"
                            href="{{ route('authors.index') }}"
                        >
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection