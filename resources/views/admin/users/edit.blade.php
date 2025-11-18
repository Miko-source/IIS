@extends('layouts.app')

@section('title', 'Upravit uživatele')

@section('content')
<h1>Upravit uživatele: {{ $user->name }}</h1>

<form method="POST" action="/admin/users/{{ $user->id }}/edit">
    @csrf

    <label>Jméno</label>
    <input type="text" name="name" value="{{ $user->name }}" required>

    <br>

    <label>Příjmení</label>
    <input type="text" name="surname" value="{{ $user->surname }}">

    <br>

    <label>Email</label>
    <input type="email" name="email" value="{{ $user->email }}" required>

    <br>

    <label>Role</label>
    <select name="role">
        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
        <option value="coordinator" {{ $user->role == 'coordinator' ? 'selected' : '' }}>Coordinator</option>
        <option value="worker" {{ $user->role == 'worker' ? 'selected' : '' }}>Worker</option>
    </select>

    <br><br>

    <button type="submit">Uložit změny</button>
</form>

@endsection
