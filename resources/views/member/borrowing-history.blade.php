@extends('layouts.app')

@section('title', 'My Borrowings | Library Management System')
@section('page-title', 'My Borrowing History')
@section('page-subtitle', 'View your borrowed books, due dates, returns, and fines')

@section('content')
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
                            <td>
                                <strong>{{ $issue->copy->book->title }}</strong>
                            </td>

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
                                    <strong>
                                        ৳{{ number_format($issue->fine->amount, 2) }}
                                    </strong>
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
                            <td colspan="7" class="py-5 text-center text-muted">
                                You have no borrowing record yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection