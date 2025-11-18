@extends('layouts.app')

@section('content')
    <h1>Vítej v systému, {{ auth()->user()->name }} {{ auth()->user()->surname }}!</h1>

    <p>Tvá role: 
        <strong>
            {{ auth()->user()->role instanceof \App\Enums\UserRole 
                ? auth()->user()->role->value 
                : auth()->user()->role }}
        </strong>
    </p>

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
        <a href="{{ route('topics.index') }}">Zobrazit témata</a>
    </p>
        <p>
        <a href="{{ route('profile.edit') }}">Muj profil</a>
    </p>
@endsection
