@extends('layouts.app')

@section('title', 'Edit Book Copy | Library Management System')
@section('page-title', 'Edit Book Copy')
@section('page-subtitle', 'Update book copy and availability details')

@section('content')
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-1">Edit Book Copy</h2>
                <p class="text-muted mb-4">
                    Update copy information and availability status.
                </p>

                <form action="{{ route('book-copies.update', $bookCopy) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('book-copies.form', ['bookCopy' => $bookCopy])

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">
                            Update Book Copy
                        </button>

                        <a class="btn btn-secondary" href="{{ route('book-copies.index') }}">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
@endsection