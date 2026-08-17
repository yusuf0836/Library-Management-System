@extends('layouts.app')

@section('title', 'Authors | Library Management System')
@section('page-title', 'Authors')
@section('page-subtitle', 'Manage book author records and biographies')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Authors</h4>
            <p class="text-muted mb-0">
                Add, update, and manage book authors.
            </p>
        </div>

        <a class="btn btn-primary" href="{{ route('authors.create') }}">
            + Add Author
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Author Name</th>
                        <th>Biography</th>
                        <th>Created Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($authors as $author)
                        <tr>
                            <td>{{ $authors->firstItem() + $loop->index }}</td>

                            <td>
                                <strong>{{ $author->name }}</strong>
                            </td>

                            <td>
                                {{ $author->biography ?: 'No biography added.' }}
                            </td>

                            <td>
                                {{ $author->created_at->format('d M, Y') }}
                            </td>

                            <td class="text-end text-nowrap">
                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('authors.edit', $author) }}"
                                >
                                    Edit
                                </a>

                                <form
                                    class="d-inline"
                                    action="{{ route('authors.destroy', $author) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this author?');"
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
                            <td colspan="5" class="py-5 text-center text-muted">
                                No author has been added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($authors->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            @if ($authors->onFirstPage())
                <span class="btn btn-outline-secondary disabled">
                    Previous
                </span>
            @else
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $authors->previousPageUrl() }}"
                >
                    Previous
                </a>
            @endif

            <span class="text-muted">
                Page {{ $authors->currentPage() }} of {{ $authors->lastPage() }}
            </span>

            @if ($authors->hasMorePages())
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $authors->nextPageUrl() }}"
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