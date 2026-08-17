@extends('layouts.app')

@section('title', 'Publishers | Library Management System')
@section('page-title', 'Publishers')
@section('page-subtitle', 'Manage publisher contact information and records')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Publishers</h4>
            <p class="text-muted mb-0">
                Add, update, and manage book publishers.
            </p>
        </div>

        <a class="btn btn-primary" href="{{ route('publishers.create') }}">
            + Add Publisher
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Publisher Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($publishers as $publisher)
                        <tr>
                            <td>{{ $publishers->firstItem() + $loop->index }}</td>

                            <td>
                                <strong>{{ $publisher->name }}</strong>
                            </td>

                            <td>{{ $publisher->email ?: 'N/A' }}</td>
                            <td>{{ $publisher->phone ?: 'N/A' }}</td>
                            <td>{{ $publisher->address ?: 'N/A' }}</td>

                            <td class="text-end text-nowrap">
                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('publishers.edit', $publisher) }}"
                                >
                                    Edit
                                </a>

                                <form
                                    class="d-inline"
                                    action="{{ route('publishers.destroy', $publisher) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this publisher?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        type="submit"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted">
                                No publisher has been added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($publishers->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            @if ($publishers->onFirstPage())
                <span class="btn btn-outline-secondary disabled">
                    Previous
                </span>
            @else
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $publishers->previousPageUrl() }}"
                >
                    Previous
                </a>
            @endif

            <span class="text-muted">
                Page {{ $publishers->currentPage() }} of {{ $publishers->lastPage() }}
            </span>

            @if ($publishers->hasMorePages())
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $publishers->nextPageUrl() }}"
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