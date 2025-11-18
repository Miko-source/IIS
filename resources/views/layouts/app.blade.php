<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Aplikace')</title>
</head>
<body>

<nav>
    <a href="/dashboard">Dashboard</a>
    <a href="/profile">Můj profil</a>

    @if(auth()->user()->role === 'admin')
        <a href="/admin/users">Uživatelé</a>
    @endif
</nav>

<hr>

@yield('content')

</body>
</html>
