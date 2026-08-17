@extends('layouts.app')

@section('title', 'Add Book Copy | Library Management System')
@section('page-title', 'Add Book Copy')
@section('page-subtitle', 'Add a physical copy to the library')

@section('content')
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-1">Add Book Copy</h2>
                <p class="text-muted mb-4">
                    Add a physical copy of a book to the library.
                </p>

                <form action="{{ route('book-copies.store') }}" method="POST">
                    @csrf

                    @include('book-copies.form', ['bookCopy' => null])

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">
                            Save Book Copy
                        </button>

                        <a class="btn btn-secondary" href="{{ route('book-copies.index') }}">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
@endsection