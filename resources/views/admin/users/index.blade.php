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
                        @include('components.edit-button', [
                            'href' => route('admin.users.edit', $user),
                            'label' => 'Upravit',
                            'small' => true
                        ])

                        @include('components.delete-button', [
                            'action' => route('admin.users.destroy', $user),
                            'label' => 'Smazat/Deaktivovat',
                            'confirm' => 'Opravdu smazat/deaktivovat?',
                            'small' => true
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
@endsection
