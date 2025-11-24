@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Zpětný odkaz --}}
    <div class="mb-3">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            ← Zpět na správu uživatelů
        </a>
    </div>

    <h1 class="mb-4">Přidat nového uživatele</h1>

    {{-- Chybové hlášky --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        {{-- Jméno --}}
        <div class="mb-3">
            <label class="form-label">Jméno</label>
            <input type="text"
                    placeholder="Elvis"

                   name="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   required>
        </div>

        {{-- Příjmení --}}
        <div class="mb-3">
            <label class="form-label">Příjmení</label>
            <input type="text"
                     placeholder="Presley"
                   name="surname"
                   class="form-control"
                   value="{{ old('surname') }}"
                   required>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email"
                    placeholder="elvis.presley@example.com"
                   name="email"
                   class="form-control"
                   value="{{ old('email') }}"
                   required>
        </div>

        {{-- Heslo --}}
        <div class="mb-3">
            <label class="form-label">Heslo</label>
            <input type="password"
                   placeholder="min. 6 znaků"
                   name="password"
                   class="form-control"
                   required>
        </div>

        {{-- Confirm hesla --}}
        <div class="mb-3">
            <label class="form-label">Potvrzení hesla</label>
            <input type="password"
                     placeholder="potvrďte heslo"
                   name="password_confirmation"
                   class="form-control"
                   required>
        </div>

        {{-- Role --}}
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="worker">worker</option>
            </select>

        </div>

        {{-- Tlačítko --}}
        <button type="submit" class="btn btn-primary">
            Vytvořit uživatele
        </button>

    </form>
</div>
@endsection
