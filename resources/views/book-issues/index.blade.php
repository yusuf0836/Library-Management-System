<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Issue & Return | Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <h2 class="mb-1">Book Issue & Return</h2>
                <p class="text-muted mb-0">
                    Manage borrowed books, return dates, and overdue fines.
                </p>
            </div>

            <a class="btn btn-primary" href="{{ route('book-issues.create') }}">
                + Issue Book
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('book-issues.index') }}" class="row g-2">
                    <div class="col-md-10">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ $search }}"
                            placeholder="Search member, member code, book title, or accession number..."
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
                            <th>Member</th>
                            <th>Book Copy</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Fine</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($issues as $issue)
                            <tr>
                                <td>
                                    <strong>{{ $issue->member->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $issue->member->member_code }}
                                    </small>
                                </td>

                                <td>
                                    <strong>{{ $issue->copy->book->title }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $issue->copy->accession_number }}
                                    </small>
                                </td>

                                <td>{{ $issue->issued_at->format('d M, Y') }}</td>
                                <td>{{ $issue->due_at->format('d M, Y') }}</td>

                                <td>
                                    {{ $issue->returned_at?->format('d M, Y') ?? 'Not Returned' }}
                                </td>

                                <td>
                                    @if ($issue->status === 'returned')
                                        <span class="badge bg-success">Returned</span>
                                    @elseif (now()->startOfDay()->greaterThan($issue->due_at))
                                        <span class="badge bg-danger">Overdue</span>
                                    @else
                                        <span class="badge bg-primary">Issued</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($issue->fine)
                                        ৳{{ number_format($issue->fine->amount, 2) }}
                                        <br>
                                        <small class="text-danger">
                                            {{ ucfirst($issue->fine->status) }}
                                        </small>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    @if ($issue->status !== 'returned')
                                        <button
                                            class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#returnModal{{ $issue->id }}"
                                        >
                                            Return
                                        </button>

                                        <div
                                            class="modal fade"
                                            id="returnModal{{ $issue->id }}"
                                            tabindex="-1"
                                        >
                                            <div class="modal-dialog">
                                                <form
                                                    action="{{ route('book-issues.return', $issue) }}"
                                                    method="POST"
                                                >
                                                    @csrf

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Return Book</h5>

                                                            <button
                                                                type="button"
                                                                class="btn-close"
                                                                data-bs-dismiss="modal"
                                                            ></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <p>
                                                                <strong>{{ $issue->copy->book->title }}</strong>
                                                            </p>

                                                            <label class="form-label">
                                                                Return Date
                                                            </label>

                                                            <input
                                                                type="date"
                                                                name="returned_at"
                                                                class="form-control"
                                                                value="{{ date('Y-m-d') }}"
                                                                min="{{ $issue->issued_at->format('Y-m-d') }}"
                                                                required
                                                            >

                                                            <label class="form-label mt-3">
                                                                Return Notes
                                                            </label>

                                                            <textarea
                                                                name="notes"
                                                                class="form-control"
                                                                rows="3"
                                                                placeholder="Optional notes..."
                                                            ></textarea>

                                                            <small class="text-muted d-block mt-2">
                                                                Fine rate: ৳5 per overdue day.
                                                            </small>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button
                                                                type="button"
                                                                class="btn btn-secondary"
                                                                data-bs-dismiss="modal"
                                                            >
                                                                Cancel
                                                            </button>

                                                            <button class="btn btn-success" type="submit">
                                                                Confirm Return
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No book issue record found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>