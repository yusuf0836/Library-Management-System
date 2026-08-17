@extends('layouts.app')

@section('title', 'Edit Category | Library Management System')
@section('page-title', 'Edit Book Category')
@section('page-subtitle', 'Update selected category information')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form
                        action="{{ route('categories.update', $category) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $category->name) }}"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="5"
                            >{{ old('description', $category->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button class="btn btn-primary" type="submit">
                            Update Category
                        </button>

                        <a
                            class="btn btn-outline-secondary"
                            href="{{ route('categories.index') }}"
                        >
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection