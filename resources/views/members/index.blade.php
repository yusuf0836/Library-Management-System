@extends('layouts.app')

@section('title', 'Members | Library Management System')
@section('page-title', 'Library Members')
@section('page-subtitle', 'Manage member accounts, profiles, and membership status')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Members</h4>
            <p class="text-muted mb-0">
                Add, update, search, and manage library members.
            </p>
        </div>

        <a class="btn btn-primary" href="{{ route('members.create') }}">
            + Add Member
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('members.index') }}" class="row g-2">
                <div class="col-md-10">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ $search }}"
                        placeholder="Search member name, email, member code, or phone..."
                    >
                </div>

                <div class="col-md-1 d-grid">
                    <button class="btn btn-dark" type="submit">
                        Search
                    </button>
                </div>

                <div class="col-md-1 d-grid">
                    <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">
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
                        <th>#</th>
                        <th>Member</th>
                        <th>Member Code</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>{{ $members->firstItem() + $loop->index }}</td>

                            <td>
                                <strong>{{ $member->user->name }}</strong>
                                <br>

                                <small class="text-muted">
                                    {{ $member->user->email }}
                                </small>
                            </td>

                            <td>{{ $member->member_code }}</td>
                            <td>{{ $member->department ?: 'N/A' }}</td>
                            <td>{{ $member->phone ?: 'N/A' }}</td>
                            <td>{{ $member->joined_at->format('d M, Y') }}</td>

                            <td>
                                @if ($member->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>

                            <td class="text-end text-nowrap">
                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('members.edit', $member) }}"
                                >
                                    Edit
                                </a>

                                <form
                                    class="d-inline"
                                    action="{{ route('members.destroy', $member) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this member?');"
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
                            <td colspan="8" class="py-5 text-center text-muted">
                                No member found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($members->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            @if ($members->onFirstPage())
                <span class="btn btn-outline-secondary disabled">
                    Previous
                </span>
            @else
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $members->previousPageUrl() }}"
                >
                    Previous
                </a>
            @endif

            <span class="text-muted">
                Page {{ $members->currentPage() }} of {{ $members->lastPage() }}
            </span>

            @if ($members->hasMorePages())
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $members->nextPageUrl() }}"
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