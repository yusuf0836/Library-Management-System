<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Copies | Library Management System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background:#1e3a8a;">
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
                <h2 class="mb-1">Book Copies</h2>
                <p class="text-muted mb-0">
                    Manage physical copies, shelf locations, and availability.
                </p>
            </div>

            <a class="btn btn-primary" href="{{ route('book-copies.create') }}">
                + Add Book Copy
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('book-copies.index') }}" class="row g-2">
                    <div class="col-md-10">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ $search }}"
                            placeholder="Search by book title, accession number, or shelf..."
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
                            <th>Book Title</th>
                            <th>Accession Number</th>
                            <th>Shelf Location</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($copies as $copy)
                            <tr>
                                <td>{{ $copies->firstItem() + $loop->index }}</td>
                                <td>{{ $copy->book->title }}</td>
                                <td><strong>{{ $copy->accession_number }}</strong></td>
                                <td>{{ $copy->shelf_location ?: 'N/A' }}</td>
                                <td>
                                    @if ($copy->status === 'available')
                                        <span class="badge bg-success">Available</span>
                                    @elseif ($copy->status === 'issued')
                                        <span class="badge bg-primary">Issued</span>
                                    @elseif ($copy->status === 'reserved')
                                        <span class="badge bg-warning text-dark">Reserved</span>
                                    @elseif ($copy->status === 'lost')
                                        <span class="badge bg-danger">Lost</span>
                                    @else
                                        <span class="badge bg-secondary">Damaged</span>
                                    @endif
                                </td>

                                <td class="text-nowrap">
                                    <a
                                        class="btn btn-sm btn-outline-primary"
                                        href="{{ route('book-copies.edit', $copy) }}"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        class="d-inline"
                                        action="{{ route('book-copies.destroy', $copy) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this copy?');"
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
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No book copy found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($copies->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                @if ($copies->onFirstPage())
                    <span class="btn btn-outline-secondary disabled">Previous</span>
                @else
                    <a class="btn btn-outline-secondary" href="{{ $copies->previousPageUrl() }}">
                        Previous
                    </a>
                @endif

                <span class="text-muted">
                    Page {{ $copies->currentPage() }} of {{ $copies->lastPage() }}
                </span>

                @if ($copies->hasMorePages())
                    <a class="btn btn-outline-secondary" href="{{ $copies->nextPageUrl() }}">
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