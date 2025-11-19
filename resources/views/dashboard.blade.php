@extends('layouts.app')

@php
    $role = auth()->user()->role instanceof \App\Enums\UserRole 
        ? auth()->user()->role->value 
        : auth()->user()->role;
@endphp

@section('content')
    <h1>Vítej v systému, {{ auth()->user()->name }} {{ auth()->user()->surname }}!</h1>

    <p>Tvá role: 
        <strong>{{ $role }}</strong>
    </p>


    {{-- -------------------------
        ODKAZY PODLE ROLE
    -------------------------- --}}
    @if ($role === 'admin')
        <p><a href="{{ route('admin.users.index') }}">Správa uživatelů</a></p>
    @endif

    <p><a href="{{ route('topics.index') }}">Zobrazit témata</a></p>
    <p><a href="{{ route('profile.edit') }}">Můj profil</a></p>


    {{-- -------------------------
        NEPOTVRZENÉ ŽÁDOSTI K AKTIVITÁM
    -------------------------- --}}

    <hr>
    @if ($role !== 'worker')

    <h2>Nepotvrzené žádosti o účast na aktivitách</h2>



    @if(isset($requests) && $requests->isNotEmpty())
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Pracovník</th>
                    <th>Aktivita</th>
                    <th>Krok</th>
                    <th>Kampaň</th>
                    <th>Přihlášen</th>
                </tr>
            </thead>

            <tbody>
                @foreach($requests as $req)
                    <tr>
                        <td>{{ $req->user->name }} {{ $req->user->surname }}</td>
                        <td>{{ $req->activity->name }}</td>
                        <td>{{ $req->activity->step->name }}</td>
                        <td>{{ $req->activity->step->campaign->name }}</td>
                        <td>{{ $req->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else
        <p class="text-muted">Žádné čekající žádosti.</p>
    @endif
    @endif

@endsection
