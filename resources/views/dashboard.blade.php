<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Vítej v systému, {{ auth()->user()->name }} {{ auth()->user()->surname }}!</h1>

    <p>Tvá role: 
        <strong>
            {{ auth()->user()->role instanceof \App\Enums\UserRole 
                ? auth()->user()->role->value 
                : auth()->user()->role }}
        </strong>
    </p>

    {{-- Jen pro admina zobrazíme odkaz na správu uživatelů --}}
    @php
        $role = auth()->user()->role instanceof \App\Enums\UserRole 
            ? auth()->user()->role->value 
            : auth()->user()->role;
    @endphp

    @if ($role === 'admin')
        <p>
            <a href="{{ route('admin.users.index') }}">Správa uživatelů</a>
        </p>
    @endif

    <p>
    <a href="{{ route('profile.edit') }}">Můj profil</a>
    </p>



    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Odhlásit se</button>
    </form>
</body>
</html>
