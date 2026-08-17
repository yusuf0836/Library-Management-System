@extends('layouts.app')

@section('title', 'Dashboard | Library Management System')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of library activities and records')

@section('content')
    <style>
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

        .module-card {
            border: none;
            border-radius: 12px;
            transition: 0.2s;
        }

        .module-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(15, 23, 42, 0.12);
        }

        .module-card a {
            display: block;
            color: inherit;
            text-decoration: none;
        }
    </style>

    @if (auth()->user()->role === 'member')

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

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5>My Library Account</h5>

                <p class="text-muted">
                    View your currently borrowed books, due dates,
                    return history, and fine information.
                </p>

                <a class="btn btn-primary" href="{{ route('member.borrowings') }}">
                    View My Borrowing History
                </a>
            </div>
        </div>

    @else

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="stat-number text-primary">
                            {{ $totalBooks }}
                        </div>

                        <div class="stat-label">Total Book Titles</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="stat-number text-success">
                            {{ $availableCopies }}
                        </div>

                        <div class="stat-label">Available Copies</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="stat-number text-info">
                            {{ $issuedCopies }}
                        </div>

                        <div class="stat-label">Currently Issued</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="stat-number text-dark">
                            {{ $activeMembers }}
                        </div>

                        <div class="stat-label">Active Members</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card stat-card h-100 border-start border-danger border-4">
                    <div class="card-body">
                        <div class="stat-number text-danger">
                            {{ $overdueIssues }}
                        </div>

                        <div class="stat-label">Overdue Borrowings</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card stat-card h-100 border-start border-warning border-4">
                    <div class="card-body">
                        <div class="stat-number text-warning">
                            ৳{{ number_format($outstandingFine, 2) }}
                        </div>

                        <div class="stat-label">Outstanding Fine Amount</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Book Copy Status</h5>
                        <canvas id="copyStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Monthly Book Issue Activity</h5>
                        <canvas id="monthlyIssueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-3">Quick Access</h4>

        <div class="row g-3 mb-5">
            <div class="col-md-6 col-xl-3">
                <div class="card module-card h-100 shadow-sm">
                    <a href="{{ route('books.index') }}">
                        <div class="card-body">
                            <h5>📚 Books</h5>
                            <p class="text-muted mb-0 small">Manage book catalog.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card module-card h-100 shadow-sm">
                    <a href="{{ route('members.index') }}">
                        <div class="card-body">
                            <h5>👥 Members</h5>
                            <p class="text-muted mb-0 small">Manage library members.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card module-card h-100 shadow-sm">
                    <a href="{{ route('book-issues.index') }}">
                        <div class="card-body">
                            <h5>🔄 Issue & Return</h5>
                            <p class="text-muted mb-0 small">Manage circulation records.</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card module-card h-100 shadow-sm">
                    <a href="{{ route('reports.circulation') }}">
                        <div class="card-body">
                            <h5>📊 Reports</h5>
                            <p class="text-muted mb-0 small">Reports and CSV export.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

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
@endsection

@push('scripts')
    @if (auth()->user()->role !== 'member')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            new Chart(document.getElementById('copyStatusChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($copyStatusLabels),
                    datasets: [{
                        data: @json($copyStatusData),
                        backgroundColor: [
                            '#198754',
                            '#0d6efd',
                            '#ffc107',
                            '#dc3545',
                            '#6c757d'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            new Chart(document.getElementById('monthlyIssueChart'), {
                type: 'bar',
                data: {
                    labels: @json($monthlyIssueLabels),
                    datasets: [{
                        label: 'Books Issued',
                        data: @json($monthlyIssueCounts),
                        backgroundColor: '#1d4ed8',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        </script>
    @endif
@endpush