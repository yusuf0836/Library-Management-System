<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Library Management System</title>

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
            padding: 17px 8%;
            background: #1e3a8a;
            color: white;
        }

        .navbar h2 {
            margin: 0;
            font-size: 20px;
        }

        .logout-button {
            padding: 8px 13px;
            border: none;
            border-radius: 6px;
            background: white;
            color: #1e3a8a;
            font-weight: bold;
            cursor: pointer;
        }

        .container {
            max-width: 1100px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .welcome-card {
            padding: 30px;
            border-radius: 14px;
            background: white;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.08);
        }

        .welcome-card h1 {
            margin-top: 0;
            color: #1e3a8a;
        }

        .role {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 14px;
            font-weight: bold;
        }

        .notice {
            margin-top: 22px;
            color: #64748b;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <h2>Library Management System</h2>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </header>

    <main class="container">
        <section class="welcome-card">
            <h1>Welcome, {{ auth()->user()->name }}</h1>

            <p>
                Your role:
                <span class="role">{{ ucfirst(auth()->user()->role) }}</span>
            </p>

            <p class="notice">
                Authentication and role setup is complete. Book, member,
                issue-return, and fine management modules will be added in the next steps.
            </p>
        </section>
    </main>
</body>
</html>