<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Report | Library Management System</title>

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
            <h2 class="mb-1 text-danger">Overdue Book Report</h2>

            <p class="text-muted mb-0">
                Books that have passed their due date and are not yet returned.
            </p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-danger">
                        <tr>
                            <th>Member</th>
                            <th>Book</th>
                            <th>Accession No.</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Overdue Days</th>
                            <th>Estimated Fine</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($overdueIssues as $issue)
                            @php
                                $overdueDays = $issue->due_at->diffInDays(now());
                                $estimatedFine = $overdueDays * 5;
                            @endphp

                            <tr>
                                <td>
                                    <strong>{{ $issue->member->user->name }}</strong>
                                    <br>

                                    <small class="text-muted">
                                        {{ $issue->member->member_code }}
                                    </small>
                                </td>

                                <td>{{ $issue->copy->book->title }}</td>
                                <td>{{ $issue->copy->accession_number }}</td>
                                <td>{{ $issue->issued_at->format('d M, Y') }}</td>
                                <td>{{ $issue->due_at->format('d M, Y') }}</td>

                                <td>
                                    <span class="badge bg-danger">
                                        {{ $overdueDays }} day(s)
                                    </span>
                                </td>

                                <td>
                                    ৳{{ number_format($estimatedFine, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-success">
                                    No overdue book found. Great!
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