@extends('layouts.app')

@section('title', 'Issue & Return | Library Management System')
@section('page-title', 'Book Issue & Return')
@section('page-subtitle', 'Manage borrowed books, returns, due dates, and overdue records')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Circulation Records</h4>
            <p class="text-muted mb-0">
                Issue books to members and process returned books.
            </p>
        </div>

        <a class="btn btn-primary" href="{{ route('book-issues.create') }}">
            + Issue Book
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('book-issues.index') }}" class="row g-2">
                <div class="col-md-7">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ $search }}"
                        placeholder="Search member, member code, book, or accession number..."
                    >
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Issue Status</option>

                        <option value="issued" @selected($status === 'issued')>
                            Currently Issued
                        </option>

                        <option value="returned" @selected($status === 'returned')>
                            Returned
                        </option>

                        <option value="overdue" @selected($status === 'overdue')>
                            Overdue
                        </option>
                    </select>
                </div>

                <div class="col-md-1 d-grid">
                    <button class="btn btn-dark" type="submit">
                        Filter
                    </button>
                </div>

                <div class="col-md-1 d-grid">
                    <a
                        class="btn btn-outline-secondary"
                        href="{{ route('book-issues.index') }}"
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
                        <th>Book Copy</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                        <th class="text-end">Action</th>
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

                            <td class="text-end">
                                @if ($issue->status !== 'returned')
                                    <button
                                        class="btn btn-sm btn-outline-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#returnModal{{ $issue->id }}"
                                    >
                                        Return Book
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
                                                        <h5 class="modal-title">
                                                            Return Book
                                                        </h5>

                                                        <button
                                                            type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"
                                                        ></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <p class="mb-1">
                                                            <strong>
                                                                {{ $issue->copy->book->title }}
                                                            </strong>
                                                        </p>

                                                        <p class="text-muted small">
                                                            Accession Number:
                                                            {{ $issue->copy->accession_number }}
                                                        </p>

                                                        <label
                                                            for="returned_at{{ $issue->id }}"
                                                            class="form-label"
                                                        >
                                                            Return Date
                                                        </label>

                                                        <input
                                                            id="returned_at{{ $issue->id }}"
                                                            type="date"
                                                            name="returned_at"
                                                            class="form-control"
                                                            value="{{ date('Y-m-d') }}"
                                                            min="{{ $issue->issued_at->format('Y-m-d') }}"
                                                            required
                                                        >

                                                        <label
                                                            for="notes{{ $issue->id }}"
                                                            class="form-label mt-3"
                                                        >
                                                            Return Notes
                                                        </label>

                                                        <textarea
                                                            id="notes{{ $issue->id }}"
                                                            name="notes"
                                                            class="form-control"
                                                            rows="3"
                                                            placeholder="Optional notes..."
                                                        ></textarea>

                                                        <div class="alert alert-info mt-3 mb-0">
                                                            Fine rate:
                                                            <strong>
                                                                ৳{{ number_format($finePerDay, 2) }}
                                                            </strong>
                                                            per overdue day.
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            Cancel
                                                        </button>

                                                        <button
                                                            class="btn btn-success"
                                                            type="submit"
                                                        >
                                                            Confirm Return
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">
                                        Completed
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-5 text-center text-muted">
                                No book issue record found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection