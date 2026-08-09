<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Book | Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background:#1e3a8a;">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Library Management System</span>
        </div>
    </nav>

    <main class="container py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="mb-1">Issue Book</h2>
                <p class="text-muted mb-4">
                    Select an active member and an available book copy.
                </p>

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($members->isEmpty())
                    <div class="alert alert-warning">
                        No active member is available. Add an active member first.
                    </div>
                @endif

                @if ($copies->isEmpty())
                    <div class="alert alert-warning">
                        No available book copy is found. Add a book copy or return an issued copy.
                    </div>
                @endif

                <form action="{{ route('book-issues.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="member_id" class="form-label">
                                Member <span class="text-danger">*</span>
                            </label>

                            <select
                                id="member_id"
                                name="member_id"
                                class="form-select @error('member_id') is-invalid @enderror"
                                required
                            >
                                <option value="">Select Member</option>

                                @foreach ($members as $member)
                                    <option
                                        value="{{ $member->id }}"
                                        @selected(old('member_id') == $member->id)
                                    >
                                        {{ $member->member_code }} — {{ $member->user->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('member_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="book_copy_id" class="form-label">
                                Available Book Copy <span class="text-danger">*</span>
                            </label>

                            <select
                                id="book_copy_id"
                                name="book_copy_id"
                                class="form-select @error('book_copy_id') is-invalid @enderror"
                                required
                            >
                                <option value="">Select Book Copy</option>

                                @foreach ($copies as $copy)
                                    <option
                                        value="{{ $copy->id }}"
                                        @selected(old('book_copy_id') == $copy->id)
                                    >
                                        {{ $copy->accession_number }} — {{ $copy->book->title }}
                                    </option>
                                @endforeach
                            </select>

                            @error('book_copy_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="issued_at" class="form-label">
                                Issue Date <span class="text-danger">*</span>
                            </label>

                            <input
                                id="issued_at"
                                type="date"
                                name="issued_at"
                                class="form-control @error('issued_at') is-invalid @enderror"
                                value="{{ old('issued_at', date('Y-m-d')) }}"
                                required
                            >

                            @error('issued_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="due_at" class="form-label">
                                Due Date <span class="text-danger">*</span>
                            </label>

                            <input
                                id="due_at"
                                type="date"
                                name="due_at"
                                class="form-control @error('due_at') is-invalid @enderror"
                                value="{{ old('due_at', date('Y-m-d', strtotime('+14 days'))) }}"
                                required
                            >

                            @error('due_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>

                            <textarea
                                id="notes"
                                name="notes"
                                class="form-control"
                                rows="3"
                                placeholder="Optional issue notes..."
                            >{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button
                            class="btn btn-primary"
                            type="submit"
                            @disabled($members->isEmpty() || $copies->isEmpty())
                        >
                            Issue Book
                        </button>

                        <a class="btn btn-secondary" href="{{ route('book-issues.index') }}">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>