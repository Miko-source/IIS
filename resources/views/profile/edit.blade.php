@extends('layouts.app')

@section('content')
<div class="container col-md-6">

    <h1 class="mb-4">Můj profil</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Jméno</label>
            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name', $user->name) }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Příjmení</label>
            <input
                type="text"
                name="surname"
                class="form-control"
                value="{{ old('surname', $user->surname) }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email', $user->email) }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Nové heslo (nepovinné)</label>
            <input
                type="password"
                name="password"
                class="form-control"
                value="{{ session('profile_raw_password') }}"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Potvrzení nového hesla</label>
            <input
                type="password"
                name="password_confirmation"
                class="form-control"
                value="{{ session('profile_raw_password_confirmation') }}"
            >
        </div>

        <button type="submit" class="btn btn-primary w-100">Uložit změny</button>
    </form>
</div>
@endsection
