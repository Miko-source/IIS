@extends('layouts.app') 

@section('content')
    <h1>Můj profil</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color: red">{{ $error }}</p>
        @endforeach
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <label>Jméno</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>

        <label>Příjmení</label>
        <input type="text" name="surname" value="{{ old('surname', $user->surname) }}" required>

        <label>E-mail</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>

        <label>Nové heslo (nepovinné)</label>
        <input type="password" name="password">

        <label>Potvrzení nového hesla</label>
        <input type="password" name="password_confirmation">

        <button type="submit">Uložit změny</button>
    </form>
@endsection
