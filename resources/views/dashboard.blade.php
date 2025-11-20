@extends('layouts.app')

@php
    $role = auth()->user()->role instanceof \App\Enums\UserRole 
        ? auth()->user()->role->value 
        : auth()->user()->role;
@endphp

@section('content')
    <h1>Vítej v systému, {{ auth()->user()->name }} {{ auth()->user()->surname }}!</h1>

    <!-- <p>Tvá role: 
        <strong>{{ $role }}</strong>
    </p> -->


    {{-- -------------------------
        ODKAZY PODLE ROLE
    -------------------------- --}}

    <a href="{{ route('campaigns.manage') }}" class="btn btn-warning mt-3">
    Správa pracovníků kampaní
</a>
    <a href="{{ route('workspace') }}" class="btn btn-primary mt-3">
        Můj pracovní prostor
    </a>
    @if (in_array($role, ['campaign_manager', 'admin']))
    <a href="{{ route('dashboard.campaigns') }}" class="btn btn-primary mt-3">
        Přehled kampaní
    </a>
@endif


    <!-- @if ($role === 'admin')
        <p><a href="{{ route('admin.users.index') }}">Správa uživatelů</a></p>
    @endif

    <p><a href="{{ route('topics.index') }}">Zobrazit témata</a></p>
    <p><a href="{{ route('profile.edit') }}">Můj profil</a></p> -->


    {{-- -------------------------
        NEPOTVRZENÉ ŽÁDOSTI K AKTIVITÁM
    -------------------------- --}}

    <hr>

<h2>Moje žádosti o účast na aktivitách</h2>

@php
    // všechny aktivity, na které je aktuální uživatel přihlášen
    $activities = auth()->user()->activities()
        ->with(['step.campaign'])
        ->get();
@endphp

@if($activities->isEmpty())
    <p class="text-muted">Zatím jste nezaslali žádnou žádost.</p>
@else

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>Aktivita</th>
            <th>Krok</th>
            <th>Kampaň</th>
            <th>Stav</th>
        </tr>
    </thead>

    <tbody>
        @foreach($activities as $activity)
            <tr>
                <td>{{ $activity->name }}</td>
                <td>{{ $activity->step->name }}</td>
                <td>{{ $activity->step->campaign->name }}</td>

                <td>
                    @php $status = $activity->pivot->is_confirmed; @endphp

                    @if($status == 0)
                        <span class="badge bg-warning">Čeká na potvrzení</span>
                    @elseif($status == 1)
                        <span class="badge bg-success">Potvrzeno</span>
                    @else
                        <span class="badge bg-danger">Zamítnuto</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endif


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
                    <th>Stav / Akce</th>
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

                        <td>
                            {{-- Stav 0 = čeká --}}
                            @if($req->is_confirmed == 0)

                                @can('manage', $req)
                                    <div class="d-flex">

                                        {{-- Potvrdit --}}
                                        <form method="POST" action="{{ route('activityUsers.confirm', $req->id) }}" class="me-2">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-success btn-sm">Potvrdit</button>
                                        </form>

                                        {{-- Odmítnout --}}
                                        <form method="POST" action="{{ route('activityUsers.reject', $req->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-danger btn-sm">Odmítnout</button>
                                        </form>

                                    </div>
                                @else
                                    <span class="text-muted">Nemáte oprávnění</span>
                                @endcan

                            {{-- Stav 1 = schváleno --}}
                            @elseif($req->is_confirmed == 1)
                                <span class="badge bg-success">Potvrzeno</span>

                            {{-- Stav 2 = zamítnuto --}}
                            @elseif($req->is_confirmed == 2)
                                <span class="badge bg-danger">Zamítnuto</span>

                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4 d-flex justify-content-center">
            {{ $requests->links() }}
        </div>

    @else
        <p class="text-muted">Žádné čekající žádosti.</p>
    @endif

@endif



@endsection
