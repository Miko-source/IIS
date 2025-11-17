<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Vítej v systému, {{ auth()->user()->name }} {{ auth()->user()->surname }}!</h1>

    <p>Tvá role: <strong>{{ auth()->user()->role }}</strong></p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Odhlásit se</button>
    </form>
</body>
</html>
