@extends('layouts.app')

@section('content')
    <h1>Správa uživatelů</h1>
    <div class="mb-3">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        + Přidat uživatele
        </a>
    </div>


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
                    <td>   @if ($user->role->value === 'deactivated')
        <span style="color: red; font-weight: bold;">Neaktivní</span><br>
  
    @else
        <span style="color: green; font-weight: bold;">{{ ucfirst($user->role->value) }}</span><br>
 
    @endif</td>
                    <td>
                        @include('components.edit-button', [
                            'href' => route('admin.users.edit', $user),
                            'label' => 'Upravit',
                            'small' => true
                        ])

                        @include('components.delete-button', [
                            'action' => route('admin.users.destroy', $user),
                            'label' => 'Smazat',
                            'confirm' => 'Opravdu smazat?',
                            'small' => true
                        ])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
@endsection
