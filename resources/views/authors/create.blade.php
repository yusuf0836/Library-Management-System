<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Author | Library Management System</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f8fafc;
            color: #0f172a;
            font-family: Arial, sans-serif;
        }

        .navbar {
            padding: 16px 8%;
            background: #1e3a8a;
            color: white;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
        }

        h1 {
            margin-top: 0;
            color: #1e3a8a;
        }

        label {
            display: block;
            margin: 18px 0 7px;
            font-size: 14px;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        input:focus, textarea:focus {
            border-color: #2563eb;
            outline: none;
        }

        .error {
            margin-top: 6px;
            color: #dc2626;
            font-size: 13px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 24px;
        }

        .button {
            padding: 11px 16px;
            border: none;
            border-radius: 7px;
            background: #1d4ed8;
            color: white;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .cancel {
            background: #64748b;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <strong>Library Management System</strong>
    </header>

    <main class="container">
        <section class="card">
            <h1>Add New Author</h1>

            <form action="{{ route('authors.store') }}" method="POST">
                @csrf

                <label for="name">
                    Author Name <span style="color: red">*</span>
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Example: Humayun Ahmed"
                    required
                >

                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror

                <label for="biography">Biography</label>

                <textarea
                    id="biography"
                    name="biography"
                    placeholder="Write a short biography of the author..."
                >{{ old('biography') }}</textarea>

                @error('biography')
                    <div class="error">{{ $message }}</div>
                @enderror

                <div class="buttons">
                    <button class="button" type="submit">Save Author</button>

                    <a class="button cancel" href="{{ route('authors.index') }}">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>