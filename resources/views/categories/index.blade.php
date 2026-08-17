@extends('layouts.app')

@section('title', 'Categories | Library Management System')
@section('page-title', 'Book Categories')
@section('page-subtitle', 'Manage categories for organizing library books')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Categories</h4>
            <p class="text-muted mb-0">
                Add, update, and organize book categories.
            </p>
        </div>

        <a class="btn btn-primary" href="{{ route('categories.create') }}">
            + Add Category
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Created Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $loop->index }}</td>

                            <td>
                                <strong>{{ $category->name }}</strong>
                            </td>

                            <td>
                                {{ $category->description ?: 'No description added.' }}
                            </td>

                            <td>
                                {{ $category->created_at->format('d M, Y') }}
                            </td>

                            <td class="text-end text-nowrap">
                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('categories.edit', $category) }}"
                                >
                                    Edit
                                </a>

                                <form
                                    class="d-inline"
                                    action="{{ route('categories.destroy', $category) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this category?');"
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
                                No category has been added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($categories->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            @if ($categories->onFirstPage())
                <span class="btn btn-outline-secondary disabled">
                    Previous
                </span>
            @else
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $categories->previousPageUrl() }}"
                >
                    Previous
                </a>
            @endif

            <span class="text-muted">
                Page {{ $categories->currentPage() }} of {{ $categories->lastPage() }}
            </span>

            @if ($categories->hasMorePages())
                <a
                    class="btn btn-outline-secondary"
                    href="{{ $categories->nextPageUrl() }}"
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