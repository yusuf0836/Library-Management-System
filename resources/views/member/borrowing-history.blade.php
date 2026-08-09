<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowings | Library Management System</title>

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
        <div class="mb-4">
            <h2 class="mb-1">My Borrowing History</h2>
            <p class="text-muted mb-0">
                View your currently borrowed and previously returned books.
            </p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>Book</th>
                            <th>Accession No.</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Fine</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($issues as $issue)
                            <tr>
                                <td>{{ $issue->copy->book->title }}</td>
                                <td>{{ $issue->copy->accession_number }}</td>
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

                                        @if ($issue->fine->status === 'paid')
                                            <small class="text-success">Paid</small>
                                        @else
                                            <small class="text-danger">
                                                {{ ucfirst($issue->fine->status) }}
                                            </small>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    You have no borrowing record yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>