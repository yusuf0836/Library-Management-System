@extends('layouts.app')

@section('title', 'Books | Library Management System')
@section('page-title', 'Books')
@section('page-subtitle', 'Manage and search your library book catalog')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Book Catalog</h4>
            <p class="text-muted mb-0">
                Add, search, filter, and manage books.
            </p>
        </div>

        <a class="btn btn-primary" href="{{ route('books.create') }}">
            + Add New Book
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('books.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ $search }}"
                        placeholder="Search by title or ISBN..."
                    >
                </div>

                <div class="col-md-2">
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected($categoryId == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="author_id" class="form-select">
                        <option value="">All Authors</option>

                        @foreach ($authors as $author)
                            <option
                                value="{{ $author->id }}"
                                @selected($authorId == $author->id)
                            >
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="publisher_id" class="form-select">
                        <option value="">All Publishers</option>

                        @foreach ($publishers as $publisher)
                            <option
                                value="{{ $publisher->id }}"
                                @selected($publisherId == $publisher->id)
                            >
                                {{ $publisher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-grid">
                    <button class="btn btn-dark" type="submit">
                        Filter
                    </button>
                </div>

                <div class="col-md-1 d-grid">
                    <a class="btn btn-outline-secondary" href="{{ route('books.index') }}">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Cover</th>
                        <th>Book</th>
                        <th>Author(s)</th>
                        <th>Category</th>
                        <th>Publisher</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $books->firstItem() + $loop->index }}</td>

                            <td>
                                @if ($book->cover_image)
                                    <img
                                        src="{{ asset('storage/' . $book->cover_image) }}"
                                        alt="{{ $book->title }}"
                                        style="width:45px;height:60px;object-fit:cover;"
                                        class="rounded border"
                                    >
                                @else
                                    <div
                                        class="bg-light border rounded d-flex align-items-center justify-content-center text-muted"
                                        style="width:45px;height:60px;font-size:10px;"
                                    >
                                        No Cover
                                    </div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $book->title }}</strong>
                                <br>

                                <small class="text-muted">
                                    ISBN: {{ $book->isbn ?: 'N/A' }}
                                </small>
                            </td>

                            <td>{{ $book->authors->pluck('name')->join(', ') }}</td>
                            <td>{{ $book->category?->name ?? 'N/A' }}</td>
                            <td>{{ $book->publisher?->name ?? 'N/A' }}</td>
                            <td>{{ $book->publication_year ?? 'N/A' }}</td>

                            <td class="text-nowrap">
                                <a
                                    class="btn btn-sm btn-outline-dark"
                                    href="{{ route('books.show', $book) }}"
                                >
                                    View
                                </a>

                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('books.edit', $book) }}"
                                >
                                    Edit
                                </a>

                                <form
                                    class="d-inline"
                                    action="{{ route('books.destroy', $book) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this book?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No books found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection