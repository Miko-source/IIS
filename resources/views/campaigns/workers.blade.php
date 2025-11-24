@extends('layouts.app')

@section('content')
<div class="container">


    <h1 class="mb-1">Správa pracovníků kampaně</h1>
    <h5 class="text-muted mb-3">{{ $campaign->name }}</h5>

    <p class="mb-3">
        <strong>Správce:</strong>
        @if($campaign->manager)
            {{ $campaign->manager->name }} {{ $campaign->manager->surname }}
        @else
            — žádný —
        @endif
    </p>
       {{--Přidání pracovníka --}}
    <div class="card mb-3">
        <div class="card-header py-2">
            Přidat pracovníka
        </div>
        <div class="card-body py-3">

            <form action="{{ route('campaigns.workers.add', $campaign->id) }}" method="POST">
                @csrf

                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <select name="user_id" class="form-select form-select-sm" required>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} {{ $user->surname }} ({{ $user->role }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-4">
                        <button class="btn btn-primary btn-sm w-100">Přidat</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    {{--Koordinátoři kroků --}}
    <div class="accordion mb-4" id="campaignManagement">

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSteps">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseSteps">
                    Koordinátoři kroků
                </button>
            </h2>

            <div id="collapseSteps" class="accordion-collapse collapse show">
                <div class="accordion-body">

                    @if($campaign->steps->isEmpty())
                        <p class="text-muted">Tato kampaň nemá žádné kroky.</p>
                    @else
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Krok</th>
                                    <th>Koordinátor</th>
                                    <th width="140">Akce</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($campaign->steps as $step)
                                    <tr>
                                        <td>{{ $step->name }}</td>

                                        <td>
                                            <form method="POST"
                                                  action="{{ route('campaigns.steps.coordinator.update', [$campaign->id, $step->id]) }}">
                                                @csrf
                                                @method('PATCH')

                                                <select name="user_id" class="form-select form-select-sm">
                                                    <option value="">— žádný —</option>

                                                    @foreach($coordinators as $coord)
                                                        <option value="{{ $coord->id }}"
                                                            {{ $step->user_id == $coord->id ? 'selected' : '' }}>
                                                            {{ $coord->name }} {{ $coord->surname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                        </td>

                                        <td>
                                            <button class="btn btn-primary btn-sm w-100">Uložit</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        </div>

    </div>

 

    {{--
         Seznam pracovníků--}}
    <div class="card mb-4">
        <div class="card-header py-2">
            Pracovníci v kampani
        </div>
        <div class="card-body py-3">

            @if($assignedUsers->isEmpty())
                <p class="text-muted mb-0">V kampani nejsou přiřazeni žádní pracovníci.</p>
            @else
                <table class="table table-sm align-middle table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Jméno</th>
                            <th>Email</th>
                            <th>Role v kampani</th>
                            <th width="110">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($assignedUsers as $u)
                        <tr>
                            <td>{{ $u->name }} {{ $u->surname }}</td>
                            <td>{{ $u->email }}</td>

                            <td>
                                @switch($u->campaign_position)
                                    @case('manager')
                                        Campaign_manager
                                        @break

                                    @case('coordinator')
                                        Coordinator
                                        @break
                                    @case('activity_worker')
                                        Realizator 
                                    @break

                                    @case('worker')
                                        Worker
                                        @break

                                    

                                    @default
                                        —
                                @endswitch
                            </td>

                            <td>
                                <form action="{{ route('campaigns.workers.remove', [$campaign->id, $u->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm w-100"
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

</div>
@endsection
