<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Library Management System')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #1e3a8a;
            --primary-dark: #172554;
            --page-bg: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: #0f172a;
            font-family: Arial, sans-serif;
        }

        .app-sidebar {
            position: fixed;
            z-index: 1050;
            top: 0;
            bottom: 0;
            left: 0;
            width: var(--sidebar-width);
            overflow-y: auto;
            background: linear-gradient(
                180deg,
                var(--primary-dark) 0%,
                var(--primary-color) 100%
            );
            color: white;
            transition: transform 0.3s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 23px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
        }

        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 37px;
            height: 37px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
            font-size: 20px;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 11px;
            margin: 18px 15px;
            padding: 12px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.10);
        }

        .sidebar-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 39px;
            height: 39px;
            border-radius: 50%;
            overflow: hidden;
            background: #dbeafe;
            color: var(--primary-color);
            font-weight: bold;
        }

        .sidebar-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-user-name {
            overflow: hidden;
            font-size: 13px;
            font-weight: bold;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-user-role {
            margin-top: 2px;
            color: #bfdbfe;
            font-size: 12px;
        }

        .menu-title {
            padding: 9px 21px;
            color: #bfdbfe;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sidebar-menu {
            margin: 0;
            padding: 0 12px;
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 4px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 12px;
            border-radius: 8px;
            color: #dbeafe;
            font-size: 14px;
            text-decoration: none;
            transition: 0.2s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.16);
            color: white;
        }

        .menu-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }

        .app-content {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 72px;
            padding: 0 32px;
            border-bottom: 1px solid #e2e8f0;
            background: white;
        }

        .topbar-title {
            margin: 0;
            font-size: 21px;
            font-weight: bold;
        }

        .topbar-subtitle {
            margin-top: 2px;
            color: #64748b;
            font-size: 13px;
        }

        .menu-toggle {
            display: none;
            border: none;
            background: transparent;
            color: var(--primary-color);
            font-size: 24px;
        }

        .main-content {
            padding: 30px;
        }

        .logout-button {
            width: 100%;
            padding: 11px 12px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.10);
            color: #dbeafe;
            text-align: left;
            font-size: 14px;
        }

        .logout-button:hover {
            background: rgba(255, 255, 255, 0.16);
            color: white;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 991px) {
            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.show {
                transform: translateX(0);
            }

            .app-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .topbar {
                padding: 0 18px;
            }

            .main-content {
                padding: 20px 15px;
            }

            .sidebar-overlay.show {
                position: fixed;
                z-index: 1040;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                display: block;
                background: rgba(15, 23, 42, 0.55);
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <aside class="app-sidebar" id="appSidebar">
        <a class="brand" href="{{ route('dashboard') }}">
            <span class="brand-icon">📚</span>
            <span>Library MS</span>
        </a>

        <div class="sidebar-user">
            <div class="sidebar-avatar">
                @if (auth()->user()->photo)
                    <img
                        src="{{ asset('storage/' . auth()->user()->photo) }}"
                        alt="Profile Photo"
                    >
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>

            <div style="min-width:0;">
                <div class="sidebar-user-name">
                    {{ auth()->user()->name }}
                </div>

                <div class="sidebar-user-role">
                    {{ ucfirst(auth()->user()->role) }}
                </div>
            </div>
        </div>

        <div class="menu-title">Main Menu</div>

        <ul class="sidebar-menu">
            <li>
                <a
                    href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                >
                    <span class="menu-icon">⌂</span>
                    Dashboard
                </a>
            </li>

            @if (auth()->user()->role === 'member')
                <li>
                    <a
                        href="{{ route('member.borrowings') }}"
                        class="{{ request()->routeIs('member.borrowings') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">📖</span>
                        My Borrowings
                    </a>
                </li>
            @else
                <li>
                    <a
                        href="{{ route('books.index') }}"
                        class="{{ request()->routeIs('books.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">📚</span>
                        Books
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('book-copies.index') }}"
                        class="{{ request()->routeIs('book-copies.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">📖</span>
                        Book Copies
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('members.index') }}"
                        class="{{ request()->routeIs('members.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">👥</span>
                        Members
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('book-issues.index') }}"
                        class="{{ request()->routeIs('book-issues.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">🔄</span>
                        Issue & Return
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('fines.index') }}"
                        class="{{ request()->routeIs('fines.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">💰</span>
                        Fine Management
                    </a>
                </li>
            @endif
        </ul>

        @if (auth()->user()->role !== 'member')
            <div class="menu-title">Book Catalog</div>

            <ul class="sidebar-menu">
                <li>
                    <a
                        href="{{ route('categories.index') }}"
                        class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">🗂️</span>
                        Categories
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('authors.index') }}"
                        class="{{ request()->routeIs('authors.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">✍️</span>
                        Authors
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('publishers.index') }}"
                        class="{{ request()->routeIs('publishers.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">🏢</span>
                        Publishers
                    </a>
                </li>
            </ul>

            <div class="menu-title">Reports</div>

            <ul class="sidebar-menu">
                <li>
                    <a
                        href="{{ route('reports.circulation') }}"
                        class="{{ request()->routeIs('reports.circulation') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">📊</span>
                        Circulation Report
                    </a>
                </li>

                <li>
                    <a
                        href="{{ route('reports.overdue') }}"
                        class="{{ request()->routeIs('reports.overdue') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">⚠️</span>
                        Overdue Report
                    </a>
                </li>
            </ul>
        @endif

        <div class="menu-title">Account</div>

        <ul class="sidebar-menu">
            <li>
                <a
                    href="{{ route('profile.edit') }}"
                    class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"
                >
                    <span class="menu-icon">👤</span>
                    My Profile
                </a>
            </li>

            @if (auth()->user()->role === 'admin')
                <li>
                    <a
                        href="{{ route('settings.index') }}"
                        class="{{ request()->routeIs('settings.*') ? 'active' : '' }}"
                    >
                        <span class="menu-icon">⚙️</span>
                        Settings
                    </a>
                </li>
            @endif

            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button class="logout-button" type="submit">
                        <span class="menu-icon">↪</span>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="app-content">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle" id="menuToggle" type="button">
                    ☰
                </button>

                <div>
                    <h1 class="topbar-title">
                        @yield('page-title', 'Library Management System')
                    </h1>

                    <div class="topbar-subtitle">
                        @yield('page-subtitle', 'Library operations made simple')
                    </div>
                </div>
            </div>

            <div class="d-none d-md-block text-muted small">
                {{ now()->format('d M, Y') }}
            </div>
        </header>

        <main class="main-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const appSidebar = document.getElementById('appSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            appSidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        }

        menuToggle?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', toggleSidebar);
    </script>

    @stack('scripts')
</body>
</html>