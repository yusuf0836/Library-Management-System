@extends('layouts.app')

@section('title', 'Fine Management | Library Management System')
@section('page-title', 'Fine Management')
@section('page-subtitle', 'View overdue fines and receive fine payments')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Fine Records</h4>
            <p class="text-muted mb-0">
                Track unpaid, partial, and paid overdue fines.
            </p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('fines.index') }}" class="row g-2">
                <div class="col-md-10">
                    <select name="status" class="form-select">
                        <option value="">All Fine Status</option>
                        <option value="unpaid" @selected($status === 'unpaid')>Unpaid</option>
                        <option value="partial" @selected($status === 'partial')>Partial</option>
                        <option value="paid" @selected($status === 'paid')>Paid</option>
                        <option value="waived" @selected($status === 'waived')>Waived</option>
                    </select>
                </div>

                <div class="col-md-1 d-grid">
                    <button class="btn btn-dark" type="submit">
                        Filter
                    </button>
                </div>

                <div class="col-md-1 d-grid">
                    <a class="btn btn-outline-secondary" href="{{ route('fines.index') }}">
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
                        <th>Total Fine</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($fines as $fine)
                        @php
                            $remaining = $fine->amount - $fine->paid_amount;
                        @endphp

                        <tr>
                            <td>
                                <strong>{{ $fine->issue->member->user->name }}</strong>
                                <br>

                                <small class="text-muted">
                                    {{ $fine->issue->member->member_code }}
                                </small>
                            </td>

                            <td>
                                <strong>{{ $fine->issue->copy->book->title }}</strong>
                                <br>

                                <small class="text-muted">
                                    {{ $fine->issue->copy->accession_number }}
                                </small>
                            </td>

                            <td>৳{{ number_format($fine->amount, 2) }}</td>

                            <td class="text-success">
                                ৳{{ number_format($fine->paid_amount, 2) }}
                            </td>

                            <td class="text-danger">
                                ৳{{ number_format($remaining, 2) }}
                            </td>

                            <td>
                                @if ($fine->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif ($fine->status === 'partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @elseif ($fine->status === 'waived')
                                    <span class="badge bg-secondary">Waived</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </td>

                            <td class="text-end">
                                @if (! in_array($fine->status, ['paid', 'waived']))
                                    <button
                                        class="btn btn-sm btn-outline-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#paymentModal{{ $fine->id }}"
                                    >
                                        Receive Payment
                                    </button>

                                    <div
                                        class="modal fade"
                                        id="paymentModal{{ $fine->id }}"
                                        tabindex="-1"
                                    >
                                        <div class="modal-dialog">
                                            <form
                                                action="{{ route('fines.pay', $fine) }}"
                                                method="POST"
                                            >
                                                @csrf

                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            Receive Fine Payment
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
                                                                {{ $fine->issue->member->user->name }}
                                                            </strong>
                                                        </p>

                                                        <p class="text-muted">
                                                            Due Amount:
                                                            <strong>
                                                                ৳{{ number_format($remaining, 2) }}
                                                            </strong>
                                                        </p>

                                                        <label
                                                            for="payment_amount{{ $fine->id }}"
                                                            class="form-label"
                                                        >
                                                            Payment Amount
                                                        </label>

                                                        <input
                                                            id="payment_amount{{ $fine->id }}"
                                                            type="number"
                                                            name="payment_amount"
                                                            class="form-control"
                                                            min="0.01"
                                                            max="{{ $remaining }}"
                                                            step="0.01"
                                                            required
                                                        >

                                                        <label
                                                            for="notes{{ $fine->id }}"
                                                            class="form-label mt-3"
                                                        >
                                                            Payment Notes
                                                        </label>

                                                        <textarea
                                                            id="notes{{ $fine->id }}"
                                                            name="notes"
                                                            class="form-control"
                                                            rows="3"
                                                            placeholder="Optional payment note..."
                                                        ></textarea>
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
                                                            Save Payment
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
                            <td colspan="7" class="py-5 text-center text-muted">
                                No fine record found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection