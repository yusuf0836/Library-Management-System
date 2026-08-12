<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books | Library Management System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background:#1e3a8a;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                Library Management System
            </a>

            <a class="btn btn-outline-light btn-sm" href="{{ route('dashboard') }}">
                Dashboard
            </a>
        </div>
    </nav>

    <main class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="mb-1">Books</h2>
                <p class="text-muted mb-0">Manage your library book catalog.</p>
            </div>

            <a class="btn btn-primary" href="{{ route('books.create') }}">
                + Add New Book
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('books.index') }}" class="row g-2">
                    <div class="col-md-10">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ $search }}"
                            placeholder="Search by book title or ISBN..."
                        >
                    </div>

                    <div class="col-md-2 d-grid">
                        <button class="btn btn-dark" type="submit">Search</button>
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
                                    <strong>{{ $book->title }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        ISBN: {{ $book->isbn ?: 'N/A' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $book->authors->pluck('name')->join(', ') }}
                                </td>

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
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No books found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($books->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                @if ($books->onFirstPage())
                    <span class="btn btn-outline-secondary disabled">Previous</span>
                @else
                    <a class="btn btn-outline-secondary" href="{{ $books->previousPageUrl() }}">
                        Previous
                    </a>
                @endif

                <span class="text-muted">
                    Page {{ $books->currentPage() }} of {{ $books->lastPage() }}
                </span>

                @if ($books->hasMorePages())
                    <a class="btn btn-outline-secondary" href="{{ $books->nextPageUrl() }}">
                        Next
                    </a>
                @else
                    <span class="btn btn-outline-secondary disabled">Next</span>
                @endif
            </div>
        @endif
    </main>
</body>
</html>