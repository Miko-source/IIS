@extends('layouts.app')

@section('content')
    <h1>Správa uživatelů</h1>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>ID</th>
                <th>Jméno</th>
                <th>Příjmení</th>
                <th>E-mail</th>
                <th>Role</th>
                <th>Akce</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->surname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role?->value}}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}">Upravit</a>

                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Opravdu smazat/deaktivovat?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Smazat/Deaktivovat</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
@endsection
