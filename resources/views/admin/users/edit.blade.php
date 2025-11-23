@extends('layouts.app')

@section('title', 'Upravit uživatele')

@section('content')
<h1>Upravit uživatele: {{ $user->name }}</h1>

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
    @csrf
    @method('PUT')

    <label>Jméno</label>
    <input type="text" name="name" value="{{ $user->name }}" required>

    <br>

    <label>Příjmení</label>
    <input type="text" name="surname" value="{{ $user->surname }}">

    <br>

    <label>Email</label>
    <input type="email" name="email" value="{{ $user->email }}" required>

    <br>

    <label>Aktivace účtu</label>
    <select name="role" required>
        <option value="{{ $user->role->value }}" selected hidden>
            @if ($user->role->value === 'deactivated')
                Neaktivní
            @else
                Aktivní
            @endif
        </option>
        <option value="worker">Aktivovat</option>
        <option value="deactivated">Deaktivovat</option>
    </select>

    <br><br>

    <button type="submit">Uložit změny</button>
</form>

@endsection
