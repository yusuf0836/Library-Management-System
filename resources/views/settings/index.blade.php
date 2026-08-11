<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings | Library Management System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background:#1e3a8a;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                Library Management System
            </a>

            <a class="btn btn-outline-light btn-sm" href="{{ route('dashboard') }}">
                Dashboard
            </a>
        </div>
    </nav>

    <main class="container py-4">
        <div class="mb-4">
            <h2 class="mb-1">System Settings</h2>
            <p class="text-muted mb-0">
                Configure general library information, borrowing period, and fine rate.
            </p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h5 class="mb-3">Library Information</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="library_name" class="form-label">
                                Library Name <span class="text-danger">*</span>
                            </label>

                            <input
                                id="library_name"
                                type="text"
                                name="library_name"
                                class="form-control @error('library_name') is-invalid @enderror"
                                value="{{ old('library_name', $settings['library_name']) }}"
                                required
                            >

                            @error('library_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="library_email" class="form-label">
                                Library Email
                            </label>

                            <input
                                id="library_email"
                                type="email"
                                name="library_email"
                                class="form-control @error('library_email') is-invalid @enderror"
                                value="{{ old('library_email', $settings['library_email']) }}"
                            >

                            @error('library_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="library_phone" class="form-label">
                                Library Phone
                            </label>

                            <input
                                id="library_phone"
                                type="text"
                                name="library_phone"
                                class="form-control @error('library_phone') is-invalid @enderror"
                                value="{{ old('library_phone', $settings['library_phone']) }}"
                            >

                            @error('library_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="library_address" class="form-label">
                                Library Address
                            </label>

                            <input
                                id="library_address"
                                type="text"
                                name="library_address"
                                class="form-control @error('library_address') is-invalid @enderror"
                                value="{{ old('library_address', $settings['library_address']) }}"
                            >

                            @error('library_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Borrowing & Fine Rules</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="default_borrowing_days" class="form-label">
                                Default Borrowing Days <span class="text-danger">*</span>
                            </label>

                            <input
                                id="default_borrowing_days"
                                type="number"
                                name="default_borrowing_days"
                                class="form-control @error('default_borrowing_days') is-invalid @enderror"
                                value="{{ old('default_borrowing_days', $settings['default_borrowing_days']) }}"
                                min="1"
                                required
                            >

                            <small class="text-muted">
                                Example: 14 means a book is due after 14 days.
                            </small>

                            @error('default_borrowing_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="fine_per_day" class="form-label">
                                Fine Per Overdue Day (৳) <span class="text-danger">*</span>
                            </label>

                            <input
                                id="fine_per_day"
                                type="number"
                                name="fine_per_day"
                                class="form-control @error('fine_per_day') is-invalid @enderror"
                                value="{{ old('fine_per_day', $settings['fine_per_day']) }}"
                                min="0"
                                step="0.01"
                                required
                            >

                            @error('fine_per_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>