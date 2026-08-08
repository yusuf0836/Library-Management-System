<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories | Library Management System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8fafc;
            color: #0f172a;
            font-family: Arial, sans-serif;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 8%;
            background: #1e3a8a;
            color: white;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .container {
            max-width: 1100px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        h1 {
            margin: 0;
            color: #1e3a8a;
        }

        .button {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 7px;
            background: #1d4ed8;
            color: white;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover {
            background: #1e40af;
        }

        .card {
            overflow: hidden;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #eff6ff;
            color: #1e3a8a;
        }

        .success {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
        }

        .empty {
            padding: 35px;
            color: #64748b;
            text-align: center;
        }

        .edit-link {
            margin-right: 8px;
            color: #1d4ed8;
            font-weight: bold;
            text-decoration: none;
        }

        .delete-button {
            padding: 0;
            border: none;
            background: none;
            color: #dc2626;
            font-weight: bold;
            cursor: pointer;
        }

        .action-form {
            display: inline;
        }

        .pagination {
            display: flex;
            gap: 8px;
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: #1e3a8a;
            text-decoration: none;
            font-size: 14px;
        }

        @media (max-width: 650px) {
            .page-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 14px;
            }

            table {
                min-width: 650px;
            }

            .card {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <header class="navbar">
        <strong>Library Management System</strong>

        <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
    </header>

    <main class="container">
        <div class="page-header">
            <div>
                <h1>Book Categories</h1>
                <p>Manage all library book categories.</p>
            </div>

            <a class="button" href="{{ route('categories.create') }}">
                + Add Category
            </a>
        </div>

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <section class="card">
            @if ($categories->count())
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $categories->firstItem() + $loop->index }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description ?: '—' }}</td>
                                <td>{{ $category->created_at->format('d M, Y') }}</td>
                                <td>
                                    <a
                                        class="edit-link"
                                        href="{{ route('categories.edit', $category) }}"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        class="action-form"
                                        action="{{ route('categories.destroy', $category) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this category?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button class="delete-button" type="submit">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty">
                    No category has been added yet.
                </div>
            @endif
        </section>

        @if ($categories->hasPages())
            <div class="pagination">
                @if ($categories->onFirstPage())
                    <span>Previous</span>
                @else
                    <a href="{{ $categories->previousPageUrl() }}">Previous</a>
                @endif

                <span>Page {{ $categories->currentPage() }} of {{ $categories->lastPage() }}</span>

                @if ($categories->hasMorePages())
                    <a href="{{ $categories->nextPageUrl() }}">Next</a>
                @else
                    <span>Next</span>
                @endif
            </div>
        @endif
    </main>
</body>
</html>