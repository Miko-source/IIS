@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Správa pracovníků kampaně</h1>
    <h3 class="text-muted">{{ $campaign->name }}</h3>

    {{-- Flash zprávy --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    {{-- ===========================
         Přidání pracovníka
       =========================== --}}
    <div class="card mb-4">
        <div class="card-header">Přidat pracovníka</div>
        <div class="card-body">

            <form action="{{ route('campaigns.workers.add', $campaign->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Vyber uživatele</label>
                    <select name="user_id" class="form-select" required>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} {{ $user->surname }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary mt-3">Přidat</button>
            </form>

        </div>
    </div>



    {{-- ===========================
         Správce kampaně (1 správce)
       =========================== --}}
    <div class="card mb-4">
        <div class="card-header">Správce kampaně</div>

        <div class="card-body">

            <form method="POST" action="{{ route('campaigns.manager.update', $campaign->id) }}">
                @csrf
                @method('PATCH')

                <div class="row align-items-center">

                    <div class="col-md-6">
                        <select name="user_id" class="form-select">

                            <option value="">— žádný správce —</option>

                            @foreach($allUsers as $u)
                                <option value="{{ $u->id }}"
                                    {{ $campaign->user_id == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} {{ $u->surname }} ({{ $u->role }})
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary">Uložit správce</button>
                    </div>

                </div>
            </form>

        </div>
    </div>



    {{-- ===========================
         Seznam pracovníků v kampani
       =========================== --}}
    <div class="card mb-4">
        <div class="card-header">Pracovníci v kampani</div>
        <div class="card-body">

            @if($assignedUsers->isEmpty())
                <p class="text-muted">V kampani nejsou přiřazeni žádní pracovníci.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Jméno</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedUsers as $u)
                            <tr>
                                <td>{{ $u->name }} {{ $u->surname }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->role }}</td>

                                <td>
                                    <form action="{{ route('campaigns.workers.remove', [$campaign->id, $u->id]) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Odebrat pracovníka?')">
                                            Odebrat
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>



    {{-- ===========================
         Koordinátoři kroků (právě jeden na krok)
       =========================== --}}
    <div class="card mb-4">
        <div class="card-header">Koordinátoři kroků</div>

        <div class="card-body">

            @if($campaign->steps->isEmpty())
                <p class="text-muted">Tato kampaň nemá žádné kroky.</p>
            @else

                @foreach($campaign->steps as $step)
                    <div class="card mb-3">
                        <div class="card-body">

                            <h5 class="mb-3">{{ $step->name }}</h5>

                            <form method="POST"
                                  action="{{ route('campaigns.steps.coordinator.update', [$campaign->id, $step->id]) }}">
                                @csrf
                                @method('PATCH')

                                <div class="row align-items-center">

                                    <div class="col-md-6">
                                        <select name="user_id" class="form-select">

                                            <option value="">— žádný koordinátor —</option>

                                            @foreach($coordinators as $coord)
                                                <option value="{{ $coord->id }}"
                                                    {{ $step->user_id == $coord->id ? 'selected' : '' }}>
                                                    {{ $coord->name }} {{ $coord->surname }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <button class="btn btn-primary">
                                            Uložit koordinátora
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                @endforeach

            @endif

        </div>
    </div>


</div>
@endsection
