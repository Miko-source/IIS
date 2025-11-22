<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'DisinfoCamp Manager')</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        body {
            background: #f3f4f6;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .app-navbar {
            background: #111827;
            color: #fff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        }

        .app-navbar .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .app-navbar .nav-link {
            color: #e5e7eb;
        }

        .app-navbar .nav-link.active {
            color: #ffffff;
            font-weight: 600;
        }

        .app-main {
            min-height: calc(100vh - 64px);
            padding-top: 24px;
            padding-bottom: 32px;
        }

        .app-footer {
            font-size: 12px;
            color: #6b7280;
            padding: 8px 0 16px;
            text-align: center;
        }

        /* Buttony  */
        .btn-app {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            padding: 0.35rem 0.9rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid transparent;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-app-sm {
            padding: 0.25rem 0.7rem;
            font-size: 0.8rem;
        }

        .btn-app-primary {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
        }
        .btn-app-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            color: #ffffff;
        }

        .btn-app-secondary {
            background-color: #e5e7eb;
            border-color: #d1d5db;
            color: #111827;
        }
        .btn-app-secondary:hover {
            background-color: #d1d5db;
            border-color: #9ca3af;
            color: #111827;
        }

        .btn-app-edit {
            background-color: #facc15;
            border-color: #facc15;
            color: #1f2937;
        }
        .btn-app-edit:hover {
            background-color: #eab308;
            border-color: #eab308;
            color: #111827;
        }

        .btn-app-danger {
            background-color: #ef4444;
            border-color: #ef4444;
            color: #ffffff;
        }
        .btn-app-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            color: #ffffff;
        }

        .btn-topic {
            display: block;
            width: 100%;
            padding: 12px 18px;
            border-radius: 0.5rem;
            border: 1px solid #2563eb;
            background-color: #f9fafb;
            color: #2563eb;
            font-weight: 600;
            font-size: 16px;
            text-align: left;
            text-decoration: none;
        }
        .btn-topic:hover {
            background-color: #2563eb;
            color: #ffffff;
        }

        /* Flatpickr - lepší zobrazení */
        .flatpickr-months .flatpickr-month {
            height: 40px;
        }

        .flatpickr-current-month {
            font-size: 16px;
            padding: 10px 0;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month .numInputWrapper {
            cursor: pointer;
            font-size: 16px;
        }

        .flatpickr-current-month input.cur-year {
            font-weight: 700;
            cursor: pointer;
            padding: 0 5px;
        }

        .flatpickr-current-month input.cur-year:hover {
            background: rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="{{ route('dashboard') }}">
            DisinfoCamp Manager
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            @auth
                @php
                    $role = auth()->user()->role instanceof \App\Enums\UserRole
                        ? auth()->user()->role->value
                        : auth()->user()->role;
                @endphp

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    @if($role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.topics.*') ? 'active' : '' }}"
                               href="{{ route('admin.topics.index') }}">
                                Správa témat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                               href="{{ route('admin.users.index') }}">
                                Uživatelé
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('topics.*') ? 'active' : '' }}"
                               href="{{ route('topics.index') }}">
                                Témata
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-sm text-light">
                        {{ auth()->user()->name }} ({{ $role }})
                    </span>

                    <a href="{{ route('profile.edit') }}"
                       class="btn btn-sm btn-outline-light">
                        Můj profil
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-light text-dark">
                            Odhlásit
                        </button>
                    </form>
                </div>
            @else
                <ul class="navbar-nav me-auto"></ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                        Přihlásit
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-light text-dark">
                        Registrovat
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<main class="app-main">
    <div class="container">
        @include('components.flash-message')

        @yield('content')
    </div>
</main>

<footer class="app-footer">
    &copy; 2025 DisinfoCamp Manager – tým IIS
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Flash messages hide automatically after 2 seconds
    setTimeout(() => {
        document.querySelectorAll('.auto-hide').forEach(el => {
            el.classList.remove('show');  // Bootstrap fade-out
            setTimeout(() => el.remove(), 500); // remove DOM element after fade
        });
    }, 2000);
</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/cs.js"></script>
<script>

    if (typeof flatpickr !== 'undefined' && flatpickr.l10ns && flatpickr.l10ns.cs) {
        flatpickr.localize(flatpickr.l10ns.cs);
    }
</script>
</body>
</html>