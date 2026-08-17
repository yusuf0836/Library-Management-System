@extends('layouts.app')

@section('title', 'Overdue Report | Library Management System')
@section('page-title', 'Overdue Book Report')
@section('page-subtitle', 'Books that have passed the due date and are not yet returned')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-danger">Overdue Books</h4>
            <p class="text-muted mb-0">
                Monitor books that members have not returned by the due date.
            </p>
        </div>

        <a class="btn btn-outline-primary" href="{{ route('reports.circulation') }}">
            View Circulation Report
        </a>
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
                                <strong>
                                    ৳{{ number_format($estimatedFine, 2) }}
                                </strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-5 text-center text-success">
                                No overdue book found. Great!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection