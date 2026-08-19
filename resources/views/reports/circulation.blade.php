@extends('layouts.app')

@section('title', 'Circulation Report | Library Management System')
@section('page-title', 'Circulation Report')
@section('page-subtitle', 'View and export book issue and return records')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Circulation Report</h4>
            <p class="text-muted mb-0">
                Generate book issue and return reports using date and status filters.
            </p>
        </div>

        <a
            class="btn btn-success"
            href="{{ route('reports.circulation.export', [
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
                'status' => request('status'),
            ]) }}"
            download
        >
            Download CSV
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.circulation') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">
                        Start Date
                    </label>

                    <input
                        id="start_date"
                        type="date"
                        name="start_date"
                        class="form-control"
                        value="{{ request('start_date') }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="end_date" class="form-label">
                        End Date
                    </label>

                    <input
                        id="end_date"
                        type="date"
                        name="end_date"
                        class="form-control"
                        value="{{ request('end_date') }}"
                    >
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label">
                        Issue Status
                    </label>

                    <select id="status" name="status" class="form-select">
                        <option value="">All Status</option>

                        <option
                            value="issued"
                            @selected(request('status') === 'issued')
                        >
                            Currently Issued
                        </option>

                        <option
                            value="returned"
                            @selected(request('status') === 'returned')
                        >
                            Returned
                        </option>

                        <option
                            value="overdue"
                            @selected(request('status') === 'overdue')
                        >
                            Overdue
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-dark flex-fill" type="submit">
                        Generate Report
                    </button>

                    <a
                        class="btn btn-outline-secondary"
                        href="{{ route('reports.circulation') }}"
                    >
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
                        <th>Member</th>
                        <th>Book</th>
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
                                @if ($issue->returned_at)
                                    <span class="badge bg-success">Returned</span>
                                @elseif (now()->startOfDay()->greaterThan($issue->due_at))
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-primary">Issued</span>
                                @endif
                            </td>

                            <td>
                                @if ($issue->fine)
                                    <strong>
                                        ৳{{ number_format($issue->fine->amount, 2) }}
                                    </strong>
                                    <br>

                                    <small
                                        class="{{ $issue->fine->status === 'paid' ? 'text-success' : 'text-danger' }}"
                                    >
                                        {{ ucfirst($issue->fine->status) }}
                                    </small>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-5 text-center text-muted">
                                No circulation record found for the selected filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($issues->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            @if ($issues->onFirstPage())
                <span class="btn btn-outline-secondary disabled">
                    Previous
                </span>
            @else
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $issues->previousPageUrl() }}"
                >
                    Previous
                </a>
            @endif

            <span class="text-muted">
                Page {{ $issues->currentPage() }} of {{ $issues->lastPage() }}
            </span>

            @if ($issues->hasMorePages())
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $issues->nextPageUrl() }}"
                >
                    Next
                </a>
            @else
                <span class="btn btn-outline-secondary disabled">
                    Next
                </span>
            @endif
        </div>
    @endif
@endsection