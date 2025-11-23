@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-3">
        @include('components.back-link', [
            'target' => route('campaign.steps.show', [
                'campaign' => $activity->step->campaign,
                'step'     => $activity->step,
                'activity' => $activity,
            ]),
            'label'  => '← Zpět na aktivitu'
        ])
    </div>

    <h1>Spravovat realizátory – {{ $activity->name }}</h1>
    <p><strong>Kampaň:</strong> {{ $campaign->name }}</p>

    <div class="row">

        {{-- Aktuální realizátoři --}}
        <div class="col-md-6 mb-4">
            <h3>Aktuální realizátoři</h3>

            @if($assignedUsers->isEmpty())
                <p class="text-muted">K aktivitě zatím nejsou přiřazeni žádní realizátoři.</p>
            @else
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Jméno</th>
                            <th>E-mail</th>
                            <th>Stav</th>
                            <th>Má zprávu?</th>  {{-- ➕ nový sloupec --}}
                            <th>Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedUsers as $worker)
                            @php
                                $hasMessage = in_array($worker->id, $usersWithMessages);
                            @endphp

                            <tr>
                                <td>{{ $worker->name }} {{ $worker->surname }}</td>
                                <td>{{ $worker->email }}</td>

                                <td>
                                    @if($worker->pivot->is_confirmed == 1)
                                        potvrzen
                                    @elseif($worker->pivot->is_confirmed === 0)
                                        čeká na potvrzení
                                    @elseif($worker->pivot->is_confirmed == 2)
                                        odmítnut
                                    @endif

                                    @if($worker->pivot->is_completed)
                                        , dokončeno
                                    @endif
                                </td>

                                {{-- 🔥 NOVÝ SLOUPEC --}}
                                <td>
                                    @if($hasMessage)
                                        <span class="badge bg-success">Ano</span>
                                    @else
                                        <span class="badge bg-secondary">Ne</span>
                                    @endif
                                </td>

                                <td>
                                    {{-- Formulář mazání --}}
                                    <form action="{{ route('activities.workers.destroy', [$activity, $worker]) }}"
                                          method="POST"
                                          onsubmit="return confirm('{{ $hasMessage 
                                                ? 'Tento realizátor již přidal zprávu k aktivitě. Jeho zpráva bude odebráním nenávratně smazána. Opravdu chcete pokračovat?' 
                                                : 'Opravdu odebrat tohoto realizátora z aktivity?' }}')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-outline-danger">
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

        {{-- Přidání nových --}}
        <div class="col-md-6 mb-4">
            <h3>Přidat realizátora k aktivitě</h3>

            @if($availableWorkers->isEmpty())
                <p class="text-muted">Žádní dostupní realizátoři.</p>
            @else
                <form action="{{ route('activities.workers.store', $activity) }}" method="POST" class="d-flex gap-2">
                    @csrf

                    <select name="user_id" class="form-select form-select-sm" required>
                        <option value="">Vyberte realizátora...</option>

                        @foreach($availableWorkers as $worker)
                            <option value="{{ $worker->id }}">
                                {{ $worker->name }} {{ $worker->surname }}
                                ({{ $worker->email }}) — role: {{ $worker->role }}
                            </option>
                        @endforeach
                    </select>

                    <button class="btn btn-sm btn-outline-primary">
                        Přidat
                    </button>
                </form>
            @endif

            <p class="mt-2 text-muted small">
                * Pokud realizátor není členem kampaně, bude automaticky přidán.
            </p>
        </div>
    </div>
</div>
@endsection
