<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IIS systém</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">IIS</a>

            <div>
                @auth
                    <span class="text-white me-3">
                        {{ auth()->user()->name }} ({{ auth()->user()->role }})
                    </span>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-light">Odhlásit</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Přihlásit</a>
                @endauth
            </div>
        </div>
    </nav>

<div class="container mb-4">

    @auth
        <div class="mb-3">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                ← Zpět na dashboard
            </a>
        </div>
    @endauth

    @yield('content')
</div>


</body>
</html>
