<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} | Library Management System</title>

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

            <a class="btn btn-outline-light btn-sm" href="{{ route('books.index') }}">
                Back to Books
            </a>
        </div>
    </nav>

    <main class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-3 text-center">
                        @if ($book->cover_image)
                            <img
                                src="{{ asset('storage/' . $book->cover_image) }}"
                                alt="{{ $book->title }}"
                                class="img-fluid rounded shadow-sm"
                                style="max-height: 320px; object-fit: cover;"
                            >
                        @else
                            <div
                                class="d-flex align-items-center justify-content-center bg-secondary text-white rounded mx-auto"
                                style="width: 180px; height: 250px;"
                            >
                                No Cover Image
                            </div>
                        @endif
                    </div>

                    <div class="col-md-9">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2>{{ $book->title }}</h2>

                                <p class="text-muted">
                                    {{ $book->authors->pluck('name')->join(', ') }}
                                </p>
                            </div>

                            <a
                                class="btn btn-primary"
                                href="{{ route('books.edit', $book) }}"
                            >
                                Edit Book
                            </a>
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <strong>ISBN:</strong><br>
                                {{ $book->isbn ?: 'N/A' }}
                            </div>

                            <div class="col-md-6">
                                <strong>Edition:</strong><br>
                                {{ $book->edition ?: 'N/A' }}
                            </div>

                            <div class="col-md-6">
                                <strong>Category:</strong><br>
                                {{ $book->category?->name ?? 'N/A' }}
                            </div>

                            <div class="col-md-6">
                                <strong>Publisher:</strong><br>
                                {{ $book->publisher?->name ?? 'N/A' }}
                            </div>

                            <div class="col-md-6">
                                <strong>Publication Year:</strong><br>
                                {{ $book->publication_year ?: 'N/A' }}
                            </div>

                            <div class="col-md-6">
                                <strong>Authors:</strong><br>
                                {{ $book->authors->pluck('name')->join(', ') }}
                            </div>
                        </div>

                        <hr>

                        <h5>Description</h5>
                        <p class="text-muted">
                            {{ $book->description ?: 'No description available.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $totalCopies }}</h3>
                        <p class="mb-0 text-muted">Total Copies</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-success">{{ $availableCopies }}</h3>
                        <p class="mb-0 text-muted">Available Copies</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-info">{{ $issuedCopies }}</h3>
                        <p class="mb-0 text-muted">Issued Copies</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body">
                <h4 class="mb-3">Physical Copies</h4>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>Accession Number</th>
                                <th>Shelf Location</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($book->copies as $copy)
                                <tr>
                                    <td>{{ $copy->accession_number }}</td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        No physical copy has been added yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>