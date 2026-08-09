<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Member | Library Management System</title>
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
                <h2 class="mb-1">Add Library Member</h2>
                <p class="text-muted mb-4">Create a member profile and login account.</p>

                <form action="{{ route('members.store') }}" method="POST">
                    @csrf

                    @include('members.form', ['member' => null])

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit">Save Member</button>
                        <a class="btn btn-secondary" href="{{ route('members.index') }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>