<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Library Management System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
        }

        .login-card {
            width: 100%;
            max-width: 410px;
            padding: 35px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.25);
        }

        h1 {
            margin: 0 0 8px;
            text-align: center;
            color: #1e3a8a;
            font-size: 27px;
        }

        .subtitle {
            margin: 0 0 28px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        label {
            display: block;
            margin: 16px 0 7px;
            color: #334155;
            font-size: 14px;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus {
            border-color: #2563eb;
            outline: none;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 17px;
            color: #475569;
            font-size: 14px;
        }

        .remember input {
            width: auto;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #1d4ed8;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1e40af;
        }

        .error {
            padding: 11px;
            margin-bottom: 15px;
            border-radius: 8px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <main class="login-card">
        <h1>Library Management</h1>
        <p class="subtitle">Sign in to access your account</p>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <label for="email">Email Address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
            >

            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                required
            >

            <label class="remember">
                <input type="checkbox" name="remember">
                Remember me
            </label>

            <button type="submit">Sign In</button>
        </form>
    </main>
</body>
</html>