<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Library Management System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f8fafc;
        }

        .navbar {
            background: #1e3a8a;
        }

        .stat-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 5px 18px rgba(15, 23, 42, 0.08);
        }

        .stat-number {
            font-size: 30px;
            font-weight: 700;
        }

        .stat-label {
            color: #64748b;
            font-size: 14px;
        }

        .nav-card {
            border: none;
            border-radius: 12px;
            transition: 0.2s;
        }

        .nav-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(15, 23, 42, 0.12);
        }

        .nav-card a {
            color: inherit;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                Library Management System
            </a>

            <div class="d-flex align-items-center gap-3">
                <span class="text-white small d-none d-md-inline">
                    {{ auth()->user()->name }}
                    ({{ ucfirst(auth()->user()->role) }})
                </span>

                <a class="btn btn-outline-light btn-sm" href="{{ route('profile.edit') }}">
                    Profile
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button class="btn btn-outline-light btn-sm" type="submit">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <div class="mb-4">
            <h2 class="mb-1">
                Welcome, {{ auth()->user()->name }}
            </h2>

            <p class="text-muted mb-0">
                @if (auth()->user()->role === 'member')
                    View your library borrowing information and fine status.
                @else
                    Manage books, members, circulation, fines, and reports.
                @endif
            </p>
        </div>

        @if (auth()->user()->role === 'member')

            {{-- Member Dashboard --}}
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-number text-primary">
                                {{ $activeBorrowings }}
                            </div>

                            <div class="stat-label">
                                Active Borrowings
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-number text-danger">
                                {{ $overdueBorrowings }}
                            </div>

                            <div class="stat-label">
                                Overdue Books
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-number text-warning">
                                ৳{{ number_format($unpaidFine, 2) }}
                            </div>

                            <div class="stat-label">
                                Outstanding Fine
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5>My Library Account</h5>

                    <p class="text-muted">
                        View your currently borrowed books, return history,
                        due dates, and fine information.
                    </p>

                    <a class="btn btn-primary" href="{{ route('member.borrowings') }}">
                        View My Borrowing History
                    </a>
                </div>
            </div>

        @else

            {{-- Admin and Librarian Statistics --}}
            <div class="row g-4 mb-4">
                <div class="col-md-4 col-lg-3">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-number text-primary">
                                {{ $totalBooks }}
                            </div>

                            <div class="stat-label">
                                Total Book Titles
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-3">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-number text-success">
                                {{ $availableCopies }}
                            </div>

                            <div class="stat-label">
                                Available Copies
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-3">
                    <div class="card stat-card h-100">
                        <div class="card-body">
                            <div class="stat-number text-info">
                                {{ $issuedCopies }}
                            </div>

                            <div class="stat-label">
                                Currently Issued
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-3">
                    <div class="card stat-card h-100">
                        <div class="stat-number text-dark p-3 pb-0">
                            {{ $activeMembers }}
                        </div>

                        <div class="card-body pt-2">
                            <div class="stat-label">
                                Active Members
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card stat-card h-100 border-start border-danger border-4">
                        <div class="card-body">
                            <div class="stat-number text-danger">
                                {{ $overdueIssues }}
                            </div>

                            <div class="stat-label">
                                Overdue Borrowings
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card stat-card h-100 border-start border-warning border-4">
                        <div class="card-body">
                            <div class="stat-number text-warning">
                                ৳{{ number_format($outstandingFine, 2) }}
                            </div>

                            <div class="stat-label">
                                Outstanding Fine Amount
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Modules --}}
            <h4 class="mb-3">Library Modules</h4>

            <div class="row g-3 mb-5">
                <div class="col-md-6 col-lg-3">
                    <div class="card nav-card h-100 shadow-sm">
                        <a href="{{ route('books.index') }}">
                            <div class="card-body">
                                <h5>📚 Books</h5>
                                <p class="text-muted mb-0 small">
                                    Manage book catalog.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card nav-card h-100 shadow-sm">
                        <a href="{{ route('book-copies.index') }}">
                            <div class="card-body">
                                <h5>📖 Book Copies</h5>
                                <p class="text-muted mb-0 small">
                                    Manage copies and shelves.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card nav-card h-100 shadow-sm">
                        <a href="{{ route('members.index') }}">
                            <div class="card-body">
                                <h5>👥 Members</h5>
                                <p class="text-muted mb-0 small">
                                    Manage member accounts.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card nav-card h-100 shadow-sm">
                        <a href="{{ route('book-issues.index') }}">
                            <div class="card-body">
                                <h5>🔄 Issue & Return</h5>
                                <p class="text-muted mb-0 small">
                                    Manage circulation records.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card nav-card h-100 shadow-sm">
                        <a href="{{ route('categories.index') }}">
                            <div class="card-body">
                                <h5>🗂️ Categories</h5>
                                <p class="text-muted mb-0 small">
                                    Manage book categories.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card nav-card h-100 shadow-sm">
                        <a href="{{ route('authors.index') }}">
                            <div class="card-body">
                                <h5>✍️ Authors</h5>
                                <p class="text-muted mb-0 small">
                                    Manage author records.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card nav-card h-100 shadow-sm">
                        <a href="{{ route('publishers.index') }}">
                            <div class="card-body">
                                <h5>🏢 Publishers</h5>
                                <p class="text-muted mb-0 small">
                                    Manage publisher records.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card nav-card h-100 shadow-sm">
                        <a href="{{ route('fines.index') }}">
                            <div class="card-body">
                                <h5>💰 Fines</h5>
                                <p class="text-muted mb-0 small">
                                    Fine payments and records.
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                @if (auth()->user()->role === 'admin')
                    <div class="col-md-6 col-lg-3">
                        <div class="card nav-card h-100 shadow-sm">
                            <a href="{{ route('settings.index') }}">
                                <div class="card-body">
                                    <h5>⚙️ Settings</h5>
                                    <p class="text-muted mb-0 small">
                                        Library rules and system settings.
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Recent Issues --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Recent Issue Records</h4>

                <a class="btn btn-sm btn-outline-danger" href="{{ route('reports.overdue') }}">
                    View Overdue Report
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>Member</th>
                                <th>Book</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($recentIssues as $issue)
                                <tr>
                                    <td>
                                        {{ $issue->member->user->name }}
                                        <br>
                                        <small class="text-muted">
                                            {{ $issue->member->member_code }}
                                        </small>
                                    </td>

                                    <td>{{ $issue->copy->book->title }}</td>
                                    <td>{{ $issue->issued_at->format('d M, Y') }}</td>
                                    <td>{{ $issue->due_at->format('d M, Y') }}</td>

                                    <td>
                                        @if ($issue->status === 'returned')
                                            <span class="badge bg-success">Returned</span>
                                        @elseif (now()->startOfDay()->greaterThan($issue->due_at))
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-primary">Issued</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No issue record found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @endif
    </main>
</body>
</html>