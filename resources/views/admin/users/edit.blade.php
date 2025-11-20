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

    <label>Role</label>
    <select name="role" required>
        <option value="{{ $user->role->value }}" selected hidden>
            {{ ucfirst($user->role->value) }}
        </option>

        <option value="campaign_manager">Manager</option>
        <option value="coordinator">Coordinator</option>
        <option value="worker">Worker</option>
    </select>

    <br><br>

    <button type="submit">Uložit změny</button>
</form>

@endsection
